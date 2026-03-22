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
    $facultyImageFsPath = ROOT_PATH . '/public/assets/images/staff/' . $facultyImageFile;
    if (is_file($facultyImageFsPath)) {
        $facultyImageUrl = BASE_URL . '/public/assets/images/staff/' . rawurlencode($facultyImageFile);
    }
}

$facultyInitial = strtoupper(substr($facultyWelcomeName, 0, 1));
if ($facultyInitial === '') {
    $facultyInitial = 'F';
}
?>

<style>
.faculty-dashboard-shell {
    max-width: none;
    margin: 0;
    --bs-gutter-x: 0;
    --faculty-sidebar-width: 260px;
    min-height: 100vh;
    position: relative;
}

.faculty-layout-wrap {
    max-width: 100% !important;
    padding-left: 0 !important;
    padding-right: 0 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

.faculty-page-content {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

.faculty-dashboard-shell > .col-lg-3 {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--faculty-sidebar-width);
    max-width: var(--faculty-sidebar-width);
    height: 100vh;
    flex: initial;
    padding: 0;
    margin: 0;
    z-index: 1040;
    overflow: hidden;
    display: flex;
}

.faculty-dashboard-shell > .col-lg-9 {
    width: calc(100% - var(--faculty-sidebar-width));
    max-width: none;
    flex: initial;
    min-width: 0;
    padding: 0 24px 24px 0;
    margin: 0 0 0 calc(var(--faculty-sidebar-width) + 20px);
}

.faculty-side-panel {
    background: linear-gradient(180deg, #17304f 0%, #1b2740 58%, #172236 100%);
    border-right: 1px solid rgba(255, 255, 255, 0.08);
    padding: 0;
    min-height: 100vh;
    width: 100%;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 18px 0 36px rgba(9, 18, 32, 0.14);
}

.faculty-sidebar-brand {
    margin: 0;
    padding: 22px 18px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0.01));
    text-align: center;
}

.faculty-sidebar-brand-title {
    display: block;
    font-size: 16px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #f7fbff;
}

.faculty-sidebar-brand-subtitle {
    display: block;
    margin-top: 6px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #93abc7;
}

.faculty-side-nav {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 14px 10px 12px;
}

.faculty-side-nav-main {
    flex: 1;
}

.faculty-side-nav-utility {
    border-top: 1px solid rgba(255, 255, 255, 0.12);
    padding-top: 14px;
    padding-bottom: 16px;
}

.faculty-side-section {
    padding: 0 12px 8px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.10em;
    text-transform: uppercase;
    color: #7f97b3;
}

.faculty-side-link {
    position: relative;
    text-decoration: none;
    color: #d5deeb;
    font-size: 17px;
    font-weight: 600;
    padding: 13px 14px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    border: 1px solid transparent;
    transition: transform 0.18s ease, background 0.18s ease, border-color 0.18s ease, color 0.18s ease, box-shadow 0.18s ease;
}

.faculty-side-link:hover,
.faculty-side-link.active {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255,255,255,0.08);
    color: #ffffff;
    transform: translateX(2px);
}

.faculty-side-link.active {
    background: linear-gradient(135deg, rgba(255,255,255,0.14), rgba(255,255,255,0.08));
    box-shadow: inset 4px 0 0 #f0b323;
}

.faculty-side-link.is-disabled {
    opacity: 0.92;
    color: #c2d0e1;
    background: linear-gradient(135deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
    border-color: rgba(255,255,255,0.05);
    cursor: default;
}

.faculty-side-link.is-disabled:hover {
    transform: none;
    background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.02));
    color: #d7e2ef;
}

.faculty-side-link.is-disabled .faculty-side-link-icon {
    color: #a9bdd3;
}

.faculty-side-link-note {
    margin-left: auto;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 5px 9px;
    border-radius: 999px;
    background: rgba(240, 179, 35, 0.16);
    color: #f6c54f;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    white-space: nowrap;
}

.faculty-side-link-icon {
    width: 20px;
    text-align: center;
    font-size: 16px;
    color: #9eb2cf;
    opacity: 0.95;
}

.faculty-side-link:hover .faculty-side-link-icon,
.faculty-side-link.active .faculty-side-link-icon {
    color: #ffffff;
}

.faculty-side-link-logout,
.faculty-side-link-logout .faculty-side-link-icon {
    color: #ff8e8e;
}

.faculty-side-link-logout {
    margin-top: 6px;
    background: rgba(255, 255, 255, 0.03);
}

