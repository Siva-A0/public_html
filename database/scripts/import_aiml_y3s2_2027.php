<?php
if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This script must be run from the command line.\n");
    exit(1);
}

require_once __DIR__ . '/../../config.php';
require_once LIB_PATH . '/functions.class.php';

$defaults = array(
    'batch_id' => 8,
    'class_id' => 27,
    'default_password' => 'Aiml@123',
    'default_status' => 1,
    'dry_run' => false,
    'file' => ROOT_PATH . '/AIML3rdYear2ndSem-Batch2027StudentData(Responses)-FormResponses.csv',
);

$options = $defaults;

foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--dry-run') {
        $options['dry_run'] = true;
        continue;
    }
    if (strpos($arg, '--file=') === 0) {
        $options['file'] = substr($arg, 7);
        continue;
    }
    if (strpos($arg, '--batch-id=') === 0) {
        $options['batch_id'] = (int)substr($arg, 11);
        continue;
    }
    if (strpos($arg, '--class-id=') === 0) {
        $options['class_id'] = (int)substr($arg, 11);
        continue;
    }
    if (strpos($arg, '--default-password=') === 0) {
        $options['default_password'] = (string)substr($arg, 19);
        continue;
    }
    if (strpos($arg, '--default-status=') === 0) {
        $options['default_status'] = (int)substr($arg, 17);
        continue;
    }
    if ($arg === '--help' || $arg === '-h') {
        echo "Usage:\n";
        echo "  php database/scripts/import_aiml_y3s2_2027.php [--file=PATH] [--batch-id=8] [--class-id=27] [--default-password=Aiml@123] [--default-status=1] [--dry-run]\n";
        echo "\n";
        echo "Expected CSV columns:\n";
        echo "  username, admission_id, firstname, lastname, mail_id, gender, address, mobile_no, section, password, status\n";
        exit(0);
    }
}

$csvFile = $options['file'];
if (!is_file($csvFile) || !is_readable($csvFile)) {
    fwrite(STDERR, "CSV file not found or not readable: {$csvFile}\n");
    exit(1);
}

$fcObj = new DataFunctions();
$db = new DataBasePDO();

$sectionRows = $db->getAllPrepared(
    "SELECT id, section_code, section_name
     FROM `" . TB_SECTION . "`
     WHERE batch_id = :batch_id AND class_id = :class_id
     ORDER BY id",
    array(
        ':batch_id' => (int)$options['batch_id'],
        ':class_id' => (int)$options['class_id'],
    )
);

if (empty($sectionRows)) {
    fwrite(STDERR, "No sections found for batch_id {$options['batch_id']} and class_id {$options['class_id']}.\n");
    exit(1);
}

$sectionMap = array();
foreach ($sectionRows as $row) {
    $keys = array(
        strtoupper(trim((string)$row['section_code'])),
        strtoupper(trim((string)$row['section_name'])),
    );
    foreach ($keys as $key) {
        if ($key !== '') {
            $sectionMap[$key] = (int)$row['id'];
        }
    }
}

function normalize_section_key($value)
{
    $value = strtoupper(trim((string)$value));
    if ($value === '') {
        return '';
    }
    $value = str_replace(array('_', '-'), ' ', $value);
    $value = preg_replace('/\s+/', ' ', $value);
    if (preg_match('/^SECTION\s+([A-Z0-9]+)$/', $value, $matches)) {
        return $matches[1];
    }
    return $value;
}

$handle = fopen($csvFile, 'r');
if ($handle === false) {
    fwrite(STDERR, "Unable to open CSV file: {$csvFile}\n");
    exit(1);
}

$header = fgetcsv($handle);
if ($header === false) {
    fclose($handle);
    fwrite(STDERR, "CSV file is empty: {$csvFile}\n");
    exit(1);
}

$headerMap = array();
foreach ($header as $index => $column) {
    $headerMap[strtolower(trim((string)$column))] = $index;
}

