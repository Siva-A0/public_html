<?php
if (session_id() == '') {
    session_start();
}

if (!defined('BASE_URL')) {
    require_once(__DIR__ . '/../../config.php');
}

if (!isset($_SESSION['adminId'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

$currentAdminPath = $_SERVER['PHP_SELF'] ?? '';
$adminFirstName = trim((string)($_SESSION['adminFirstName'] ?? 'Admin User'));
$adminPreloaderFlash = isset($_SESSION['site_preloader_once']) && is_array($_SESSION['site_preloader_once'])
    ? $_SESSION['site_preloader_once']
    : null;
if ($adminPreloaderFlash !== null) {
    unset($_SESSION['site_preloader_once']);
}
$adminPreloaderMessage = trim((string)($adminPreloaderFlash['message'] ?? ''));
if ($adminPreloaderMessage === '') {
    $adminPreloaderMessage = $adminFirstName !== '' ? ('Welcome ' . $adminFirstName) : 'Welcome';
}
$navItems = array(
    array('href' => BASE_URL . '/admin/main_home.php', 'icon' => 'bi bi-grid', 'label' => 'Dashboard', 'match' => '/admin/main_home.php'),
    // array('href' => BASE_URL . '/admin/main_home.php#overview', 'icon' => 'bi bi-bar-chart', 'label' => 'Overview', 'match' => '/admin/__overview_anchor__'),
    array('href' => BASE_URL . '/admin/committe/assoc.php', 'icon' => 'bi bi-cpu', 'label' => 'Pragya AI', 'match' => '/admin/committe/assoc.php'),
    array('href' => BASE_URL . '/admin/Department/department.php', 'icon' => 'bi bi-building', 'label' => 'Department', 'match' => '/admin/Department/department.php'),
    array('href' => BASE_URL . '/admin/users/students.php', 'icon' => 'bi bi-people', 'label' => 'Students', 'match' => '/admin/users/students.php'),
    array('href' => BASE_URL . '/admin/users/alumni.php', 'icon' => 'bi bi-person-badge', 'label' => 'Alumni', 'match' => '/admin/users/alumni.php'),
    array('href' => BASE_URL . '/admin/gallery/gallery.php', 'icon' => 'bi bi-image', 'label' => 'Gallery', 'match' => '/admin/gallery/'),
    array('href' => BASE_URL . '/admin/settings/otheroperations.php', 'icon' => 'bi bi-gear', 'label' => 'Core Settings', 'match' => '/admin/settings/otheroperations.php')
);

$adminImageFile = basename($_SESSION['adminImage'] ?? '');
$defaultAdminImage = 'ithod.png';
$adminImageWebPath = BASE_URL . '/public/assets/images/admin/' . ($adminImageFile !== '' ? $adminImageFile : $defaultAdminImage);
$adminImageDiskPath = __DIR__ . '/../../public/assets/images/admin/' . ($adminImageFile !== '' ? $adminImageFile : $defaultAdminImage);

if (!file_exists($adminImageDiskPath)) {
    $adminImageWebPath = BASE_URL . '/public/assets/images/admin/' . $defaultAdminImage;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel | AIML Department</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/site-refresh.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/admin-refresh.css">
    <style>
        :root {
            --admin-sidebar-width: 246px;
            --admin-sidebar: #173d69;
            --admin-sidebar-deep: #13345a;
            --admin-accent: #f0b323;
            --admin-surface: #eef4fa;
            --admin-card: #ffffff;
            --admin-border: #d9e3ef;
            --admin-text: #163a61;
            --admin-muted: #88a0ba;
        }

        body {
            overflow-x: hidden;
            background: var(--admin-surface);
            color: var(--admin-text);
        }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--admin-sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--admin-sidebar) 0%, var(--admin-sidebar-deep) 100%);
            color: #fff;
            z-index: 1040;
            transition: transform 0.25s ease;
            display: flex;
            flex-direction: column;
            padding: 18px 12px;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 4px 8px 24px;
            color: #fff;
        }

        .sidebar-brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: #fff;
            color: var(--admin-sidebar);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
        }

        .sidebar-brand-title {
            font-size: 1.22rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .sidebar-brand-subtitle {
            margin-top: 2px;
            font-size: 0.72rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #8fb0d4;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            flex: 1 1 auto;
        }

        .sidebar-nav-bottom {
            margin-top: auto;
            padding-top: 10px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 6px;
            border-radius: 12px;
            border-left: 4px solid transparent;
            color: #d7e5f5;
            text-decoration: none;
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease, transform 0.2s ease;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            border-left-color: var(--admin-accent);
            transform: translateX(2px);
        }

        .sidebar a.text-danger {
            color: #fca5a5 !important;
        }

        .sidebar a.text-danger:hover {
            background: rgba(239, 68, 68, 0.18);
            border-left-color: #f87171;
            color: #fff !important;
        }

        .topbar {
            margin-left: var(--admin-sidebar-width);
            padding: 14px 24px;
            min-height: 82px;
            background: #fff;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            transition: margin-left 0.25s ease;
        }

        .content-area {
            margin-left: var(--admin-sidebar-width);
            padding: 24px;
            transition: margin-left 0.25s ease;
        }

        .topbar-primary,
        .topbar-actions {
            min-width: 0;
        }

        .topbar-title {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--admin-text);
            letter-spacing: 0.01em;
        }

        .topbar-title span {
            color: var(--admin-accent);
        }

        .sidebar-toggle {
            border: 1px solid #d5e0ee;
            background: #f6faff;
            color: #1a3556;
            border-radius: 10px;
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            padding: 0;
        }

        .admin-icon-btn {
            height: 46px;
            border-radius: 12px;
            border: 1px solid #d8e3ef;
            background: #fff;
            color: #2a4a71;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .admin-notify {
            width: 46px;
            position: relative;
            padding: 0;
        }

        .admin-notify-badge {
            position: absolute;
            top: 10px;
            right: 11px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid #fff;
        }

        .admin-profile {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            white-space: nowrap;
            font-weight: 700;
            color: var(--admin-text);
        }

        .admin-avatar {
            display: inline-flex;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: var(--admin-sidebar);
        }

        .admin-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .sidebar-overlay {
            display: none;
        }

        body.sidebar-collapsed .sidebar {
            transform: translateX(calc(var(--admin-sidebar-width) * -1));
        }

        body.sidebar-collapsed .topbar,
        body.sidebar-collapsed .content-area {
            margin-left: 0;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .topbar,
            .content-area {
                margin-left: 0 !important;
            }

            .topbar {
                padding: 12px 16px;
                align-items: stretch;
                flex-wrap: wrap;
            }

            .topbar-primary,
            .topbar-actions {
                width: 100%;
            }

            .topbar-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                justify-content: flex-start;
            }

            .content-area {
                padding: 16px;
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-open #sidebarToggle {
                position: fixed;
                top: 12px;
                left: 14px;
                z-index: 1050;
                background: rgba(255, 255, 255, 0.16);
                border-color: rgba(255, 255, 255, 0.32);
                color: #fff;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(2, 10, 22, 0.45);
                z-index: 1030;
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }
        }

        @media (max-width: 575px) {
            .topbar {
                padding: 12px;
            }

            .topbar-title {
                font-size: 0.86rem;
            }

            .admin-profile-name {
                display: none;
            }
        }

        .admin-site-preloader {
            position: fixed;
            inset: 0;
            z-index: 2000;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 10% 0%, rgba(163, 171, 207, 0.26), transparent 28%),
                        radial-gradient(circle at 92% 8%, rgba(230, 195, 106, 0.14), transparent 22%),
                        #edf1fb;
            transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .admin-site-preloader.is-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }

        .admin-site-preloader-content {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .admin-site-preloader-logo {
            width: 160px;
            height: auto;
            position: relative;
            z-index: 2;
            animation: adminGlowLogo 2s infinite alternate ease-in-out;
        }

        .admin-site-preloader-spinner {
            position: absolute;
            width: 280px;
            height: 280px;
            border: 3px solid transparent;
            border-top-color: #3b3f82;
            border-bottom-color: #3b3f82;
            border-radius: 50%;
            animation: adminSpinSpinner 2s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
            z-index: 1;
            box-shadow: 0 0 30px rgba(59, 63, 130, 0.15);
        }

        .admin-site-preloader-spinner::before {
            content: '';
            position: absolute;
            inset: 20px;
            border: 3px solid transparent;
            border-left-color: #e6c36a;
            border-right-color: #e6c36a;
            border-radius: 50%;
            animation: adminSpinSpinnerReverse 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
        }

        .admin-site-preloader-spinner::after {
            content: '';
            position: absolute;
            inset: 45px;
            border: 3px solid transparent;
            border-top-color: #231c63;
            border-radius: 50%;
            animation: adminSpinSpinner 1s linear infinite;
        }

        .admin-site-preloader-visual {
            position: relative;
            width: 280px;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-site-preloader-message {
            margin: 28px 0 0;
            position: relative;
            z-index: 2;
            font-size: 1.05rem;
            font-weight: 700;
            color: #273467;
        }

        body.admin-preloading {
            overflow: hidden;
            height: 100vh;
        }

        @keyframes adminSpinSpinner {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes adminSpinSpinnerReverse {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(-360deg); }
        }

        @keyframes adminGlowLogo {
            0% { filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.1)); transform: scale(0.95); }
            100% { filter: drop-shadow(0 0 25px rgba(255, 255, 255, 0.6)); transform: scale(1.05); }
        }

        @media (max-width: 767px) {
            .topbar {
                min-height: auto;
                gap: 10px;
            }

            .topbar-title {
                font-size: 0.82rem;
                line-height: 1.4;
            }

            .topbar-actions {
                gap: 10px;
            }

            .admin-icon-btn,
            .sidebar-toggle {
                min-height: 42px;
                width: auto;
            }

            .admin-profile {
                width: 100%;
                justify-content: flex-start;
                white-space: normal;
            }

            .admin-profile-name {
                overflow-wrap: anywhere;
            }
        }

        @media (max-width: 575px) {
            .sidebar {
                width: min(88vw, 300px);
            }

            .topbar {
                padding: 10px 12px;
            }

            .content-area {
                padding: 12px;
            }

            .topbar-actions {
                display: grid !important;
                grid-template-columns: 1fr 1fr;
                align-items: stretch;
                width: 100%;
            }

            .admin-icon-btn,
            .admin-profile {
                width: 100%;
            }

            .admin-icon-btn {
                justify-content: center;
            }

            .admin-profile {
                grid-column: 1 / -1;
            }
        }
    </style>
