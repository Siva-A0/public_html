<?php
if (session_id() == '') {
    session_start();
}

$requestPath = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$basePath = strtolower(rtrim(BASE_URL, '/'));
$relativePath = $requestPath;

if ($basePath !== '' && strpos($requestPath, $basePath) === 0) {
    $relativePath = substr($requestPath, strlen($basePath));
    if ($relativePath === '') {
        $relativePath = '/';
    }
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'user') {
    $allowedUserPaths = array(
        '/public/pages/user/dashboard.php',
        '/public/pages/user/academics.php',
        '/public/pages/user/profile.php',
        '/public/pages/user/achievements.php',
        '/public/pages/user/my_achievements.php',
        '/public/pages/user/downloads.php',
        '/public/pages/user/studentsupport.php',
        '/public/pages/authentication/logout.php'
    );

    if (!in_array($relativePath, $allowedUserPaths, true)) {
        header('Location: ' . BASE_URL . '/public/pages/user/dashboard.php');
        exit;
    }
}

$currentPage = basename($_SERVER['PHP_SELF']);
$isUserArea = isset($_SESSION['role']) && $_SESSION['role'] === 'user';
$publicSessionRole = (string)($_SESSION['role'] ?? '');
$publicIsAuthenticated = in_array($publicSessionRole, array('user', 'faculty'), true);
$hidePublicNavbar = isset($hidePublicNavbar) ? (bool)$hidePublicNavbar : false;
$currentPath = strtolower(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$isHomePage = $currentPage === 'index.php';
$isDepartmentsPage = strpos($currentPath, '/public/pages/department/') !== false;
$isEventsPage = strpos($currentPath, '/public/pages/events/') !== false;
$isGalleryPage = strpos($currentPath, '/public/pages/gallery.php') !== false;
$isPlacementsPage = strpos($currentPath, '/public/pages/placements.php') !== false;
$isAboutPage = strpos($currentPath, '/public/pages/aboutit.php') !== false;

$bodyClasses = array();
if ($isUserArea) {
    $bodyClasses[] = 'user-role';
}
if ($isHomePage) {
    $bodyClasses[] = 'home-page';
}

$publicPreloaderName = trim((string)($_SESSION['firstName'] ?? $_SESSION['facultyFirstName'] ?? $_SESSION['adminFirstName'] ?? $_SESSION['userName'] ?? $_SESSION['facultyName'] ?? $_SESSION['adminName'] ?? ''));
$publicPreloaderFlash = isset($_SESSION['site_preloader_once']) && is_array($_SESSION['site_preloader_once'])
    ? $_SESSION['site_preloader_once']
    : null;
if ($publicPreloaderFlash !== null) {
    unset($_SESSION['site_preloader_once']);
}
$publicPreloaderMessage = trim((string)($publicPreloaderFlash['message'] ?? ''));
if ($publicPreloaderMessage === '') {
    $publicPreloaderMessage = $publicPreloaderName !== '' ? ('Welcome ' . $publicPreloaderName) : 'Welcome to AIML Department';
}

$heroRobotWebPath = BASE_URL . '/public/assets/images/hero-robot.png';
$heroRobotFsPath = ROOT_PATH . '/public/assets/images/hero-robot.png';
$hasHeroRobotImage = $isHomePage && is_file($heroRobotFsPath);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AIML Department</title>
    <?php if ($isUserArea) { ?>
    <script>
    (function () {
        try {
            var savedTheme = localStorage.getItem('user-theme');
            if (!savedTheme) {
                savedTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            document.documentElement.setAttribute('data-theme', savedTheme === 'dark' ? 'dark' : 'light');
        } catch (e) {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    })();
    </script>
    <?php } ?>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/newstyle.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/site-refresh.css">
    <?php if ($isHomePage) { ?>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <?php } ?>


</head>

<body class="<?php echo implode(' ', $bodyClasses); ?>">

<!-- ================= PRELOADER ================= -->
<div id="site-preloader" aria-hidden="true">
    <div class="preloader-content">
        <div class="preloader-visual">
            <img src="<?php echo BASE_URL; ?>/public/assets/images/navbar-logo.svg" alt="AIML Logo" class="preloader-logo">
            <div class="preloader-spinner"></div>
        </div>
        <p class="preloader-message" id="sitePreloaderMessage"><?php echo htmlspecialchars($publicPreloaderMessage, ENT_QUOTES, 'UTF-8'); ?></p>
    </div>
</div>

<style>
#site-preloader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background: radial-gradient(circle at 10% 0%, rgba(163, 171, 207, 0.26), transparent 28%),
                radial-gradient(circle at 92% 8%, rgba(230, 195, 106, 0.14), transparent 22%),
                #edf1fb;
    z-index: 999999;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.8s cubic-bezier(0.16, 1, 0.3, 1);
}

#site-preloader.is-hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

#site-preloader.fade-out {
    opacity: 0;
    visibility: hidden;
}

.preloader-content {
    text-align: center;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.preloader-visual {
    position: relative;
    width: 280px;
    height: 280px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preloader-logo {
    width: 160px;
    height: auto;
    animation: glowLogo 2s infinite alternate ease-in-out;
    position: relative;
    z-index: 2;
}

.preloader-spinner {
    position: absolute;
    width: 280px;
    height: 280px;
    border: 3px solid transparent;
    border-top-color: #3b3f82;
    border-bottom-color: #3b3f82;
    border-radius: 50%;
    animation: spinSpinner 2s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
    z-index: 1;
    box-shadow: 0 0 30px rgba(59, 63, 130, 0.15);
}

.preloader-spinner::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 20px;
    right: 20px;
    bottom: 20px;
    border: 3px solid transparent;
    border-left-color: #e6c36a;
    border-right-color: #e6c36a;
    border-radius: 50%;
    animation: spinSpinnerReverse 1.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) infinite;
}

.preloader-spinner::after {
    content: '';
    position: absolute;
    top: 45px;
    left: 45px;
    right: 45px;
    bottom: 45px;
    border: 3px solid transparent;
    border-top-color: #231c63;
    border-radius: 50%;
    animation: spinSpinner 1s linear infinite;
}

.preloader-message {
    margin: 28px 0 0;
    position: relative;
    z-index: 2;
    font-size: 1.05rem;
    font-weight: 700;
    color: #273467;
    letter-spacing: 0.01em;
}

@keyframes spinSpinner {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

@keyframes spinSpinnerReverse {
    0% { transform: rotate(360deg); }
    100% { transform: rotate(-360deg); }
}

@keyframes glowLogo {
    0% { filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.1)); transform: scale(0.95); }
    100% { filter: drop-shadow(0 0 25px rgba(255, 255, 255, 0.6)); transform: scale(1.05); }
}

