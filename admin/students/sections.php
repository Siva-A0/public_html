<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['adminId'])) {
    http_response_code(403);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('ok' => false, 'error' => 'forbidden'));
    exit;
}

$batchId = isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
$classId = isset($_GET['classId']) ? (int)$_GET['classId'] : 0;

header('Content-Type: application/json; charset=utf-8');

if ($batchId <= 0 || $classId <= 0) {
    echo json_encode(array('ok' => true, 'sections' => array()));
    exit;
}

$fcObj = new DataFunctions();
$sections = $fcObj->getSections(TB_SECTION, $classId, $batchId);

$out = array();
foreach ($sections as $s) {
    $out[] = array(
        'id' => (int)$s['id'],
        'code' => (string)($s['section_code'] ?? ''),
        'name' => (string)($s['section_name'] ?? '')
    );
}

echo json_encode(array('ok' => true, 'sections' => $out));

