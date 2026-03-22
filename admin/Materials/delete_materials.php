<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbMaterial = TB_MATERAILS;
$tbSubject  = TB_SUBJECTS;

$materialId = isset($_GET['material']) ? (int)$_GET['material'] : 0;
if ($materialId <= 0) {
	header('Location: materials.php');
	exit;
}

$materialDet = $fcObj->getMaterialById($tbMaterial, $materialId);
if (empty($materialDet)) {
	header('Location: materials.php');
	exit;
}

$matFileName = (string)($materialDet[0]['mater_file'] ?? '');
$subjectId = (int)($materialDet[0]['subject_id'] ?? 0);
$subjectDet = $subjectId > 0 ? $fcObj->getSubjectById($tbSubject, $subjectId) : array();
$batchId = !empty($subjectDet) ? (int)($subjectDet[0]['batch_id'] ?? 0) : 0;

$deleted = $fcObj->deleteMaterial($tbMaterial, $materialId);
if ($deleted && $matFileName !== '') {
	$filePath = ROOT_PATH . '/public/uploads/materials/' . $matFileName;
	if (is_file($filePath)) {
		@unlink($filePath);
	}
}

$redirect = 'materials.php' . ($batchId > 0 ? ('?batchId=' . $batchId) : '');
header('Location: ' . $redirect);
exit;
