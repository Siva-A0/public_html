<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (session_id() == '') {
    session_start();
}

if (!isset($_SESSION['adminId'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$fcObj = new DataFunctions();
$tbSection = TB_SECTION;

if (isset($_GET['section'])) {
    $secId = (int)$_GET['section'];
    if ($secId > 0) {
        $fcObj->deleteSection($tbSection, $secId);
    }
}

header('Location: ' . BASE_URL . '/admin/Section/sections.php');
exit;
?>