</head>
<body>
<div id="admin-site-preloader" class="admin-site-preloader is-hidden" aria-hidden="true">
    <div class="admin-site-preloader-content">
        <div class="admin-site-preloader-visual">
            <img src="<?php echo BASE_URL; ?>/public/assets/images/navbar-logo.svg" alt="AIML Logo" class="admin-site-preloader-logo">
            <div class="admin-site-preloader-spinner"></div>
        </div>
        <p class="admin-site-preloader-message" id="adminSitePreloaderMessage"><?php echo htmlspecialchars($adminPreloaderMessage, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>
<div class="sidebar">
    <div class="sidebar-brand">
        <span class="sidebar-brand-mark">AI</span>
        <div>
            <div class="sidebar-brand-title">AIML Admin</div>
            <div class="sidebar-brand-subtitle">Dept. of AI &amp; ML</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <?php foreach ($navItems as $item) { ?>
            <?php $isActive = strpos($currentAdminPath, $item['match']) !== false; ?>
            <a href="<?php echo $item['href']; ?>" class="<?php echo $isActive ? 'active' : ''; ?>">
                <i class="<?php echo $item['icon']; ?>"></i>
                <span><?php echo $item['label']; ?></span>
            </a>
        <?php } ?>

        <div class="sidebar-nav-bottom">
            <a href="<?php echo BASE_URL; ?>/admin/settings/changepassword.php" class="<?php echo strpos($currentAdminPath, '/admin/settings/changepassword.php') !== false ? 'active' : ''; ?>">
                <i class="bi bi-person-gear"></i>
                <span>Profile Settings</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/admin/logout.php" class="text-danger">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>
</div>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="topbar">
    <div class="topbar-primary d-flex align-items-center gap-2">
        <button id="sidebarToggle" type="button" class="sidebar-toggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="topbar-title">Dept. of <span>AI &amp; Machine Learning</span></h5>
    </div>

    <div class="topbar-actions d-flex align-items-center gap-3">
        <button type="button" class="admin-icon-btn admin-notify" aria-label="Notifications">
            <i class="bi bi-bell"></i>
            <span class="admin-notify-badge"></span>
        </button>
        <div class="admin-profile">
            <span class="admin-profile-name"><?php echo htmlspecialchars($adminFirstName, ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="admin-avatar">
                <img src="<?php echo htmlspecialchars($adminImageWebPath, ENT_QUOTES, 'UTF-8'); ?>" class="admin-img" alt="Admin">
            </span>
        </div>
    </div>
</div>

<div class="content-area">
<script>
    (function () {
        var body = document.body;
        var btn = document.getElementById('sidebarToggle');
        var overlay = document.getElementById('sidebarOverlay');
        var desktop = window.matchMedia('(min-width: 992px)');
        var welcomePreloader = document.getElementById('admin-site-preloader');

        if (!btn) return;

        if (welcomePreloader && <?php echo $adminPreloaderFlash !== null ? 'true' : 'false'; ?>) {
            welcomePreloader.classList.remove('is-hidden');
            body.classList.add('admin-preloading');

            window.setTimeout(function () {
                welcomePreloader.classList.add('is-hidden');
                body.classList.remove('admin-preloading');
            }, 1200);
        }

        btn.addEventListener('click', function () {
            if (desktop.matches) {
                body.classList.toggle('sidebar-collapsed');
            } else {
                body.classList.toggle('sidebar-open');
            }
        });

        if (overlay) {
            overlay.addEventListener('click', function () {
                body.classList.remove('sidebar-open');
            });
        }

        Array.prototype.forEach.call(document.querySelectorAll('a[href*="/admin/logout.php"]'), function (link) {
            link.addEventListener('click', function (event) {
                var href = link.getAttribute('href');
                var preloader = document.getElementById('admin-site-preloader');
                var message = document.getElementById('adminSitePreloaderMessage');

                if (!href || !preloader) {
                    return;
                }

                event.preventDefault();

                if (message) {
                    message.textContent = 'See you soon, <?php echo htmlspecialchars($adminFirstName, ENT_QUOTES, 'UTF-8'); ?>';
                }

                preloader.classList.remove('is-hidden');
                body.classList.add('admin-preloading');

                window.setTimeout(function () {
                    window.location.href = href;
                }, 850);
            });
        });

        window.addEventListener('resize', function () {
            if (desktop.matches) {
                body.classList.remove('sidebar-open');
            } else {
                body.classList.remove('sidebar-collapsed');
            }
        });
    })();
</script>