body.preloading {
    overflow: hidden;
    height: 100vh;
}
</style>

<script>
(function () {
    var preloader = document.getElementById('site-preloader');
    var messageNode = document.getElementById('sitePreloaderMessage');
    var isHomePage = <?php echo $isHomePage ? 'true' : 'false'; ?>;
    var hasFlashPreloader = <?php echo $publicPreloaderFlash !== null ? 'true' : 'false'; ?>;
    var shouldShowOnLoad = hasFlashPreloader;
    var homePreloaderSessionKey = 'aiml_home_preloader_seen';

    if (isHomePage && !hasFlashPreloader) {
        try {
            shouldShowOnLoad = window.sessionStorage.getItem(homePreloaderSessionKey) !== '1';
            if (shouldShowOnLoad) {
                window.sessionStorage.setItem(homePreloaderSessionKey, '1');
            }
        } catch (e) {
            shouldShowOnLoad = true;
        }
    }

    function hidePreloader() {
        if (!preloader) {
            return;
        }
        preloader.classList.add('fade-out');
        document.body.classList.remove('preloading');
        window.setTimeout(function () {
            preloader.classList.add('is-hidden');
            preloader.classList.remove('fade-out');
        }, 800);
    }

    window.showSitePreloader = function (message) {
        if (!preloader) {
            return;
        }
        if (messageNode && typeof message === 'string' && message !== '') {
            messageNode.textContent = message;
        }
        preloader.classList.remove('is-hidden', 'fade-out');
        document.body.classList.add('preloading');
    };

    window.hideSitePreloader = hidePreloader;

    if (shouldShowOnLoad) {
        document.body.classList.add('preloading');
        window.addEventListener('load', function () {
            window.setTimeout(hidePreloader, 1200);
        });
    } else if (preloader) {
        preloader.classList.add('is-hidden');
    }
})();
</script>

