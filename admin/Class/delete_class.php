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
$tbClass = TB_CLASS;

$status = 'invalid';

if (isset($_GET['class'])) {
    $clsId = (int)$_GET['class'];
    if ($clsId >= 0) {
        $deleted = $fcObj->deleteClass($tbClass, $clsId);
        if ($deleted === false) {
            $status = 'error';
        } elseif ((int)$deleted > 0) {
            $status = 'success';
        } else {
            $status = 'notfound';
        }
    }
}

header('Location: ' . BASE_URL . '/admin/Class/classes.php?delete=' . urlencode($status));
exit;
?>
