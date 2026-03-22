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
$tbEvents = TB_EVENTS;

if (isset($_GET['event'])) {
    $eventId = (int)$_GET['event'];
    if ($eventId > 0) {
        $fcObj->deleteEvent($tbEvents, $eventId);
    }
}

header('Location: ' . BASE_URL . '/admin/events/view_events.php');
exit;
?>
