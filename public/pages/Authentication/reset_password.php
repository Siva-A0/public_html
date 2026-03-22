<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

// Logged-in users should use their profile/settings (or admin tools).
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    header('Location: ' . BASE_URL . '/public/pages/user/dashboard.php');
    exit;
}
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$token = trim((string)($_GET['token'] ?? $_POST['token'] ?? ''));
$tokenHash = $token !== '' ? hash('sha256', $token) : '';

$message = '';
$messageType = 'danger';
$canReset = false;
$resetRow = array();

if ($tokenHash !== '') {
    $rows = $fcObj->getValidPasswordResetByTokenHash(TB_PASSWORD_RESETS, $tokenHash);
    if (!empty($rows)) {
        $resetRow = $rows[0];
        $canReset = true;
    }
}

if (isset($_POST['submit'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please try again.';
        $messageType = 'danger';
    } elseif (!$canReset) {
        $message = 'This reset link is invalid or expired. Please request a new one.';
        $messageType = 'danger';
    } else {
        $password = (string)($_POST['password'] ?? '');
        $confirm = (string)($_POST['confirm_password'] ?? '');

        if (strlen($password) < 8) {
            $message = 'Password must be at least 8 characters long.';
            $messageType = 'danger';
        } elseif ($password !== $confirm) {
            $message = 'Passwords do not match.';
            $messageType = 'danger';
        } else {
            $userId = (int)($resetRow['user_id'] ?? 0);
            $resetId = (int)($resetRow['reset_id'] ?? 0);

            if ($userId <= 0 || $resetId <= 0) {
                $message = 'This reset link is invalid. Please request a new one.';
                $messageType = 'danger';
            } else {
                $updated = $fcObj->adminUpdateUserPasswordById(TB_USERS, $userId, $fcObj->hashPassword($password));
                if ($updated !== false) {
                    $fcObj->markPasswordResetUsed(TB_PASSWORD_RESETS, $resetId);
                    $_SESSION['success_msg'] = 'Password updated successfully. Please login.';
                    app_rotate_csrf_token();
                    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
                    exit;
                }

                $message = 'Unable to reset password. Please try again.';
                $messageType = 'danger';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password | AIML Department</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <?php
        $loginCssVersion = '';
        $loginCssFs = ROOT_PATH . '/public/assets/css/login.css';
        if (is_file($loginCssFs)) {
            $loginCssVersion = '?v=' . (string)@filemtime($loginCssFs);
        }
    ?>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/login.css<?php echo $loginCssVersion; ?>">
</head>

<body>
<div class="auth-shell">
    <div class="auth-frame">
        <header class="auth-header">
            <div class="brand-mark">AIML Department</div>
            <div class="auth-switch" aria-label="Authentication pages">
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/register.php" class="switch-link">Sign up</a>
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/login.php" class="switch-link">Login</a>
            </div>
        </header>

        <main class="auth-panel">
            <div class="auth-back-row">
                <a class="btn btn-sm btn-outline-secondary auth-back-btn" href="<?php echo BASE_URL; ?>/">
                    &larr; Home
                </a>
            </div>

            <div class="auth-copy">
                <h2 class="auth-title">Choose a new password</h2>
                <p class="auth-subtitle">This link is valid for 1 hour.</p>
            </div>

            <?php if (!$canReset && $message === '') { ?>
                <div class="alert alert-danger">
                    This reset link is invalid or expired. Please request a new one.
                </div>
            <?php } ?>

            <?php if ($message !== '') { ?>
                <div class="alert alert-<?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>

            <?php if ($canReset) { ?>
                <form method="POST" class="auth-form" action="">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">

                    <div class="field-group">
                        <input
                            type="password"
                            name="password"
                            class="form-control auth-input"
                            placeholder="New Password"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <div class="field-group">
                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control auth-input"
                            placeholder="Confirm New Password"
                            autocomplete="new-password"
                            required
                        >
                    </div>

                    <button type="submit" name="submit" class="btn auth-btn primary-btn">
                        Update Password
                    </button>
                </form>
            <?php } else { ?>
                <a class="btn auth-btn secondary-btn" href="<?php echo BASE_URL; ?>/public/pages/Authentication/forgot_password.php">Request a new link</a>
            <?php } ?>
        </main>
    </div>
</div>
</body>
</html>

