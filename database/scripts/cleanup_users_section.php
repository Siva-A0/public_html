<?php
/**
 * One-time data cleanup:
 * Convert legacy students.section values (strings/'0') into numeric section.id.
 *
 * Usage (dry-run / preview):
 *   php database/scripts/cleanup_users_section.php
 *
 * Apply updates:
 *   php database/scripts/cleanup_users_section.php --apply
 *
 * Optional:
 *   --limit=500   (only process first N legacy students)
 */

if (php_sapi_name() !== 'cli') {
    http_response_code(403);
    echo "CLI only.\n";
    exit(1);
}

require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/mysql.class.php');

function arg_flag($name)
{
    global $argv;
    return in_array($name, $argv, true);
}

function arg_value($prefix, $default = null)
{
    global $argv;
    foreach ($argv as $arg) {
        if (strpos($arg, $prefix) === 0) {
            $val = substr($arg, strlen($prefix));
            return $val === '' ? $default : $val;
        }
    }
    return $default;
}

function normalize_section_label($raw)
{
    $raw = strtoupper(trim((string)$raw));
    if ($raw === '') {
        return '';
    }
    if ($raw === '0') {
        return '0';
    }

    if ($raw === 'OTHER' || $raw === 'OTHERS') {
        return 'OTHER';
    }

    if (preg_match('/\\bSECTION\\s*[- ]\\s*([A-Z])\\b/', $raw, $m)) {
        return $m[1];
    }
    if (preg_match('/\\bSEC\\s*[- ]\\s*([A-Z])\\b/', $raw, $m2)) {
        return $m2[1];
    }
    if (preg_match('/\\b([A-Z])\\b/', $raw, $m3) && strlen($raw) <= 3) {
        return $m3[1];
    }

    // Keep original string for exact matches (e.g. legacy section_code).
    return trim((string)$raw);
}

function is_numeric_string($val)
{
    $val = trim((string)$val);
    return $val !== '' && ctype_digit($val);
}

$apply = arg_flag('--apply');
$limit = (int)arg_value('--limit=', 0);
if ($limit < 0) {
    $limit = 0;
}

$db = new DataBasePDO(true);

// Detect if section has batch_id.
$batchScoped = false;
try {
    $cols = $db->getAllResults("SHOW COLUMNS FROM `section` LIKE 'batch_id'");
    $batchScoped = !empty($cols);
} catch (Exception $e) {
    $batchScoped = false;
}

// Preview candidates first.
$sqlUsers = "SELECT id, username, mail_id, admission_id, batch_id, section
             FROM `students`
             WHERE section IS NULL
                OR section = ''
                OR section = '0'
                OR section NOT REGEXP '^[0-9]+$'
             ORDER BY id ASC";
if ($limit > 0) {
    $sqlUsers .= " LIMIT " . (int)$limit;
}

$legacyUsers = $db->getAllResults($sqlUsers);

$proposed = array();
$skipped = array();
$ambiguous = array();

foreach ($legacyUsers as $u) {
    $userId = (int)($u['id'] ?? 0);
    $batchId = (int)($u['batch_id'] ?? 0);
    $sectionRaw = (string)($u['section'] ?? '');

    if ($userId <= 0) {
        continue;
    }

    if (is_numeric_string($sectionRaw) && (int)$sectionRaw > 0) {
        continue;
    }

    $label = normalize_section_label($sectionRaw);
    if ($label === '' || $label === '0') {
        $skipped[] = array('id' => $userId, 'section' => $sectionRaw, 'reason' => 'empty/0');
        continue;
    }

    // 1) Exact match on section_code or section_name.
    $where = array("(s.section_code = :exact OR s.section_name = :exact)");
    $params = array(':exact' => $label);
    if ($batchScoped) {
        $where[] = "s.batch_id = :batch_id";
        $params[':batch_id'] = $batchId;
    }
    $rows = $db->getAllPrepared(
        "SELECT s.id, s.class_id, s.section_code, s.section_name
         FROM `section` s
         WHERE " . implode(' AND ', $where),
        $params
    );

    if (count($rows) === 1) {
        $proposed[] = array(
            'user_id' => $userId,
            'from' => $sectionRaw,
            'to' => (int)$rows[0]['id'],
            'match' => 'exact'
        );
        continue;
    }

    // 2) If label is a single-letter section (A/B/C/...) try pattern match.
    if (preg_match('/^[A-Z]$/', $label) === 1) {
        $where2 = array("(UPPER(s.section_code) REGEXP :pat OR UPPER(s.section_name) REGEXP :pat)");
        $params2 = array(':pat' => '(^|[^A-Z0-9])' . $label . '([^A-Z0-9]|$)');
        if ($batchScoped) {
            $where2[] = "s.batch_id = :batch_id";
            $params2[':batch_id'] = $batchId;
        }

        $rows2 = $db->getAllPrepared(
            "SELECT s.id, s.class_id, s.section_code, s.section_name
             FROM `section` s
             WHERE " . implode(' AND ', $where2) . "
             ORDER BY s.id DESC",
            $params2
        );

        if (count($rows2) === 1) {
            $proposed[] = array(
                'user_id' => $userId,
                'from' => $sectionRaw,
                'to' => (int)$rows2[0]['id'],
                'match' => 'pattern'
            );
            continue;
        }

        if (count($rows2) > 1) {
            $ambiguous[] = array(
                'id' => $userId,
                'section' => $sectionRaw,
                'reason' => 'multiple sections match letter within batch'
            );
            continue;
        }
    }

    $skipped[] = array('id' => $userId, 'section' => $sectionRaw, 'reason' => 'no match');
}

echo "Section batch-scoped: " . ($batchScoped ? 'YES' : 'NO') . "\n";
echo "legacy students found: " . count($legacyUsers) . "\n";
echo "Proposed updates: " . count($proposed) . "\n";
echo "Ambiguous (manual): " . count($ambiguous) . "\n";
echo "Skipped (no match/0): " . count($skipped) . "\n\n";

if (!empty($proposed)) {
    echo "Preview (first 25 proposed):\n";
    $n = 0;
    foreach ($proposed as $p) {
        $n++;
        if ($n > 25) {
            break;
        }
        echo "  user_id={$p['user_id']} section='{$p['from']}' -> {$p['to']} ({$p['match']})\n";
    }
    echo "\n";
}

if (!empty($ambiguous)) {
    echo "Ambiguous (first 25):\n";
    $n = 0;
    foreach ($ambiguous as $a) {
        $n++;
        if ($n > 25) {
            break;
        }
        echo "  user_id={$a['id']} section='{$a['section']}' ({$a['reason']})\n";
    }
    echo "\n";
}

if (!$apply) {
    echo "Dry-run only. Re-run with --apply to perform updates.\n";
    exit(0);
}

if (empty($proposed)) {
    echo "Nothing to update.\n";
    exit(0);
}

echo "Applying updates...\n";

try {
    $db->executeQuery("START TRANSACTION");
    $updated = 0;
    foreach ($proposed as $p) {
        $ok = $db->executePrepared(
            "UPDATE `students` SET section = :section_id WHERE id = :id",
            array(':section_id' => (string)(int)$p['to'], ':id' => (int)$p['user_id'])
        );
        if ($ok !== false) {
            $updated++;
        }
    }
    $db->executeQuery("COMMIT");
    echo "Done. Updated rows: " . $updated . "\n";
} catch (Exception $e) {
    try {
        $db->executeQuery("ROLLBACK");
    } catch (Exception $e2) {}
    echo "Failed: " . $e->getMessage() . "\n";
    exit(1);
}