<!-- ================= NAVBAR ================= -->
<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3"> -->
<!-- <nav class="navbar navbar-expand-lg navbar-dark custom-navbar"> -->
    <?php if (!$isUserArea && !$hidePublicNavbar) { ?>
    <header class="topbar">
        <div class="nav-shell">
            <div class="nav-shell-top">
                <a class="brand" href="<?php echo BASE_URL; ?>/">
                    <div class="brand-mark" aria-hidden="true">
                        <img src="<?php echo BASE_URL; ?>/public/assets/images/navbar-logo.svg" alt="AIML Logo">
                    </div>
                    <div class="brand-copy">
                        <span class="brand-title">Department of AIML</span>
                        <span class="brand-subtitle">Artificial Intelligence and Machine Learning</span>
                    </div>
                </a>

                <button
                    type="button"
                    class="nav-toggle"
                    aria-label="Toggle navigation"
                    aria-controls="site-navigation"
                    aria-expanded="false"
                >
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>

            <nav class="nav-links" id="site-navigation" aria-label="Primary navigation">
                <div class="nav-links-main">
                    <a class="<?php echo $isHomePage ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/"><span class="nav-link-text">Home</span></a>
                    <a class="<?php echo $isDepartmentsPage ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/public/pages/department/department.php"><span class="nav-link-text">Departments</span></a>
                    <a class="<?php echo $isEventsPage ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/public/pages/Events/events.php"><span class="nav-link-text">Events</span></a>
                    <a class="<?php echo $isGalleryPage ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/public/pages/gallery.php"><span class="nav-link-text">Gallery</span></a>
                    <a class="<?php echo $isPlacementsPage ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/public/pages/placements.php"><span class="nav-link-text">Placements</span></a>
                    <a class="<?php echo $isAboutPage ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>/public/pages/aboutit.php"><span class="nav-link-text">About Us</span></a>
                </div>

                <div class="nav-links-actions">
                    <?php if (!$publicIsAuthenticated) { ?>
                        <a class="nav-link-login" href="<?php echo BASE_URL; ?>/public/pages/Authentication/login.php"><span class="nav-link-text">Login</span></a>
                    <?php } else { ?>
                        <a class="nav-link-login" href="<?php echo BASE_URL; ?>/public/pages/Authentication/logout.php"><span class="nav-link-text">Logout</span></a>
                    <?php } ?>
                </div>
            </nav>
        </div>
    </header>
    <?php } ?>


<!-- ================= HERO (ONLY INDEX PAGE) ================= -->
<?php if ($isHomePage) { ?>
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <span class="hero-kicker">Department of AIML</span>
            <h1 class="hero-title">
                <span>Code.</span> <span>Learn.</span> <span class="typing hero-accent">Evolve.</span>
            </h1>
            <p class="hero-subtitle">
                Transforming ideas into AI-driven solutions through research, hands-on labs, and industry-ready learning paths.
            </p>

            <div class="hero-actions">
                <a href="<?php echo BASE_URL; ?>/public/pages/department/department.php" class="btn hero-btn hero-btn-primary" >
                    Explore Department
                    <span aria-hidden="true">-></span>
                </a>
                <!-- <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/register.php" class="btn hero-btn hero-btn-secondary">
                    Admissions 2026
                </a> -->
            </div>

            <div class="hero-stats" data-aos="fade-up" data-aos-delay="140">
                <div class="hero-stat">
                    <strong>1200+</strong>
                    <span>Students</span>
                </div>
                <div class="hero-stat">
                    <strong>25+</strong>
                    <span>Research Labs</span>
                </div>
                <div class="hero-stat">
                    <strong>40+</strong>
                    <span>2025 Placements</span>
                </div>
            </div>

            <!-- <div class="hero-chip-row">
                <span class="hero-chip">
                    <i class="bi bi-cpu"></i>
                    AI Brain
                </span>
            </div> -->
        </div>

        <!-- <div class="hero-visual-wrap" data-aos="fade-left" data-aos-delay="180">
            <div class="hero-visual<?php echo $hasHeroRobotImage ? ' has-hero-image' : ''; ?>">
                <div class="hero-orb hero-orb-1"></div>
                <div class="hero-orb hero-orb-2"></div>
                <div class="hero-orb hero-orb-3"></div>
                <?php if ($hasHeroRobotImage) { ?>
                <img
                    src="<?php echo $heroRobotWebPath; ?>"
                    alt="AI Robot"
                    class="hero-robot-image"
                    loading="eager"
                >
                <?php } else { ?>
                <div class="ai-core">
                    <i class="bi bi-cpu-fill" aria-hidden="true"></i>
                    <span>AI Brain</span>
                </div>
                <?php } ?>
            </div>
        </div> -->
    </div>
</section>
<?php } ?>