.faculty-page-topbar {
    margin: 16px 0 14px;
    padding: 12px 16px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(208, 219, 233, 0.9);
    box-shadow: 0 8px 18px rgba(12, 24, 40, 0.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.faculty-page-topbar-left,
.faculty-page-topbar-right {
    display: flex;
    align-items: center;
    gap: 12px;
}

.faculty-sidebar-toggle,
.faculty-theme-toggle,
.faculty-topbar-back {
    border: 1px solid rgba(191, 205, 224, 0.95);
    background: linear-gradient(180deg, #f7fbff 0%, #eef4fa 100%);
    color: #3b5373;
    border-radius: 999px;
    padding: 10px 16px;
    min-height: 42px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 16px;
    font-weight: 600;
}

.faculty-sidebar-toggle,
.faculty-topbar-back {
    width: 42px;
    padding: 0;
}

.faculty-page-greet {
    font-size: clamp(22px, 3vw, 32px);
    font-weight: 800;
    color: #2d4869;
}

.faculty-topbar-avatar,
.faculty-topbar-avatar-fallback {
    width: 36px;
    height: 36px;
    border-radius: 50%;
}

.faculty-topbar-avatar {
    object-fit: cover;
}

.faculty-topbar-avatar-fallback {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #dfe8f5 0%, #c3d4ea 100%);
    color: #294a73;
    font-weight: 700;
}

.faculty-preloader {
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

.faculty-preloader.is-hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.faculty-preloader-content {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.faculty-preloader-visual {
    position: relative;
    width: 280px;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.faculty-preloader-logo {
    width: 160px;
    height: auto;
    position: relative;
    z-index: 2;
    animation: facultyGlowLogo 2s infinite alternate ease-in-out;
}

.faculty-preloader-spinner {
    position: absolute;
    width: 280px;
    height: 280px;
    border: 3px solid transparent;
    border-top-color: #3b3f82;
    border-bottom-color: #3b3f82;
    border-radius: 50%;
    animation: facultySpinSpinner 2s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
    z-index: 1;
    box-shadow: 0 0 30px rgba(59, 63, 130, 0.15);
}

.faculty-preloader-spinner::before {
    content: '';
    position: absolute;
    inset: 20px;
    border: 3px solid transparent;
    border-left-color: #e6c36a;
    border-right-color: #e6c36a;
    border-radius: 50%;
    animation: facultySpinSpinnerReverse 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

.faculty-preloader-spinner::after {
    content: '';
    position: absolute;
    inset: 45px;
    border: 3px solid transparent;
    border-top-color: #231c63;
    border-radius: 50%;
    animation: facultySpinSpinner 1s linear infinite;
}

.faculty-preloader-message {
    margin: 28px 0 0;
    position: relative;
    z-index: 2;
    font-size: 1.05rem;
    font-weight: 700;
    color: #273467;
}

body.faculty-preloading {
    overflow: hidden;
    height: 100vh;
}

@keyframes facultySpinSpinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes facultySpinSpinnerReverse {
    0% { transform: rotate(360deg); }
    100% { transform: rotate(-360deg); }
}

@keyframes facultyGlowLogo {
    0% { filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.1)); transform: scale(0.95); }
    100% { filter: drop-shadow(0 0 25px rgba(255, 255, 255, 0.6)); transform: scale(1.05); }
}

@media (max-width: 991px) {
    .faculty-dashboard-shell {
        display: block;
        min-height: 0;
    }

    .faculty-layout-wrap {
        padding-left: 12px !important;
        padding-right: 12px !important;
        margin-bottom: 20px !important;
    }

    .faculty-dashboard-shell > .col-lg-3 {
        position: fixed;
        top: 0;
        left: 0;
        width: 260px;
        max-width: 260px;
        height: 100vh;
        transform: translateX(-100%);
        z-index: 1045;
    }

    body.faculty-mobile-sidebar-open .faculty-dashboard-shell > .col-lg-3 {
        transform: translateX(0);
    }

    .faculty-dashboard-shell > .col-lg-9 {
        width: 100%;
        margin-left: 0;
        padding: 0;
    }

    .faculty-side-panel {
        border-radius: 0;
        border-right: 0;
        min-height: 100vh;
    }
}

@media (min-width: 992px) {
    body.faculty-sidebar-collapsed .faculty-dashboard-shell > .col-lg-3 {
        transform: translateX(-100%);
    }

    body.faculty-sidebar-collapsed .faculty-dashboard-shell > .col-lg-9 {
        width: 100%;
        margin-left: 0;
        padding-left: 24px;
    }
}

@media (max-width: 767px) {
    .faculty-page-topbar {
        flex-direction: column;
        align-items: stretch;
    }

    .faculty-page-topbar-left,
    .faculty-page-topbar-right {
        justify-content: space-between;
    }

    .faculty-side-link {
        font-size: 15px;
    }

    .faculty-side-link-note {
        font-size: 9px;
        padding: 4px 7px;
    }
}
</style>

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
                    <a class="faculty-side-link<?php echo facultyNavActive('dashboard', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/dashboard.php"><i class="bi bi-speedometer2 faculty-side-link-icon"></i><span>Dashboard</span></a>
                    <a class="faculty-side-link is-disabled" href="javascript:void(0)" aria-disabled="true"><i class="bi bi-trophy faculty-side-link-icon"></i><span>Upload Achievement</span><span class="faculty-side-link-note">Soon</span></a>
                    <a class="faculty-side-link is-disabled" href="javascript:void(0)" aria-disabled="true"><i class="bi bi-award faculty-side-link-icon"></i><span>My Achievements</span><span class="faculty-side-link-note">Soon</span></a>
                    <a class="faculty-side-link<?php echo facultyNavActive('profile', $facultyActivePage); ?>" href="<?php echo BASE_URL; ?>/public/pages/faculty/profile.php"><i class="bi bi-person-gear faculty-side-link-icon"></i><span>Account Settings</span></a>
                    <a class="faculty-side-link is-disabled" href="javascript:void(0)" aria-disabled="true"><i class="bi bi-download faculty-side-link-icon"></i><span>Downloads</span><span class="faculty-side-link-note">Soon</span></a>
                </nav>

                <nav class="faculty-side-nav faculty-side-nav-utility">
                    <div class="faculty-side-section">Session</div>
                    <a class="faculty-side-link faculty-side-link-logout" href="<?php echo BASE_URL; ?>/public/pages/Authentication/logout.php"><i class="bi bi-box-arrow-right faculty-side-link-icon"></i><span>Logout</span></a>
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
                    <?php if ($facultyImageUrl !== '') { ?>
                        <img src="<?php echo htmlspecialchars($facultyImageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="Faculty" class="faculty-topbar-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                        <span class="faculty-topbar-avatar-fallback" style="display:none;"><?php echo htmlspecialchars($facultyInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php } else { ?>
                        <span class="faculty-topbar-avatar-fallback"><?php echo htmlspecialchars($facultyInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php } ?>
                </div>
            </div>

