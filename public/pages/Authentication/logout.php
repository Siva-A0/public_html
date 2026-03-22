<?php
session_start();
require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/security.php');

/* Decide redirect before destroying session */
$redirectPage = BASE_URL . "/public/pages/Authentication/login.php";

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == "admin") {
        $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
    } elseif ($_SESSION['role'] == "faculty") {
        $redirectPage = BASE_URL . "/public/pages/Authentication/faculty_login.php";
    } else {
        $redirectPage = BASE_URL . "/public/pages/Authentication/login.php";
    }
}

/* Unset all session variables */
app_destroy_session_securely();

/* Redirect */
header("Location: " . $redirectPage);
exit;
?>
