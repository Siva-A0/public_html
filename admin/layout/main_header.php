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
    array('href' => BASE_URL . '/admin/placements/placements.php', 'icon' => 'bi bi-briefcase', 'label' => 'Placements', 'match' => '/admin/placements/'),
    array('href' => BASE_URL . '/admin/students/students.php', 'icon' => 'bi bi-people', 'label' => 'Students', 'match' => '/admin/students/students.php'),
    array('href' => BASE_URL . '/admin/students/alumni.php', 'icon' => 'bi bi-person-badge', 'label' => 'Alumni', 'match' => '/admin/students/alumni.php'),
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
    <script>
    (function () {
        try {
            var savedTheme = localStorage.getItem('admin-theme');
            if (!savedTheme) {
                savedTheme = 'light';
            }
            document.documentElement.setAttribute('data-theme', savedTheme === 'dark' ? 'dark' : 'light');
        } catch (e) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    })();
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/site-refresh.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/admin-refresh.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/admin/admin_layout.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/admin/admin_dark.css?v=1">
    <?php if (!empty($adminExtraStyles) && is_array($adminExtraStyles)) { ?>
        <?php foreach (array_unique(array_filter($adminExtraStyles)) as $adminExtraStyle) { ?>
            <link rel="stylesheet" href="<?php echo htmlspecialchars((string)$adminExtraStyle, ENT_QUOTES, 'UTF-8'); ?>">
        <?php } ?>
    <?php } ?>
    <?php if (!defined('ADMIN_EXTRA_STYLES_RENDERED')) { define('ADMIN_EXTRA_STYLES_RENDERED', true); } ?>
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
        <button id="adminThemeToggle" type="button" class="admin-icon-btn" aria-label="Switch admin theme">
            <i id="adminThemeToggleIcon" class="bi bi-moon-stars-fill"></i>
            <span id="adminThemeToggleText">Dark</span>
        </button>
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
        var root = document.documentElement;
        var themeBtn = document.getElementById('adminThemeToggle');
        var themeIcon = document.getElementById('adminThemeToggleIcon');
        var themeText = document.getElementById('adminThemeToggleText');

        if (!btn) return;

        function updateThemeUI(theme) {
            if (!themeIcon || !themeText) {
                return;
            }
            if (theme === 'dark') {
                themeIcon.className = 'bi bi-sun-fill';
                themeText.textContent = 'Light';
            } else {
                themeIcon.className = 'bi bi-moon-stars-fill';
                themeText.textContent = 'Dark';
            }
        }

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

        updateThemeUI(root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light');

        if (themeBtn) {
            themeBtn.addEventListener('click', function () {
                var current = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
                var next = current === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                updateThemeUI(next);
                try {
                    localStorage.setItem('admin-theme', next);
                } catch (e) {}
            });
        }

        window.addEventListener('resize', function () {
            if (desktop.matches) {
                body.classList.remove('sidebar-open');
            } else {
                body.classList.remove('sidebar-collapsed');
            }
        });
    })();
</script>



