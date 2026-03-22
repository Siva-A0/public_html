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
$tbStream = TB_STREAM;

if (isset($_GET['branch'])) {
    $branchId = (int)$_GET['branch'];
    if ($branchId > 0) {
        $fcObj->deleteBranch($tbStream, $branchId);
    }
}

header('Location: ' . BASE_URL . '/admin/branch/branch.php');
exit;
?>
