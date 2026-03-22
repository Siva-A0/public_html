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

/* ---------- LOGIN LOGIC ---------- */
if (isset($_POST['username']) || isset($_POST['login_input'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $_SESSION['err_msg'] = 'Your session expired. Please try again.';
        header("Location: " . BASE_URL . "/public/pages/Authentication/login.php");
        exit;
    }

    $uName = trim((string)($_POST['login_input'] ?? $_POST['username'] ?? ''));
    $pass  = trim($_POST['password']);
    $type  = trim((string)($_POST['login_type'] ?? ''));

    if ($uName === '') {
        $_SESSION['err_msg'] = 'Enter admin username, faculty email, or student/alumni roll number.';
        header("Location: " . BASE_URL . "/public/pages/Authentication/login.php");
        exit;
    }

    if ($type === '') {
        $tbAdmin = ADMIN_TABLE;
        $adminDet = $fcObj->getAdminByLogin($tbAdmin, $uName);

        if (!empty($adminDet) && $fcObj->verifyPassword($pass, (string)$adminDet[0]['password'])) {
            session_regenerate_id(true);
            $_SESSION['role']           = "admin";
            $_SESSION['account_role']   = "admin";
            $_SESSION['adminId']        = $adminDet[0]['id'];
            $_SESSION['adminName']      = $adminDet[0]['adminname'];
            $_SESSION['adminFirstName'] = $adminDet[0]['firstname'];
            $_SESSION['adminImage']     = $adminDet[0]['image'] ?? '';
            $_SESSION['image']          = $adminDet[0]['image'] ?? '';

            if ($fcObj->passwordNeedsRehash($adminDet[0]['password'])) {
                $fcObj->changeAdminPassWord($tbAdmin, array(
                    'admin_name' => $adminDet[0]['adminname'],
                    'pass_word' => $fcObj->hashPassword($pass)
                ));
            }

            $welcomeName = trim((string)($adminDet[0]['firstname'] ?? $uName));
            set_auth_success_preloader($welcomeName !== '' ? ('Welcome ' . $welcomeName) : 'Welcome');
            header("Location: " . BASE_URL . "/admin/main_home.php");
            exit;
        }

        $type = 'user';
    }

    /* ---------- USER / ALUMNI / FACULTY LOGIN ---------- */
    if ($type == "user") {
        $tbUser  = TB_USERS;
        $userDet = $fcObj->getUserByLogin($tbUser, $uName);

        if (!empty($userDet) && $fcObj->verifyPassword($pass, $userDet[0]['password'])) {
            $userStatus = (int)($userDet[0]['status'] ?? 0);
            if ($userStatus !== 1) {
                if ($userStatus === 0) {
                    $_SESSION['err_msg'] = 'Your account is pending admin approval.';
                } elseif ($userStatus === 2) {
                    $_SESSION['err_msg'] = 'Your account access is disabled by admin. Please contact the department.';
                } else {
                    $_SESSION['err_msg'] = 'Your account is not active. Please contact the department.';
                }
                header("Location: " . BASE_URL . "/public/pages/Authentication/login.php");
                exit;
            }

            session_regenerate_id(true);
            $_SESSION['role']      = "user";
            $_SESSION['userId']    = $userDet[0]['id'];
            $_SESSION['userName']  = $userDet[0]['username'] ?? $uName;
            $_SESSION['firstName'] = $userDet[0]['firstname'];
            $_SESSION['image']     = $userDet[0]['image'];
            $_SESSION['account_role'] = (string)($userDet[0]['role'] ?? 'student');
            $_SESSION['is_alumni'] = ((int)($userDet[0]['is_alumni'] ?? 0) === 1 || (string)($userDet[0]['user_type'] ?? 'student') === 'alumni') ? 1 : 0;

            if ($_SESSION['account_role'] === 'admin') {
                $_SESSION['role'] = 'admin';
                $_SESSION['adminId'] = $userDet[0]['id'];
                $_SESSION['adminName'] = $userDet[0]['mail_id'] ?? $uName;
                $_SESSION['adminFirstName'] = $userDet[0]['firstname'] ?? 'Admin';
                $_SESSION['adminImage'] = $userDet[0]['image'] ?? '';
            }

            if ($fcObj->passwordNeedsRehash($userDet[0]['password'])) {
                $fcObj->adminUpdateUserPasswordById($tbUser, (int)$userDet[0]['id'], $fcObj->hashPassword($pass));
            }

            $welcomeName = trim((string)($userDet[0]['firstname'] ?? $uName));
            set_auth_success_preloader($welcomeName !== '' ? ('Welcome ' . $welcomeName) : 'Welcome');
            if ($_SESSION['role'] === 'admin') {
                header("Location: " . BASE_URL . "/admin/main_home.php");
            } elseif ((int)$_SESSION['is_alumni'] === 1) {
                header("Location: " . BASE_URL . "/public/pages/user/alumni_dashboard.php");
            } else {
                header("Location: " . BASE_URL . "/public/pages/user/dashboard.php");
            }
            exit;
        }

        $facultyDet = $fcObj->facultyLogin(TB_STAFF, $uName);
        if (!empty($facultyDet) && $fcObj->verifyPassword($pass, (string)($facultyDet[0]['password'] ?? ''))) {
            session_regenerate_id(true);
            $_SESSION['role'] = 'faculty';
            $_SESSION['facultyId'] = (int)$facultyDet[0]['id'];
            $_SESSION['facultyEmail'] = (string)($facultyDet[0]['e_mail'] ?? $uName);
            $_SESSION['facultyFirstName'] = (string)($facultyDet[0]['first_name'] ?? '');
            $_SESSION['facultyName'] = trim((string)($facultyDet[0]['first_name'] ?? '') . ' ' . (string)($facultyDet[0]['last_name'] ?? ''));
            $_SESSION['facultyImage'] = (string)($facultyDet[0]['image'] ?? '');

            if ($fcObj->passwordNeedsRehash((string)($facultyDet[0]['password'] ?? ''))) {
                $fcObj->updateStaffPassword(TB_STAFF, (int)$facultyDet[0]['id'], $fcObj->hashPassword($pass));
            }

            $welcomeName = trim((string)($facultyDet[0]['first_name'] ?? $uName));
            set_auth_success_preloader($welcomeName !== '' ? ('Welcome ' . $welcomeName) : 'Welcome');
            header("Location: " . BASE_URL . "/public/pages/faculty/dashboard.php");
            exit;
        }

        $_SESSION['err_msg'] = 'Invalid credentials';
        header("Location: " . BASE_URL . "/public/pages/Authentication/login.php");
        exit;
    }

    /* ---------- ADMIN LOGIN ---------- */
    if ($type == "admin") {

        $tbAdmin  = "admin";
        $adminDet = $fcObj->adminLogin($tbAdmin, $uName);

        if (
            empty($adminDet) ||
            !$fcObj->verifyPassword($pass, $adminDet[0]['password'])
        ) {
            $_SESSION['err_msg'] = 'Invalid Admin Credentials';
            header("Location: " . BASE_URL . "/public/pages/Authentication/login.php");
            exit;
        }

        session_regenerate_id(true);
        $_SESSION['role']           = "admin";
        $_SESSION['adminId']        = $adminDet[0]['id'];
        $_SESSION['adminName']      = $adminDet[0]['adminname'];
        $_SESSION['adminFirstName'] = $adminDet[0]['firstname'];
        $_SESSION['image']          = $adminDet[0]['image'];

        if ($fcObj->passwordNeedsRehash($adminDet[0]['password'])) {
            $fcObj->changeAdminPassWord($tbAdmin, array(
                'admin_name' => $uName,
                'pass_word' => $fcObj->hashPassword($pass)
            ));
        }

        $welcomeName = trim((string)($adminDet[0]['firstname'] ?? $uName));
        set_auth_success_preloader($welcomeName !== '' ? ('Welcome ' . $welcomeName) : 'Welcome');
        header("Location: " . BASE_URL . "/admin/main_home.php");
        exit;
    }
}

/* Redirect if already logged in */
if (isset($_SESSION['role'])) {

    $accountRole = (string)($_SESSION['account_role'] ?? $_SESSION['role']);
    if ($accountRole == "admin") {
        header("Location: " . BASE_URL . "/admin/main_home.php");
    } elseif ($_SESSION['role'] == "faculty") {
        header("Location: " . BASE_URL . "/public/pages/faculty/dashboard.php");
    } elseif ((int)($_SESSION['is_alumni'] ?? 0) === 1) {
        header("Location: " . BASE_URL . "/public/pages/user/alumni_dashboard.php");
    } else {
        header("Location: " . BASE_URL . "/public/pages/user/dashboard.php");
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login | AIML Department</title>
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
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/register.php" class="switch-link">Sign up</a>
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/login.php" class="switch-link active" aria-current="page">Login</a>
            </div>
        </header>

        <main class="auth-panel">
            <div class="auth-back-row">
                <a class="btn btn-sm btn-outline-secondary auth-back-btn" href="<?php echo BASE_URL; ?>/">
                    &larr; Back
                </a>
            </div>
            <div class="auth-copy">
                <h2 class="auth-title">Log in to your account</h2>
                <p class="auth-subtitle">Admin uses username, faculty uses email, and students/alumni use roll number.</p>
            </div>

            <?php if (isset($_SESSION['success_msg'])) { ?>
                <div class="alert alert-success">
                    <?php
                        echo $_SESSION['success_msg'];
                        unset($_SESSION['success_msg']);
                    ?>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['err_msg'])) { ?>
                <div class="alert alert-danger">
                    <?php
                        echo $_SESSION['err_msg'];
                        unset($_SESSION['err_msg']);
                    ?>
                </div>
            <?php } ?>

            <form method="POST" action="login.php" class="auth-form" id="loginForm">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="field-group">
                    <input type="text" name="login_input" id="login_input" class="form-control auth-input" autocomplete="username" placeholder="Admin Username / Faculty Email / Student Roll No" required>
                </div>

                <div class="field-group">
                    <div class="password-wrap">
                        <input type="password" name="password" id="password" class="form-control auth-input" autocomplete="current-password" placeholder="Password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">Show</button>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/forgot_password.php" style="font-size:13px; font-weight:600; text-decoration:none;">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <div class="role-actions">
                    <button type="submit" class="btn auth-btn primary-btn w-100">
                        Login
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
