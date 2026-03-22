<?php
session_start();
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();
$tbUsers = TB_USERS;

if (!isset($_SESSION['adminId'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
    $_SESSION['admin_users_flash_msg'] = 'Your session expired. Please try again.';
    $_SESSION['admin_users_flash_type'] = 'danger';
    header("Location: users.php");
    exit;
}

if (!isset($_POST['users']) || !is_array($_POST['users'])) {
    header("Location: users.php");
    exit;
}

$users = $_POST['users'];
$noOfUsers = sizeof($users);

for ($i=0; $i<$noOfUsers; $i++) {
    $userId = (int)$users[$i];
    if ($userId <= 0) {
        continue;
    }

    if (isset($_POST['approveusers'])) {
        $fcObj->adminUpdateUserStatus($tbUsers, $userId, 1);
    }

    if (isset($_POST['deleteusers'])) {
        $fcObj->adminDeleteUserById($tbUsers, $userId);
    }
}

header("Location: users.php");
exit;
?>
