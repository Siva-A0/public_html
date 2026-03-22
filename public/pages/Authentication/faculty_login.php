<?php
if (session_id() == '') {
    session_start();
}
require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

if (!function_exists('set_auth_success_preloader')) {
    function set_auth_success_preloader($message)
    {
        $_SESSION['site_preloader_once'] = array(
            'message' => (string)$message
        );
    }
}

if (isset($_POST['faculty_login'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['err_msg'] = 'Your session expired. Please try again.';
        header("Location: " . BASE_URL . "/public/pages/Authentication/faculty_login.php");
        exit;
    }

    $email = trim((string)($_POST['email'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $facultyDet = $fcObj->facultyLogin(TB_STAFF, $email);

    if (empty($facultyDet) || !$fcObj->verifyPassword($password, (string)($facultyDet[0]['password'] ?? ''))) {
        $_SESSION['err_msg'] = 'Invalid faculty credentials';
        header("Location: " . BASE_URL . "/public/pages/Authentication/faculty_login.php");
        exit;
    }

    session_regenerate_id(true);
    $_SESSION['role'] = 'faculty';
    $_SESSION['facultyId'] = (int)$facultyDet[0]['id'];
    $_SESSION['facultyEmail'] = (string)$facultyDet[0]['e_mail'];
    $_SESSION['facultyFirstName'] = (string)$facultyDet[0]['first_name'];
    $_SESSION['facultyName'] = trim((string)$facultyDet[0]['first_name'] . ' ' . (string)$facultyDet[0]['last_name']);
    $_SESSION['facultyImage'] = (string)$facultyDet[0]['image'];

    if ($fcObj->passwordNeedsRehash((string)$facultyDet[0]['password'])) {
        $fcObj->updateStaffPassword(TB_STAFF, (int)$facultyDet[0]['id'], $fcObj->hashPassword($password));
    }

    $welcomeName = trim((string)($facultyDet[0]['first_name'] ?? $email));
    set_auth_success_preloader($welcomeName !== '' ? ('Welcome ' . $welcomeName) : 'Welcome');
    header("Location: " . BASE_URL . "/public/pages/faculty/dashboard.php");
    exit;
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'faculty') {
        header("Location: " . BASE_URL . "/public/pages/faculty/dashboard.php");
        exit;
    }
    if ($_SESSION['role'] === 'user') {
        header("Location: " . BASE_URL . "/public/pages/user/dashboard.php");
        exit;
    }
    if ($_SESSION['role'] === 'admin') {
        header("Location: " . BASE_URL . "/admin/index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Faculty Login | AIML Department</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/login.css">

</head>
<body>

<div class="auth-shell">
    <div class="auth-frame">
        <header class="auth-header">
            <div class="brand-mark">AIML Department</div>
            <div class="auth-switch" role="tablist" aria-label="Authentication pages">
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/login.php" class="switch-link">Student Login</a>
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/faculty_login.php" class="switch-link active" aria-current="page">Faculty Login</a>
            </div>
        </header>

        <main class="auth-panel">
            <div class="auth-back-row">
                <a class="btn btn-sm btn-outline-secondary auth-back-btn" href="<?php echo BASE_URL; ?>/">
                    &larr; Back
                </a>
            </div>
            <div class="auth-copy">
                <h2 class="auth-title">Log in to your faculty profile</h2>
            </div>

            <?php if (isset($_SESSION['err_msg'])) { ?>
                <div class="alert alert-danger">
                    <?php
                        echo $_SESSION['err_msg'];
                        unset($_SESSION['err_msg']);
                    ?>
                </div>
            <?php } ?>

            <form method="POST" action="faculty_login.php" class="auth-form">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="field-group">
                    <input type="email" name="email" id="email" class="form-control auth-input" autocomplete="username" placeholder="Faculty Email" required>
                </div>

                <div class="field-group">
                    <div class="password-wrap">
                        <input type="password" name="password" id="password" class="form-control auth-input" autocomplete="current-password" placeholder="Password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">Show</button>
                    </div>
                </div>

                <div class="role-actions">
                    <button type="submit" name="faculty_login" value="1" class="btn auth-btn primary-btn">
                        Faculty Login
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

<script>
function togglePassword() {
    const pass = document.getElementById("password");
    const icon = document.querySelector(".toggle-password");

    if (pass.type === "password") {
        pass.type = "text";
        icon.textContent = "Hide";
    } else {
        pass.type = "password";
        icon.textContent = "Show";
    }
}

document.querySelectorAll(".switch-link").forEach((link) => {
    link.addEventListener("click", (event) => {
        const target = link.getAttribute("href");
        if (!target || target === window.location.href) {
            return;
        }

        event.preventDefault();

        const navigate = () => {
            window.location.href = target;
        };

        if (document.startViewTransition) {
            document.startViewTransition(() => {
                navigate();
            });
            return;
        }

        document.body.classList.add("is-switching");
        window.setTimeout(navigate, 220);
    });
});
</script>

</body>
</html>
