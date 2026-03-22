<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
    exit;
}

$fcObj = new DataFunctions();

$userData = $fcObj->userCheck(TB_USERS, $_SESSION['userName']);
if (empty($userData)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$user = $userData[0];
$admissionId = trim((string)($user['admission_id'] ?? ''));
$achievements = $admissionId !== '' ? $fcObj->getAchievementsForAdmission(TB_ACHIEVEMENTS, $admissionId) : array();
$documentCount = 0;
$textCount = 0;
foreach ($achievements as $achievementRow) {
    if ((int)($achievementRow['category_id'] ?? 0) === DOCUMENT) {
        $documentCount++;
    } else {
        $textCount++;
    }
}

function app_safe_achievement_file_url($fileName) {
    $fileName = trim((string)$fileName);
    if ($fileName === '' || preg_match('/^[A-Za-z0-9._-]+$/', $fileName) !== 1) {
        return '';
    }
    $diskPath = ROOT_PATH . '/public/assets/images/achievements/' . $fileName;
    if (!is_file($diskPath)) {
        return '';
    }
    return BASE_URL . '/public/assets/images/achievements/' . rawurlencode($fileName);
}

function app_format_achievement_meta($desc) {
    $desc = trim((string)$desc);
    $parts = explode(' - ', $desc, 3);
    if (count($parts) === 3) {
        return array('context' => $parts[1], 'text' => $parts[2]);
    }
    return array('context' => '', 'text' => $desc);
}

function app_guess_achievement_time($fileName) {
    $fileName = trim((string)$fileName);
    if (preg_match('/_([0-9]{14})_/', $fileName, $m) !== 1) {
        return '';
    }
    $raw = $m[1];
    $dt = DateTime::createFromFormat('YmdHis', $raw);
    if (!$dt) {
        return '';
    }
    return $dt->format('Y-m-d H:i');
}

include_once(INCLUDES_PATH . '/header.php');

$userActivePage = 'my_achievements';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.student-page{--sp-primary:#173d69;--sp-primary-deep:#13345a;--sp-accent:#f0b323;--sp-accent-deep:#d79a12;--sp-surface:#eef4fa;--sp-card:#fff;--sp-border:#d8e3ef;--sp-text:#284767;--sp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.student-hero{position:relative;overflow:hidden;border:1px solid var(--sp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--sp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.student-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--sp-accent),var(--sp-accent-deep))}.student-kicker,.student-tag,.student-type-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--sp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.student-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--sp-accent),var(--sp-accent-deep))}.student-hero h1{margin:14px 0 10px;color:var(--sp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.student-hero p{margin:0;max-width:820px;color:var(--sp-muted);font-size:15px;line-height:1.7}.student-meta-line{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}.student-meta-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #d5e1ee;background:rgba(255,255,255,.88);color:var(--sp-text);font-size:14px;font-weight:700}.student-meta-pill strong{color:var(--sp-primary)}.student-stat-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.student-stat-card,.student-panel,.student-achievement-card{border:1px solid var(--sp-border);border-radius:22px;background:var(--sp-card);box-shadow:0 12px 24px rgba(15,23,42,.06)}.student-stat-card{padding:20px}.student-panel{padding:22px}.student-stat-label{margin:0 0 8px;color:var(--sp-muted);font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.student-stat-value{margin:0;color:var(--sp-primary-deep);font-size:28px;font-weight:800;line-height:1.1}.student-stat-note{margin:8px 0 0;color:var(--sp-text);font-size:14px;line-height:1.5}.student-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.student-panel-title{margin:0;color:var(--sp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.student-panel-subtitle{margin:6px 0 0;color:var(--sp-muted);font-size:14px}.student-achievement-list{display:grid;gap:14px}.student-achievement-card{padding:18px}.student-achievement-top{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:12px}.student-achievement-title{margin:0;color:var(--sp-primary-deep);font-size:18px;font-weight:800;line-height:1.5}.student-achievement-context{margin:6px 0 0;color:var(--sp-muted);font-size:14px}.student-achievement-footer{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:14px}.student-link-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:11px 16px;font-weight:800;text-decoration:none;background:linear-gradient(135deg,var(--sp-primary),var(--sp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.student-date{color:var(--sp-muted);font-size:13px;font-weight:700}.student-empty-note{border:1px dashed #ccd9e8;border-radius:16px;padding:16px;color:var(--sp-muted);background:#f9fbfe;font-size:14px;line-height:1.6}
@media(max-width:1199px){.student-stat-grid{grid-template-columns:1fr}}
@media(max-width:767px){.student-page{gap:16px}.student-hero,.student-panel,.student-stat-card,.student-achievement-card{padding:18px;border-radius:20px}.student-panel-header,.student-achievement-top{flex-direction:column;align-items:flex-start}}
</style>
<div class="student-page">
    <section class="student-hero">
        <span class="student-kicker">My Achievements</span>
        <h1>Achievement History</h1>
        <p>Track every achievement you have submitted, including document uploads and text recognitions, from one cleaner and more readable student page.</p>
        <div class="student-meta-line">
            <span class="student-meta-pill"><strong>Total</strong> <?php echo count($achievements); ?></span>
            <span class="student-meta-pill"><strong>Document</strong> <?php echo $documentCount; ?></span>
            <span class="student-meta-pill"><strong>Text</strong> <?php echo $textCount; ?></span>
        </div>
    </section>

    <section class="student-stat-grid">
        <article class="student-stat-card"><p class="student-stat-label">All Submissions</p><p class="student-stat-value"><?php echo count($achievements); ?></p><p class="student-stat-note">Combined achievement entries linked to your admission record.</p></article>
        <article class="student-stat-card"><p class="student-stat-label">Document Entries</p><p class="student-stat-value"><?php echo $documentCount; ?></p><p class="student-stat-note">Achievements submitted with a file attachment.</p></article>
        <article class="student-stat-card"><p class="student-stat-label">Text Entries</p><p class="student-stat-value"><?php echo $textCount; ?></p><p class="student-stat-note">Achievements submitted as text-only descriptions.</p></article>
    </section>

    <section class="student-panel">
        <div class="student-panel-header">
            <div><h2 class="student-panel-title">Submitted Achievements</h2><p class="student-panel-subtitle">Review your entries and open any attached supporting files.</p></div>
            <a class="student-link-btn" href="<?php echo BASE_URL; ?>/public/pages/user/achievements.php">Upload New</a>
        </div>

        <?php if (empty($achievements)) { ?>
            <div class="student-empty-note">No achievements submitted yet. Use the upload page to add your first achievement.</div>
        <?php } else { ?>
            <div class="student-achievement-list">
                <?php foreach ($achievements as $row) { ?>
                    <?php
                        $rawDesc = (string)($row['achievement_desc'] ?? '');
                        $fileName = '';
                        $descText = $rawDesc;
                        if (strpos($rawDesc, '$$') !== false) {
                            $split = explode('$$', $rawDesc, 2);
                            $descText = $split[0];
                            $fileName = $split[1] ?? '';
                        }
                        $meta = app_format_achievement_meta($descText);
                        $fileUrl = app_safe_achievement_file_url($fileName);
                        $submittedAt = $fileName !== '' ? app_guess_achievement_time($fileName) : '';
                        $typeLabel = ((int)($row['category_id'] ?? 0) === DOCUMENT) ? 'Document' : 'Text';
                    ?>
                    <article class="student-achievement-card">
                        <div class="student-achievement-top">
                            <div>
                                <h3 class="student-achievement-title"><?php echo htmlspecialchars($meta['text'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <?php if ($meta['context'] !== '') { ?><p class="student-achievement-context"><?php echo htmlspecialchars($meta['context'], ENT_QUOTES, 'UTF-8'); ?></p><?php } ?>
                            </div>
                            <span class="student-type-badge"><?php echo htmlspecialchars($typeLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                        </div>
                        <div class="student-achievement-footer">
                            <?php if ($fileUrl !== '') { ?><a class="student-link-btn" href="<?php echo htmlspecialchars($fileUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Open File</a><?php } ?>
                            <span class="student-date"><?php echo $submittedAt !== '' ? htmlspecialchars($submittedAt, ENT_QUOTES, 'UTF-8') : 'Submission time unavailable'; ?></span>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
</div>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>
