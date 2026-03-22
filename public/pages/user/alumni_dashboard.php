<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
    exit;
}

if ((int)($_SESSION['is_alumni'] ?? 0) !== 1) {
    header('Location: ' . BASE_URL . '/public/pages/user/dashboard.php');
    exit;
}

$userActivePage = 'dashboard';
include_once(__DIR__ . '/layout/main_header.php');
?>

<div class="user-page-content">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h4 class="mb-2">Alumni Dashboard</h4>
            <p class="text-muted mb-0">
                You are logged in as an alumni user. Certificates and alumni-specific features can be added here later.
            </p>
        </div>
    </div>
</div>

<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>