$aliases = array(
    'first name' => 'firstname',
    'first_name' => 'firstname',
    'last name' => 'lastname',
    'last_name' => 'lastname',
    'email' => 'mail_id',
    'e-mail' => 'mail_id',
    'phone number' => 'mobile_no',
    'phone' => 'mobile_no',
    'mobile' => 'mobile_no',
);
foreach ($aliases as $source => $target) {
    if (isset($headerMap[$source]) && !isset($headerMap[$target])) {
        $headerMap[$target] = $headerMap[$source];
    }
}

$requiredColumns = array('username', 'admission_id', 'firstname', 'lastname', 'section');
foreach ($requiredColumns as $column) {
    if (!array_key_exists($column, $headerMap)) {
        fclose($handle);
        fwrite(STDERR, "Missing required CSV column: {$column}\n");
        exit(1);
    }
}

$lineNumber = 1;
$inserted = 0;
$skipped = 0;
$failed = 0;

while (($row = fgetcsv($handle)) !== false) {
    $lineNumber++;

    $read = function ($name) use ($headerMap, $row) {
        if (!array_key_exists($name, $headerMap)) {
            return '';
        }
        $index = $headerMap[$name];
        return isset($row[$index]) ? trim((string)$row[$index]) : '';
    };

    $username = $read('username');
    $admissionId = $read('admission_id');
    $firstName = $read('firstname');
    $lastName = $read('lastname');
    $mailId = $read('mail_id');
    $sectionRaw = $read('section');

    if ($username === '' && $admissionId === '' && $firstName === '' && $lastName === '' && $mailId === '' && $sectionRaw === '') {
        $skipped++;
        continue;
    }

    if ($username === '' || $admissionId === '' || $firstName === '' || $lastName === '' || $sectionRaw === '') {
        echo "[line {$lineNumber}] skipped: required values missing.\n";
        $failed++;
        continue;
    }

    $username = strtoupper(trim($username));
    $admissionId = strtoupper(trim($admissionId));
    $mailId = $admissionId . '@nrcmec.org';

    $sectionKey = normalize_section_key($sectionRaw);
    if (!isset($sectionMap[$sectionKey])) {
        echo "[line {$lineNumber}] skipped: unknown section '{$sectionRaw}'. Allowed sections: A, B, C.\n";
        $failed++;
        continue;
    }

    $plainPassword = $options['default_password'];

    $status = $read('status');
    $status = $status === '' ? (int)$options['default_status'] : (int)$status;

    $record = array(
        'username' => $username,
        'password' => $fcObj->hashPassword($plainPassword),
        'mail_id' => $mailId,
        'firstname' => $firstName,
        'lastname' => $lastName,
        'gender' => $read('gender'),
        'address' => $read('address'),
        'mobile_no' => $read('mobile_no'),
        'batch_id' => (int)$options['batch_id'],
        'stream_id' => 0,
        'section' => (string)$sectionMap[$sectionKey],
        'admission_id' => $admissionId,
        'image' => '',
        'status' => $status,
    );

    if ($options['dry_run']) {
        echo "[line {$lineNumber}] ready: {$admissionId} -> section {$sectionRaw} (section_id {$record['section']})\n";
        $inserted++;
        continue;
    }

    $result = $fcObj->regUser(TB_USERS, $record);
    if ($result === true || $result === 1) {
        echo "[line {$lineNumber}] inserted: {$admissionId}\n";
        $inserted++;
        continue;
    }

    echo "[line {$lineNumber}] skipped: {$admissionId} ({$result})\n";
    $failed++;
}

fclose($handle);

echo "\nSummary\n";
echo "file: {$csvFile}\n";
echo "batch_id: {$options['batch_id']}\n";
echo "class_id: {$options['class_id']}\n";
echo "inserted_or_ready: {$inserted}\n";
echo "blank_rows_skipped: {$skipped}\n";
echo "failed_or_duplicate: {$failed}\n";
