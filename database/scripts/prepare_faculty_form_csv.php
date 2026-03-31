<?php
if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This script must be run from the command line.\n");
    exit(1);
}

require_once __DIR__ . '/../../config.php';

$defaults = array(
    'input' => ROOT_PATH . '/Faculty Data  (Responses) - Form responses 1.csv',
    'output' => ROOT_PATH . '/database/faculty_import_prepared.csv',
);

$options = $defaults;

foreach (array_slice($argv, 1) as $arg) {
    if (strpos($arg, '--input=') === 0) {
        $options['input'] = substr($arg, 8);
        continue;
    }
    if (strpos($arg, '--output=') === 0) {
        $options['output'] = substr($arg, 9);
        continue;
    }
}

$inputFile = $options['input'];
$outputFile = $options['output'];

if (!is_file($inputFile) || !is_readable($inputFile)) {
    fwrite(STDERR, "Input CSV not found or not readable: {$inputFile}\n");
    exit(1);
}

$inputHandle = fopen($inputFile, 'r');
if ($inputHandle === false) {
    fwrite(STDERR, "Unable to open input CSV: {$inputFile}\n");
    exit(1);
}

$header = fgetcsv($inputHandle, 0, ',', '"', '\\');
if ($header === false) {
    fclose($inputHandle);
    fwrite(STDERR, "Input CSV is empty: {$inputFile}\n");
    exit(1);
}

$headerMap = array();
foreach ($header as $index => $column) {
    $headerMap[strtolower(trim((string)$column))] = $index;
}

$requiredColumns = array(
    'timestamp',
    'first name',
    'last name',
    'qualification',
    'designation',
    'email',
    'teaching experience',
    'research',
    'national publications',
    'international publications',
    'international conference',
    'national conference',
);

foreach ($requiredColumns as $column) {
    if (!array_key_exists($column, $headerMap)) {
        fclose($inputHandle);
        fwrite(STDERR, "Missing required column in input CSV: {$column}\n");
        exit(1);
    }
}

$readColumn = function (array $row, string $name) use ($headerMap) {
    $index = $headerMap[$name];
    return isset($row[$index]) ? trim((string)$row[$index]) : '';
};

$normalizeText = function (string $value) {
    $value = trim($value);
    $value = preg_replace('/\s+/', ' ', $value);
    return $value === null ? '' : $value;
};

$normalizeEmail = function (string $value) use ($normalizeText) {
    $value = strtolower($normalizeText($value));
    return str_replace(' ', '', $value);
};

$normalizeNumericish = function (string $value) use ($normalizeText) {
    $value = $normalizeText($value);
    if ($value === '') {
        return '';
    }

    $lower = strtolower($value);
    if ($lower === '0') {
        return '0';
    }

    if (preg_match('/^\d+$/', $value)) {
        return $value;
    }

    return $value;
};

$rowsByEmail = array();
$rawRows = 0;
$ignoredRows = 0;

while (($row = fgetcsv($inputHandle, 0, ',', '"', '\\')) !== false) {
    $rawRows++;

    $email = $normalizeEmail($readColumn($row, 'email'));
    $firstName = $normalizeText($readColumn($row, 'first name'));
    $lastName = $normalizeText($readColumn($row, 'last name'));
    $qualification = $normalizeText($readColumn($row, 'qualification'));
    $designation = $normalizeText($readColumn($row, 'designation'));

    if ($email === '' && $firstName === '' && $lastName === '' && $qualification === '' && $designation === '') {
        $ignoredRows++;
        continue;
    }

    if ($email === '') {
        $ignoredRows++;
        continue;
    }

    $prepared = array(
        'timestamp' => $normalizeText($readColumn($row, 'timestamp')),
        'first_name' => $firstName,
        'last_name' => $lastName,
        'qualification' => $qualification,
        'designation' => $designation,
        'email' => $email,
        'industry_exp' => '',
        'teaching_exp' => $normalizeNumericish($readColumn($row, 'teaching experience')),
        'research' => $normalizeText($readColumn($row, 'research')),
        'publ_national' => $normalizeText($readColumn($row, 'national publications')),
        'publ_international' => $normalizeText($readColumn($row, 'international publications')),
        'conf_national' => $normalizeText($readColumn($row, 'national conference')),
        'conf_international' => $normalizeText($readColumn($row, 'international conference')),
    );

    $rowsByEmail[$email] = $prepared;
}

fclose($inputHandle);

$outputHandle = fopen($outputFile, 'w');
if ($outputHandle === false) {
    fwrite(STDERR, "Unable to open output CSV for writing: {$outputFile}\n");
    exit(1);
}

$outputHeader = array(
    'first_name',
    'last_name',
    'qualification',
    'designation',
    'email',
    'industry_exp',
    'teaching_exp',
    'research',
    'publ_national',
    'publ_international',
    'conf_national',
    'conf_international',
);

fputcsv($outputHandle, $outputHeader);

ksort($rowsByEmail);
foreach ($rowsByEmail as $prepared) {
    fputcsv(
        $outputHandle,
        array(
            $prepared['first_name'],
            $prepared['last_name'],
            $prepared['qualification'],
            $prepared['designation'],
            $prepared['email'],
            $prepared['industry_exp'],
            $prepared['teaching_exp'],
            $prepared['research'],
            $prepared['publ_national'],
            $prepared['publ_international'],
            $prepared['conf_national'],
            $prepared['conf_international'],
        )
    );
}

fclose($outputHandle);

echo "Prepared faculty import CSV created.\n";
echo "input: {$inputFile}\n";
echo "output: {$outputFile}\n";
echo "raw_rows: {$rawRows}\n";
echo "ignored_rows: {$ignoredRows}\n";
echo "deduplicated_rows: " . count($rowsByEmail) . "\n";
