<?php
session_start();
require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/security.php');

/* Decide redirect before destroying session */
$redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
$requestedRole = trim((string)($_GET['role'] ?? ''));
$currentRole = trim((string)($_SESSION['role'] ?? ''));

if ($currentRole === 'admin' || $requestedRole === 'admin') {
    $redirectPage = BASE_URL . "/admin/index.php";
} elseif (
    $currentRole === 'faculty' ||
    $requestedRole === 'faculty' ||
    $currentRole === 'user' ||
    $requestedRole === 'user'
) {
    $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
}

/* Unset all session variables */
app_destroy_session_securely();

/* Redirect */
header("Location: " . $redirectPage);
exit;
?>
