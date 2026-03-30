<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../../config.php');
require_once(LIB_PATH . '/functions.class.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user' || !isset($_SESSION['userName'])) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/login.php');
    exit;
}

$fcObj = new DataFunctions();
$userData = $fcObj->userCheck(TB_USERS, $_SESSION['userName']);
if (empty($userData)) {
    header('Location: ' . BASE_URL . '/public/pages/Authentication/logout.php');
    exit;
}

$user = $userData[0];
$userFullName = trim($user['firstname'] . ' ' . $user['lastname']);
$userBatchId = (int)($user['batch_id'] ?? 0);
$userClassSection = $fcObj->getClsBySec(TB_SECTION, $user['section']);

$userClassId = 0;
$userClassName = 'N/A';
$userSectionName = 'N/A';
if (!empty($userClassSection)) {
    $userClassId = (int)$userClassSection[0]['class_id'];
    $userClassName = $userClassSection[0]['class_name'];
    $userSectionName = $userClassSection[0]['section_name'];
}

$userSyllabus = array();
$userPapers = array();
$userMaterials = array();
if ($userClassId > 0) {
    $userSyllabus = $fcObj->getSyllabusForClass(TB_SYLLABUS, $userClassId, $userBatchId);
    $subjects = $fcObj->getSubjectsForClass(TB_SUBJECTS, $userClassId, $userBatchId);
    foreach ($subjects as $subject) {
        $materials = $fcObj->getMaterialsForSubj(TB_MATERAILS, $subject['id']);
        if (!empty($materials)) {
            $userMaterials[] = array(
                'subject_code' => $subject['sub_code'],
                'subject_name' => $subject['sub_name'] ?? '',
                'materials' => $materials,
            );
        }

        $papers = $fcObj->getPrePapersForSubj(TB_PREV_PAPERS, $subject['id']);
        if (!empty($papers)) {
            $userPapers[] = array(
                'subject_code' => $subject['sub_code'],
                'subject_name' => $subject['sub_name'] ?? '',
                'papers' => $papers,
            );
        }
    }
}

$fullName = $userFullName;
$displayName = strtoupper($fullName !== '' ? $fullName : $user['username']);
$profileImage = trim((string)$user['image']);
$profileImageUrl = $profileImage !== '' ? BASE_URL . '/public/assets/images/students/' . rawurlencode($profileImage) : '';
$initials = strtoupper(substr((string)$user['firstname'], 0, 1) . substr((string)$user['lastname'], 0, 1));
$initials = $initials !== '' ? $initials : strtoupper(substr((string)$user['username'], 0, 1));

$yearDisplay = 'N/A';
if (preg_match('/\b(1st|2nd|3rd|4th|I{1,3}|IV|[1-4])\b/i', $userClassName, $yearMatch)) {
    $yearKey = strtolower($yearMatch[1]);
    $yearMap = array(
        '1st' => '1',
        '2nd' => '2',
        '3rd' => '3',
        '4th' => '4',
        '1' => '1',
        '2' => '2',
        '3' => '3',
        '4' => '4',
        'i' => '1',
        'ii' => '2',
        'iii' => '3',
        'iv' => '4',
    );
    if (isset($yearMap[$yearKey])) {
        $yearDisplay = $yearMap[$yearKey];
    }
}

$userSyllabusFile = !empty($userSyllabus) ? trim((string)$userSyllabus[0]['syllabus_name']) : '';
$userSyllabusPath = ROOT_PATH . '/public/uploads/syllabus/' . $userSyllabusFile;
$isValidUserSyllabus = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $userSyllabusFile) === 1;
$hasSyllabus = $userSyllabusFile !== '' && $isValidUserSyllabus && file_exists($userSyllabusPath);

$totalPaperFiles = 0;
foreach ($userPapers as $paperGroup) {
    $totalPaperFiles += count($paperGroup['papers']);
}

$totalMaterialFiles = 0;
foreach ($userMaterials as $materialGroup) {
    $totalMaterialFiles += count($materialGroup['materials']);
}

include_once(INCLUDES_PATH . '/header.php');
$userActivePage = 'dashboard';
include_once(__DIR__ . '/../layout/main_header.php');
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/student/student_dashboard.css">

<div class="student-dashboard-page">
    <?php include __DIR__ . '/sections/hero.php'; ?>
    <?php include __DIR__ . '/sections/stats.php'; ?>
    <?php include __DIR__ . '/sections/profile_and_actions.php'; ?>
    <?php include __DIR__ . '/sections/library_resources.php'; ?>
    <?php include __DIR__ . '/sections/resource_collections.php'; ?>
</div>

<?php include_once(__DIR__ . '/../layout/main_footer.php'); ?>


