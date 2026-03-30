<?php
if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This script must be run from the command line.\n");
    exit(1);
}

require_once __DIR__ . '/../../config.php';
require_once LIB_PATH . '/functions.class.php';

$defaults = array(
    'file' => ROOT_PATH . '/database/templates/faculty_import_ready_sample.csv',
    'staff_type' => 1,
    'default_password' => 'Nrcm@123',
    'dry_run' => false,
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
    if (strpos($arg, '--staff-type=') === 0) {
        $options['staff_type'] = (int)substr($arg, 13);
        continue;
    }
    if (strpos($arg, '--default-password=') === 0) {
        $options['default_password'] = (string)substr($arg, 19);
        continue;
    }
}

$csvFile = $options['file'];
if (!is_file($csvFile) || !is_readable($csvFile)) {
    fwrite(STDERR, "CSV file not found or not readable: {$csvFile}\n");
    exit(1);
}

$fcObj = new DataFunctions();
$db = new DataBasePDO();

$categoryRows = $db->getAllPrepared(
    "SELECT id FROM `" . TB_STAFF_CATEGORY . "` WHERE id = :id LIMIT 1",
    array(':id' => (int)$options['staff_type'])
);
if (empty($categoryRows)) {
    fwrite(STDERR, "Faculty category not found for staff_type {$options['staff_type']}.\n");
    exit(1);
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

$requiredColumns = array('first_name', 'last_name', 'qualification', 'designation', 'email');
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

    $firstName = $read('first_name');
    $lastName = $read('last_name');
    $qualification = $read('qualification');
    $designation = $read('designation');
    $email = strtolower($read('email'));

    if ($firstName === '' && $lastName === '' && $qualification === '' && $designation === '' && $email === '') {
        $skipped++;
        continue;
    }

    if ($firstName === '' || $qualification === '' || $designation === '' || $email === '') {
        echo "[line {$lineNumber}] skipped: required values missing.\n";
        $failed++;
        continue;
    }

    $record = array(
        'staffType' => (int)$options['staff_type'],
        'firstName' => $firstName,
        'lastName' => $lastName,
        'staffQualif' => str_replace(',', '\\,', $qualification),
        'staffDesig' => $designation,
        'email' => $email,
        'indusExp' => $read('industry_exp'),
        'teachingExp' => $read('teaching_exp'),
        'research' => $read('research'),
        'pub_nat' => $read('publ_national'),
        'pub_internat' => $read('publ_international'),
        'conf_nat' => $read('conf_national'),
        'conf_internat' => $read('conf_international'),
        'image' => '',
        'password' => $fcObj->hashPassword($options['default_password']),
    );

    if ($options['dry_run']) {
        echo "[line {$lineNumber}] ready: {$firstName} {$lastName} <{$email}>\n";
        $inserted++;
        continue;
    }

    $result = $fcObj->addStaffDetails(TB_STAFF, $record);
    if ($result === true || $result === 1) {
        echo "[line {$lineNumber}] inserted: {$firstName} {$lastName}\n";
        $inserted++;
        continue;
    }

    echo "[line {$lineNumber}] failed: {$firstName} {$lastName}\n";
    $failed++;
}

fclose($handle);

echo "\nSummary\n";
echo "file: {$csvFile}\n";
echo "staff_type: {$options['staff_type']}\n";
echo "inserted_or_ready: {$inserted}\n";
echo "blank_rows_skipped: {$skipped}\n";
echo "failed: {$failed}\n";
