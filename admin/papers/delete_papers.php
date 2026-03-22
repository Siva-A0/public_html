<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbPrevPapers = TB_PREV_PAPERS;
$tbSubject    = TB_SUBJECTS;

$paperId = isset($_GET['paper']) ? (int)$_GET['paper'] : 0;
if ($paperId <= 0) {
	header('Location: previouspapers.php');
	exit;
}

$paperDet = $fcObj->getPaperById($tbPrevPapers, $paperId);
if (empty($paperDet)) {
	header('Location: previouspapers.php');
	exit;
}

$paperFileName = (string)($paperDet[0]['paper_file'] ?? '');
$subjectId = (int)($paperDet[0]['subject_id'] ?? 0);
$subjectDet = $subjectId > 0 ? $fcObj->getSubjectById($tbSubject, $subjectId) : array();
$batchId = !empty($subjectDet) ? (int)($subjectDet[0]['batch_id'] ?? 0) : 0;

$deleted = $fcObj->deletePaper($tbPrevPapers, $paperId);
if ($deleted && $paperFileName !== '') {
	$filePath = ROOT_PATH . '/public/uploads/previous_papers/' . $paperFileName;
	if (is_file($filePath)) {
		@unlink($filePath);
	}
}

$redirect = 'previouspapers.php' . ($batchId > 0 ? ('?batchId=' . $batchId) : '');
header('Location: ' . $redirect);
exit;
