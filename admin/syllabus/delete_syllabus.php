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
$tbSyllabus = TB_SYLLABUS;

if (isset($_GET['syllabus'])) {
    $sylId = (int)$_GET['syllabus'];
    if ($sylId > 0) {
        $syllabusDet = $fcObj->getSyllabusById($tbSyllabus, $sylId);
        $fcObj->deleteSyllabus($tbSyllabus, $sylId);

        if (!empty($syllabusDet)) {
            $sylName = trim((string)$syllabusDet[0]['syllabus_name']);
            $sylPath = ROOT_PATH . '/public/uploads/syllabus/' . $sylName;
            if ($sylName !== '' && file_exists($sylPath)) {
                @unlink($sylPath);
            }
        }
    }
}

header('Location: ' . BASE_URL . '/admin/syllabus/syllabus.php');
exit;
?>
