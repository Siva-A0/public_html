<?php
if (!isset($facultyActivePage) || !is_string($facultyActivePage)) {
    $facultyActivePage = 'dashboard';
}

function facultyNavActive($key, $active)
{
    return $key === $active ? ' active' : '';
}

$facultyWelcomeName = trim((string)($_SESSION['facultyFirstName'] ?? $_SESSION['facultyName'] ?? 'Faculty'));
if ($facultyWelcomeName === '') {
    $facultyWelcomeName = 'Faculty';
}

$facultyImageFile = trim((string)($_SESSION['facultyImage'] ?? ''));
$facultyImageUrl = '';
if ($facultyImageFile !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $facultyImageFile) === 1) {
    $facultyImageFsPath = ROOT_PATH . '/public/assets/images/faculty/' . $facultyImageFile;
    if (is_file($facultyImageFsPath)) {
        $facultyImageUrl = BASE_URL . '/public/assets/images/faculty/' . rawurlencode($facultyImageFile);
    }
}

$facultyInitial = strtoupper(substr($facultyWelcomeName, 0, 1));
if ($facultyInitial === '') {
    $facultyInitial = 'F';
}
?>

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/faculty/faculty_layout.css">
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/faculty/faculty_dark.css">

<div id="faculty-site-preloader" class="faculty-preloader is-hidden" aria-hidden="true">
    <div class="faculty-preloader-content">
        <div class="faculty-preloader-visual">
            <img src="<?php echo BASE_URL; ?>/public/assets/images/navbar-logo.svg" alt="AIML Logo" class="faculty-preloader-logo">
            <div class="faculty-preloader-spinner"></div>
        </div>
        <p class="faculty-preloader-message" id="facultyPreloaderMessage">See you soon, <?php echo htmlspecialchars($facultyWelcomeName, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>

<div class="container faculty-layout-wrap">
    <div class="faculty-dashboard-shell row g-4">
        <div class="col-lg-3">
            <aside class="faculty-side-panel" id="faculty-sidebar">
                <h5 class="faculty-sidebar-brand text-center"><span class="faculty-sidebar-brand-title">AIML Faculty</span><span class="faculty-sidebar-brand-subtitle">Teaching Workspace</span></h5>

                <nav class="faculty-side-nav faculty-side-nav-main">
                    <div class="faculty-side-section">Workspace</div>
                    <a class="faculty-side-link<?php echo facultyNavActive('dashboard', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/dashboard/index.php"><i class="bi bi-speedometer2 faculty-side-link-icon"></i><span>Dashboard</span></a>
                    <a class="faculty-side-link<?php echo facultyNavActive('achievements', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/achievements.php"><i class="bi bi-trophy faculty-side-link-icon"></i><span>Upload Achievement</span></a>
                    <a class="faculty-side-link<?php echo facultyNavActive('my_achievements', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/my_achievements.php"><i class="bi bi-award faculty-side-link-icon"></i><span>My Achievements</span></a>
                    <a class="faculty-side-link<?php echo facultyNavActive('certifications', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/certifications.php"><i class="bi bi-patch-check faculty-side-link-icon"></i><span>Upload Certification</span></a>
                    <a class="faculty-side-link<?php echo facultyNavActive('my_certifications', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/my_certifications.php"><i class="bi bi-file-earmark-medical faculty-side-link-icon"></i><span>My Certifications</span></a>
                    <a class="faculty-side-link<?php echo facultyNavActive('profile', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/profile.php"><i class="bi bi-person-gear faculty-side-link-icon"></i><span>Account Settings</span></a>
                    <a class="faculty-side-link is-disabled" href="javascript:void(0)" aria-disabled="true"><i class="bi bi-download faculty-side-link-icon"></i><span>Downloads</span><span class="faculty-side-link-note">Soon</span></a>
                </nav>

                <nav class="faculty-side-nav faculty-side-nav-utility">
                    <div class="faculty-side-section">Session</div>
                    <a class="faculty-side-link faculty-side-link-logout" href="<?php echo BASE_URL; ?>/public/pages/Authentication/logout.php?role=faculty"><i class="bi bi-box-arrow-right faculty-side-link-icon"></i><span>Logout</span></a>
                </nav>
            </aside>
        </div>

        <div class="col-lg-9">
            <div class="faculty-page-topbar">
                <div class="faculty-page-topbar-left">
                    <button type="button" class="faculty-sidebar-toggle" id="facultySidebarToggle" aria-controls="faculty-sidebar" aria-expanded="true" aria-label="Toggle faculty menu">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="faculty-page-greet">Welcome, <?php echo htmlspecialchars($facultyWelcomeName, ENT_QUOTES, 'UTF-8'); ?>!</div>
                </div>

                <div class="faculty-page-topbar-right">
                    <button id="facultyThemeToggle" type="button" class="faculty-theme-toggle" aria-label="Switch faculty theme">
                        <i id="facultyThemeToggleIcon" class="bi bi-moon-stars-fill"></i>
                        <span id="facultyThemeToggleText">Dark</span>
                    </button>
                    <?php if ($facultyImageUrl !== '') { ?>
                        <img src="<?php echo htmlspecialchars($facultyImageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Faculty" class="faculty-topbar-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                        <span class="faculty-topbar-avatar-fallback" style="display:none;"><?php echo htmlspecialchars($facultyInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php } else { ?>
                        <span class="faculty-topbar-avatar-fallback"><?php echo htmlspecialchars($facultyInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php } ?>
                </div>
            </div>



