<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');
require_once(LIB_PATH . '/smtp_mailer.php');

$fcObj = new DataFunctions();

// Logged-in users don't need reset links.
if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    header('Location: ' . BASE_URL . '/public/pages/user/dashboard.php');
    exit;
}
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$message = '';
$messageType = 'success';

function app_get_site_origin(){
    $configured = trim((string)(defined('BASE_PATH') ? BASE_PATH : ''));
    if ($configured !== '') {
        return rtrim($configured, '/');
    }

    $host = trim((string)($_SERVER['HTTP_HOST'] ?? ''));
    if ($host === '') {
        return '';
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && strtolower((string)$_SERVER['HTTPS']) !== 'off') || ((string)($_SERVER['SERVER_PORT'] ?? '') === '443');
    $scheme = $isHttps ? 'https' : 'http';
    return $scheme . '://' . $host;
}

if (isset($_POST['submit'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please try again.';
        $messageType = 'danger';
    } else {
        $identifier = trim((string)($_POST['identifier'] ?? ''));

        // Generic response to avoid account enumeration.
        $message = 'If an account matches your details, a reset link has been sent to the registered email address. If you do not receive it in a few minutes, contact the department admin.';
        $messageType = 'success';

        if ($identifier !== '') {
            $userRows = $fcObj->findUserByLoginIdentifier(TB_USERS, $identifier);
            if (!empty($userRows)) {
                $user = $userRows[0];
                $userId = (int)($user['id'] ?? 0);
                $toEmail = trim((string)($user['mail_id'] ?? ''));

                if ($userId > 0 && $toEmail !== '' && filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
                    $supportSettings = $fcObj->getSupportSettings(TB_SUPPORT_SETTINGS);
                    $smtpConfigured = (
                        trim((string)($supportSettings['smtp_host'] ?? '')) !== '' &&
                        trim((string)($supportSettings['smtp_username'] ?? '')) !== '' &&
                        trim((string)($supportSettings['smtp_password'] ?? '')) !== '' &&
                        trim((string)($supportSettings['smtp_from_email'] ?? '')) !== ''
                    );

                    if ($smtpConfigured) {
                        $token = bin2hex(random_bytes(32));
                        $tokenHash = hash('sha256', $token);
                        $expiresAtUtc = gmdate('Y-m-d H:i:s', time() + 3600);

                        $created = $fcObj->createPasswordReset(
                            TB_PASSWORD_RESETS,
                            $userId,
                            $tokenHash,
                            $expiresAtUtc,
                            (string)($_SERVER['REMOTE_ADDR'] ?? ''),
                            (string)($_SERVER['HTTP_USER_AGENT'] ?? '')
                        );

                        if ($created !== false) {
                            $origin = app_get_site_origin();
                            $base = rtrim((string)BASE_URL, '/');
                            $path = $base . '/public/pages/Authentication/reset_password.php?token=' . rawurlencode($token);
                            $resetUrl = $origin !== '' ? ($origin . $path) : $path;
                            $subject = 'AIML Portal: Password Reset';
                            $body = "We received a request to reset your password.\n\n";
                            $body .= "Reset link (valid for 1 hour):\n" . $resetUrl . "\n\n";
                            $body .= "If you did not request this, you can ignore this email.\n";

                            $err = '';
                            app_send_mail_via_smtp($toEmail, $subject, $body, '', $supportSettings, $err);
                            // Even if send fails, keep generic message. Admin can reset manually.
                        }
                    }
                }
            }
        }

        app_rotate_csrf_token();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | AIML Department</title>
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
                <h2 class="auth-title">Reset your password</h2>
                <p class="auth-subtitle">Enter your email, username, or roll number. We will email a reset link if it matches an account.</p>
            </div>

            <?php if ($message !== '') { ?>
                <div class="alert alert-<?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>

            <form method="POST" class="auth-form" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="field-group">
                    <input
                        type="text"
                        name="identifier"
                        class="form-control auth-input"
                        placeholder="Email / Username / Roll No"
                        autocomplete="username"
                        required
                    >
                </div>
                <button type="submit" name="submit" class="btn auth-btn primary-btn">
                    Send Reset Link
                </button>
            </form>
        </main>
    </div>
</div>
</body>
</html>
