<?php
if (!isset($userActivePage) || !is_string($userActivePage)) {
    $userActivePage = 'dashboard';
}

function userNavActive($key, $active)
{
    return $key === $active ? ' active' : '';
}

$welcomeName = trim((string)($_SESSION['firstName'] ?? $_SESSION['userName'] ?? 'Student'));
if ($welcomeName === '') {
    $welcomeName = 'Student';
}

$userImageFile = trim((string)($_SESSION['image'] ?? ''));
$userImageUrl = '';
if ($userImageFile !== '') {
    // Only show the image if it's a safe filename and the file exists.
    if (preg_match('/^[A-Za-z0-9._-]+$/', $userImageFile) === 1) {
        $userImageFsPath = ROOT_PATH . '/public/assets/images/students/' . $userImageFile;
        if (is_file($userImageFsPath)) {
            $userImageUrl = BASE_URL . '/public/assets/images/students/' . rawurlencode($userImageFile);
        } else {
            $userImageFile = '';
        }
    } else {
        $userImageFile = '';
    }
}

$userInitial = strtoupper(substr($welcomeName, 0, 1));
if ($userInitial === '') {
    $userInitial = 'S';
}
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/student/student_layout.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/student/student_dark.css">

<div id="user-site-preloader" class="user-site-preloader is-hidden" aria-hidden="true">
    <div class="user-site-preloader-content">
        <div class="user-site-preloader-visual">
            <img src="<?php echo BASE_URL; ?>/public/assets/images/navbar-logo.svg" alt="AIML Logo" class="user-site-preloader-logo">
            <div class="user-site-preloader-spinner"></div>
        </div>
        <p class="user-site-preloader-message" id="userSitePreloaderMessage">Logging out...</p>
    </div>
</div>

<script>
(function () {
    var preloader = document.getElementById('user-site-preloader');
    var message = document.getElementById('userSitePreloaderMessage');
    
    document.addEventListener('click', function (event) {
        var link = event.target.closest('a[href*="/public/pages/Authentication/logout.php"]');

        if (!link || !preloader) {
            return;
        }

        var href = link.getAttribute('href');
        if (!href) {
            return;
        }

        event.preventDefault();

        if (message) {
            message.textContent = 'See you soon, <?php echo htmlspecialchars($welcomeName, ENT_QUOTES, 'UTF-8'); ?>';
        }

        preloader.classList.remove('is-hidden');
        document.body.classList.add('user-preloading');

        window.setTimeout(function () {
            window.location.href = href;
        }, 850);
    });
})();
</script>

<div class="container user-profile-wrap user-layout-wrap">
    <div class="user-dashboard-shell row g-4">
        <div class="col-lg-3">
            <aside class="user-side-panel" id="user-sidebar">
                <h5 class="user-sidebar-brand text-center">AIML User</h5>

                <nav class="user-side-nav user-side-nav-main">
                    <a class="user-side-link<?php echo userNavActive('dashboard', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/dashboard/index.php"><i class="bi bi-speedometer2 user-side-link-icon"></i><span>Dashboard</span></a>
                    <a class="user-side-link<?php echo userNavActive('academics', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/academics.php"><i class="bi bi-mortarboard user-side-link-icon"></i><span>Academics</span></a>
                    <a class="user-side-link" href="https://erp.nrcmec.org/"><i class="bi bi-journal-check user-side-link-icon"></i><span>Exam Cell</span></a>
                    <a class="user-side-link<?php echo userNavActive('achievements', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/achievements.php"><i class="bi bi-trophy user-side-link-icon"></i><span>Upload Achievement</span></a>
                    <a class="user-side-link<?php echo userNavActive('my_achievements', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/my_achievements.php"><i class="bi bi-award user-side-link-icon"></i><span>My Achievements</span></a>
                    <a class="user-side-link<?php echo userNavActive('certifications', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/certifications.php"><i class="bi bi-patch-check user-side-link-icon"></i><span>Upload Certification</span></a>
                    <a class="user-side-link<?php echo userNavActive('my_certifications', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/my_certifications.php"><i class="bi bi-file-earmark-medical user-side-link-icon"></i><span>My Certifications</span></a>
                    <a class="user-side-link<?php echo userNavActive('profile', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/profile.php"><i class="bi bi-person-gear user-side-link-icon"></i><span>Account Settings</span></a>
                    <a class="user-side-link<?php echo userNavActive('downloads', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/downloads.php"><i class="bi bi-download user-side-link-icon"></i><span>Downloads</span></a>
                    <a class="user-side-link<?php echo userNavActive('studentsupport', $userActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/student/studentsupport.php"><i class="bi bi-headset user-side-link-icon"></i><span>Student Support</span></a>
                    <a class="user-side-link user-side-link-logout user-side-link-bottom" href="<?php echo BASE_URL; ?>/public/pages/Authentication/logout.php"><i class="bi bi-box-arrow-right user-side-link-icon"></i><span>Logout</span></a>
                </nav>
            </aside>
        </div>

        <div class="col-lg-9">
            <div class="user-page-topbar">
                <div class="user-page-topbar-left">
                    <?php if ($userActivePage !== 'dashboard') { ?>
                        <button
                            type="button"
                            class="user-topbar-back"
                            id="userBackButton"
                            data-fallback-url="<?php echo BASE_URL; ?>/public/pages/student/dashboard/index.php"
                            aria-label="Go back"
                        >
                            <i class="bi bi-arrow-left"></i>
                        </button>
                    <?php } ?>
                    <button type="button" class="user-sidebar-toggle" id="userSidebarToggle" aria-controls="user-sidebar" aria-expanded="true" aria-label="Toggle user menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="user-page-greet">Welcome, <?php echo htmlspecialchars($welcomeName); ?>!</div>
                </div>

                <div class="user-page-topbar-right">
                    <button id="userThemeToggle" type="button" class="user-theme-toggle" aria-label="Switch user theme">
                        <i id="userThemeToggleIcon" class="bi bi-moon-stars-fill"></i>
                        <span id="userThemeToggleText">Dark</span>
                    </button>
                    <?php if ($userImageUrl !== '') { ?>
                        <img src="<?php echo htmlspecialchars($userImageUrl); ?>" alt="User" class="user-topbar-avatar" width="34" height="34" style="width:34px;height:34px;object-fit:cover;border-radius:50%;display:block;" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                        <span class="user-topbar-avatar-fallback" style="display:none;"><?php echo htmlspecialchars($userInitial); ?></span>
                    <?php } else { ?>
                        <span class="user-topbar-avatar-fallback"><?php echo htmlspecialchars($userInitial); ?></span>
                    <?php } ?>
                </div>
            </div>





