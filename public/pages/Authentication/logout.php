<?php
session_start();
require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/security.php');

/* Decide redirect before destroying session */
$redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
$requestedRole = trim((string)($_GET['role'] ?? ''));

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == "admin") {
        $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
    } elseif ($_SESSION['role'] == "faculty") {
        $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
    } else {
        $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
    }
} elseif ($requestedRole === 'faculty') {
    $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
} elseif ($requestedRole === 'admin') {
    $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
} elseif ($requestedRole === 'user') {
    $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
}

/* Unset all session variables */
app_destroy_session_securely();

/* Redirect */
header("Location: " . $redirectPage);
exit;
?>
