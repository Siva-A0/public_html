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
$tbBatch = TB_BATCH;

if (isset($_GET['batch'])) {
    $batchId = (int)$_GET['batch'];
    if ($batchId > 0) {
        $fcObj->deleteBatch($tbBatch, $batchId);
    }
}

header('Location: ' . BASE_URL . '/admin/batches/batch.php');
exit;
?>
