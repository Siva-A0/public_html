<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['SESS_MEMBER_ID'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$fcObj = new DataFunctions();
$tbSubject = TB_SUBJECTS;

if (isset($_GET['subject'])) {
    $subId = (int)$_GET['subject'];
    if ($subId > 0) {
        $fcObj->deleteSubject($tbSubject, $subId);
    }
}

header('Location: ' . BASE_URL . '/admin/Subject/subjects.php');
exit;
?>