<!-- <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">

  <div class="carousel-inner">

    <?php for($i=1; $i<=6; $i++){ ?>
        <div class="carousel-item <?php if($i==1) echo 'active'; ?>">
            <img src="images/sliderimages/image_<?php echo $i; ?>.png" 
                 class="d-block w-100"
                 style="height:400px; object-fit:cover;">
        </div>
    <?php } ?>

  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
  </button>

  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
  </button>

</div> -->



 <?php if ($isHomePage) { ?>
 <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
 <script>
 (function () {
    if (window.__homeHeroTypingInitialized) {
        return;
    }
    window.__homeHeroTypingInitialized = true;

     if (window.AOS) {
         window.AOS.init({
             duration: 700,
             once: true,
            offset: 40
        });
    }

    var words = ["Evolve.", "Innovate.", "Build AI.", "Lead the Future."];
    var index = 0;
    var letter = 0;
    var isDeleting = false;
    var typingElement = document.querySelector(".typing");

    if (!typingElement) {
        return;
    }

    function typeWord() {
        var currentWord = words[index];

        if (isDeleting) {
            typingElement.textContent = currentWord.substring(0, letter--);
        } else {
            typingElement.textContent = currentWord.substring(0, letter++);
        }

        if (!isDeleting && letter === currentWord.length + 1) {
            isDeleting = true;
            setTimeout(typeWord, 1200);
            return;
        }

        if (isDeleting && letter === 0) {
            isDeleting = false;
            index = (index + 1) % words.length;
        }

        setTimeout(typeWord, isDeleting ? 60 : 120);
    }
 
     typeWord();
 })();
 </script>
 <?php } ?>

<script>
(function () {
    var topbar = document.querySelector('.topbar');
    var navToggle = document.querySelector('.nav-toggle');
    var navLinks = document.getElementById('site-navigation');

    function syncPublicNav() {
        if (!topbar || !navToggle || !navLinks) {
            return;
        }

        if (window.innerWidth > 767) {
            topbar.classList.remove('menu-open');
            navToggle.setAttribute('aria-expanded', 'false');
            return;
        }

        if (!topbar.classList.contains('menu-open')) {
            navToggle.setAttribute('aria-expanded', 'false');
        }
    }

    if (topbar && navToggle && navLinks) {
        navToggle.addEventListener('click', function () {
            var isOpen = topbar.classList.toggle('menu-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

         Array.prototype.forEach.call(navLinks.querySelectorAll('a'), function (link) {
             link.addEventListener('touchstart', function () {
                 link.classList.add('is-touched');
                 window.setTimeout(function () {
                     link.classList.remove('is-touched');
                 }, 450);
             }, { passive: true });

             link.addEventListener('click', function () {
                 if (window.innerWidth <= 767) {
                     topbar.classList.remove('menu-open');
                     navToggle.setAttribute('aria-expanded', 'false');
                 }
             });
         });

        window.addEventListener('resize', syncPublicNav);
        syncPublicNav();
    }

    Array.prototype.forEach.call(document.querySelectorAll('a[href*="/logout.php"]'), function (link) {
        link.addEventListener('click', function (event) {
            if (typeof window.showSitePreloader !== 'function') {
                return;
            }

            var href = link.getAttribute('href');
            if (!href) {
                return;
            }

            event.preventDefault();
            var logoutName = <?php echo json_encode($publicPreloaderName, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;
            window.showSitePreloader(logoutName ? ('See you soon, ' + logoutName) : 'See you soon');
            window.setTimeout(function () {
                window.location.href = href;
            }, 850);
        });
    });

    function setUserNavbarHeight() {
        if (!document.body.classList.contains('user-role')) {
            return;
        }
        var nav = document.getElementById('mainNavbar');
        if (!nav) {
            return;
        }
        document.body.style.setProperty('--user-navbar-height', nav.offsetHeight + 'px');
    }

    window.addEventListener('load', setUserNavbarHeight);
     window.addEventListener('resize', setUserNavbarHeight);
     setUserNavbarHeight();
 })();
 </script>
 
 <section class="page-content <?php echo $isUserArea ? 'user-page-content' : 'py-5'; ?>">
