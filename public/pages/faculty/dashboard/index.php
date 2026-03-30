<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'faculty' || !isset($_SESSION['facultyId'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/faculty_login.php');
    exit;
}

$fcObj = new DataFunctions();
$staffRows = $fcObj->getStaffDetailsById(TB_STAFF, (int)$_SESSION['facultyId']);
if (empty($staffRows)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$faculty = $staffRows[0];
$facultyName = trim((string)$faculty['first_name'] . ' ' . (string)$faculty['last_name']);
if ($facultyName === '') {
    $facultyName = trim((string)($_SESSION['facultyName'] ?? $_SESSION['facultyFirstName'] ?? 'Faculty Member'));
}

$facultyDisplayName = strtoupper($facultyName !== '' ? $facultyName : 'FACULTY');
$facultyEmail = trim((string)($faculty['e_mail'] ?? $_SESSION['facultyEmail'] ?? ''));
$facultyQualification = str_replace('\\,', ',', (string)($faculty['qualification'] ?? ''));
$facultyDesignation = trim((string)($faculty['designation'] ?? ''));
$facultyIndustryExp = trim((string)($faculty['industry_exp'] ?? ''));
$facultyTeachingExp = trim((string)($faculty['teach_exp'] ?? ''));
$facultyResearch = trim((string)($faculty['research'] ?? ''));
$facultyImage = trim((string)($faculty['image'] ?? $_SESSION['facultyImage'] ?? ''));
$facultyImageUrl = '';

if ($facultyImage !== '' && preg_match('/^[A-Za-z0-9._-]+$/', $facultyImage) === 1) {
    $facultyImagePath = ROOT_PATH . '/public/assets/images/faculty/' . $facultyImage;
    if (is_file($facultyImagePath)) {
        $facultyImageUrl = BASE_URL . '/public/assets/images/faculty/' . rawurlencode($facultyImage);
    }
}

$hidePublicNavbar = true;
include_once(INCLUDES_PATH . '/header.php');
$facultyActivePage = 'dashboard';
include_once(__DIR__ . '/../layout/main_header.php');
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/faculty/faculty_dashboard.css">

<div class="faculty-dashboard-page">
    <?php include __DIR__ . '/sections/hero.php'; ?>
    <?php include __DIR__ . '/sections/stats.php'; ?>
    <?php include __DIR__ . '/sections/profile_and_actions.php'; ?>
    <?php include __DIR__ . '/sections/research.php'; ?>
</div>

<?php include_once(__DIR__ . '/../layout/main_footer.php'); ?>

