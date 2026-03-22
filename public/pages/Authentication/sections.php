<?php
require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');

header('Content-Type: application/json; charset=utf-8');

$batchId = isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
$classId = isset($_GET['classId']) ? (int)$_GET['classId'] : 0;
$year = isset($_GET['year']) ? (int)$_GET['year'] : 0;

if ($batchId <= 0 || ($classId <= 0 && $year <= 0)) {
    echo json_encode(array('ok' => false, 'sections' => array()));
    exit;
}

$fcObj = new DataFunctions();
if ($classId <= 0) {
    $classId = $fcObj->getDefaultClassIdForYear(TB_CLASS, $year);
}
if ($classId <= 0) {
    echo json_encode(array('ok' => false, 'sections' => array()));
    exit;
}

$sections = $fcObj->getSections(TB_SECTION, $classId, $batchId);

$out = array();
foreach ($sections as $sec) {
    $out[] = array(
        'id' => (int)($sec['id'] ?? 0),
        'code' => (string)($sec['section_code'] ?? ''),
        'name' => (string)($sec['section_name'] ?? '')
    );
}

echo json_encode(array('ok' => true, 'sections' => $out));
