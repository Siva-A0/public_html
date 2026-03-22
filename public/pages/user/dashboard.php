<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
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
                'materials' => $materials
            );
        }

        $papers = $fcObj->getPrePapersForSubj(TB_PREV_PAPERS, $subject['id']);
        if (!empty($papers)) {
            $userPapers[] = array(
                'subject_code' => $subject['sub_code'],
                'subject_name' => $subject['sub_name'] ?? '',
                'papers' => $papers
            );
        }
    }
}

$fullName = $userFullName;
$displayName = strtoupper($fullName !== '' ? $fullName : $user['username']);
$profileImage = trim((string)$user['image']);
$profileImageUrl = $profileImage !== '' ? BASE_URL . '/public/assets/images/users/' . rawurlencode($profileImage) : '';
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
        'iv' => '4'
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
include_once(__DIR__ . '/layout/main_header.php');
?>

<style>
.student-dashboard-page {
    --sd-primary: #173d69;
    --sd-primary-deep: #13345a;
    --sd-accent: #f0b323;
    --sd-accent-deep: #d79a12;
    --sd-surface: #eef4fa;
    --sd-card: #ffffff;
    --sd-border: #d8e3ef;
    --sd-text: #284767;
    --sd-muted: #6b819c;
    display: grid;
    gap: 20px;
    padding-bottom: 28px;
}

.student-hero {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--sd-border);
    border-radius: 26px;
    padding: 28px;
    background:
        radial-gradient(circle at top right, rgba(240, 179, 35, 0.18), transparent 30%),
        linear-gradient(135deg, #f9fbfe 0%, var(--sd-surface) 100%);
    box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
}

.student-hero::before {
    content: "";
    position: absolute;
    inset: 0 auto 0 0;
    width: 7px;
    background: linear-gradient(180deg, var(--sd-accent), var(--sd-accent-deep));
}

.student-hero-grid {
    display: grid;
    grid-template-columns: 180px minmax(0, 1fr);
    gap: 24px;
    align-items: center;
}

.student-photo-shell {
    width: 180px;
    height: 210px;
    border-radius: 24px;
    overflow: hidden;
    border: 4px solid rgba(255, 255, 255, 0.95);
    background: linear-gradient(180deg, #dae6f3 0%, #c4d5e7 100%);
    box-shadow: 0 18px 34px rgba(19, 52, 90, 0.16);
    display: flex;
    align-items: center;
    justify-content: center;
}

.student-photo-shell img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.student-photo-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--sd-primary-deep);
    font-size: 56px;
    font-weight: 800;
    letter-spacing: 0.08em;
}

.student-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(23, 61, 105, 0.08);
    color: var(--sd-primary);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.student-kicker::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent-deep));
}

.student-display-name {
    margin: 0;
    color: var(--sd-primary-deep);
    font-size: clamp(28px, 4vw, 42px);
    font-weight: 800;
    line-height: 1.04;
    letter-spacing: -0.04em;
    text-transform: uppercase;
}

.student-meta-line {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 14px;
}

.student-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 999px;
    border: 1px solid #d5e1ee;
    background: rgba(255, 255, 255, 0.88);
    color: var(--sd-text);
    font-size: 14px;
    font-weight: 700;
}

.student-meta-pill strong {
    color: var(--sd-primary);
}

.student-hero-copy {
    margin: 16px 0 0;
    max-width: 820px;
    color: var(--sd-muted);
    font-size: 15px;
    line-height: 1.7;
}

.student-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.student-stat-card {
    border: 1px solid var(--sd-border);
    border-radius: 20px;
    background: var(--sd-card);
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
    padding: 20px;
}

.student-stat-label {
    margin: 0 0 8px;
    color: var(--sd-muted);
    font-size: 13px;
    font-weight: 800;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.student-stat-value {
    margin: 0;
    color: var(--sd-primary-deep);
    font-size: 28px;
    font-weight: 800;
    line-height: 1.1;
}

.student-stat-note {
    margin: 8px 0 0;
    color: var(--sd-text);
    font-size: 14px;
    line-height: 1.5;
}

.student-dashboard-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
    gap: 20px;
}

.student-panel {
    border: 1px solid var(--sd-border);
    border-radius: 22px;
    background: var(--sd-card);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
    padding: 22px;
}

.student-panel-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 18px;
}

.student-panel-title {
    margin: 0;
    color: var(--sd-primary-deep);
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.student-panel-subtitle {
    margin: 6px 0 0;
    color: var(--sd-muted);
    font-size: 14px;
}

.student-tag {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(23, 61, 105, 0.08);
    color: var(--sd-primary);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.student-detail-list,
.student-resource-groups,
.student-action-grid {
    display: grid;
    gap: 12px;
}

.student-detail-row,
.student-resource-group,
.student-action-card {
    border: 1px solid #e2ebf5;
    border-radius: 16px;
    background: linear-gradient(180deg, #fbfdff 0%, #f6f9fc 100%);
}

.student-detail-row {
    display: grid;
    grid-template-columns: minmax(150px, 220px) 1fr;
    gap: 14px;
    align-items: start;
    padding: 14px 16px;
}

.student-detail-label {
    color: var(--sd-primary);
    font-size: 14px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.student-detail-value {
    color: var(--sd-text);
    font-size: 15px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.student-resource-group {
    padding: 16px;
}

.student-resource-head {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    align-items: center;
    margin-bottom: 12px;
}

.student-resource-title {
    margin: 0;
    color: var(--sd-primary-deep);
    font-size: 16px;
    font-weight: 800;
}

.student-resource-chip {
    display: inline-flex;
    align-items: center;
    padding: 7px 10px;
    border-radius: 999px;
    background: rgba(240, 179, 35, 0.16);
    color: #8a5a00;
    font-size: 12px;
    font-weight: 800;
}

.student-link-list {
    display: grid;
    gap: 10px;
}

.student-resource-link,
.student-action-card {
    text-decoration: none;
}

.student-resource-link {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 12px 14px;
    border: 1px solid #dbe6f1;
    border-radius: 14px;
    background: #ffffff;
    color: var(--sd-text);
    font-weight: 700;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.student-resource-link:hover {
    transform: translateY(-2px);
    border-color: #b8cee4;
    box-shadow: 0 10px 20px rgba(23, 61, 105, 0.08);
    color: var(--sd-primary-deep);
}

.student-resource-link i {
    color: var(--sd-primary);
    font-size: 18px;
}

.student-empty-note {
    border: 1px dashed #ccd9e8;
    border-radius: 16px;
    padding: 16px;
    color: var(--sd-muted);
    background: #f9fbfe;
    font-size: 14px;
    line-height: 1.6;
}

.student-action-grid {
    grid-template-columns: 1fr;
}

.student-action-card {
    display: grid;
    grid-template-columns: 54px minmax(0, 1fr);
    gap: 14px;
    align-items: start;
    padding: 16px;
    color: inherit;
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
}

.student-action-card:hover {
    transform: translateY(-2px);
    border-color: #c2d5e8;
    box-shadow: 0 10px 18px rgba(23, 61, 105, 0.08);
}

.student-action-icon {
    width: 54px;
    height: 54px;
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--sd-primary), var(--sd-primary-deep));
    color: #ffffff;
    font-size: 22px;
    box-shadow: 0 12px 24px rgba(23, 61, 105, 0.22);
}

.student-action-card.accent-gold .student-action-icon {
    background: linear-gradient(135deg, var(--sd-accent), var(--sd-accent-deep));
    color: #4d3700;
    box-shadow: 0 12px 24px rgba(240, 179, 35, 0.24);
}

.student-action-card.accent-soft .student-action-icon {
    background: linear-gradient(135deg, #d7e5f5, #bdd2e8);
    color: var(--sd-primary);
    box-shadow: 0 10px 20px rgba(23, 61, 105, 0.12);
}

.student-action-title {
    margin: 0;
    color: var(--sd-primary-deep);
    font-size: 17px;
    font-weight: 800;
}

.student-action-copy {
    margin: 4px 0 0;
    color: var(--sd-muted);
    font-size: 14px;
    line-height: 1.6;
}

@media (max-width: 1199px) {
    .student-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .student-dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .student-dashboard-page {
        gap: 16px;
    }

    .student-hero,
    .student-panel,
    .student-stat-card {
        padding: 18px;
        border-radius: 20px;
    }

    .student-hero-grid {
        grid-template-columns: 1fr;
        text-align: center;
    }

    .student-photo-shell {
        margin: 0 auto;
        width: 160px;
        height: 186px;
    }

    .student-meta-line {
        justify-content: center;
    }

    .student-hero-copy {
        margin-left: auto;
        margin-right: auto;
    }

    .student-stat-grid {
        grid-template-columns: 1fr;
    }

    .student-detail-row {
        grid-template-columns: 1fr;
        gap: 6px;
    }

    .student-panel-header,
    .student-resource-head {
        flex-direction: column;
        align-items: flex-start;
    }

    .student-resource-link {
        align-items: flex-start;
    }
}
</style>

<div class="student-dashboard-page">
    <section class="student-hero">
        <div class="student-hero-grid">
            <div class="student-photo-shell">
                <?php if ($profileImageUrl !== '') { ?>
                    <img src="<?php echo htmlspecialchars($profileImageUrl); ?>" alt="<?php echo htmlspecialchars($displayName); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="student-photo-fallback" style="display:none;"><?php echo htmlspecialchars($initials); ?></div>
                <?php } else { ?>
                    <div class="student-photo-fallback"><?php echo htmlspecialchars($initials); ?></div>
                <?php } ?>
            </div>

            <div>
                <div class="student-kicker">Student Dashboard</div>
                <h1 class="student-display-name"><?php echo htmlspecialchars($displayName); ?></h1>

                <div class="student-meta-line">
                    <span class="student-meta-pill"><strong>Roll</strong> <?php echo htmlspecialchars((string)$user['admission_id']); ?></span>
                    <span class="student-meta-pill"><strong>Class</strong> <?php echo htmlspecialchars($userClassName); ?></span>
                    <span class="student-meta-pill"><strong>Section</strong> <?php echo htmlspecialchars($userSectionName); ?></span>
                </div>

                <p class="student-hero-copy">
                    Your student workspace brings class resources, previous papers, notes, and support links into one cleaner dashboard that also adapts smoothly on mobile screens.
                </p>
            </div>
        </div>
    </section>

    <section class="student-stat-grid">
        <article class="student-stat-card">
            <p class="student-stat-label">Academic Year</p>
            <p class="student-stat-value"><?php echo htmlspecialchars($yearDisplay); ?></p>
            <p class="student-stat-note">Current year detected from your class and section mapping.</p>
        </article>

        <article class="student-stat-card">
            <p class="student-stat-label">Syllabus Status</p>
            <p class="student-stat-value"><?php echo $hasSyllabus ? 'Ready' : 'Pending'; ?></p>
            <p class="student-stat-note"><?php echo $hasSyllabus ? 'Your class syllabus is available to open and download.' : 'No syllabus file has been uploaded for your class yet.'; ?></p>
        </article>

        <article class="student-stat-card">
            <p class="student-stat-label">Previous Papers</p>
            <p class="student-stat-value"><?php echo (int)$totalPaperFiles; ?></p>
            <p class="student-stat-note"><?php echo count($userPapers); ?> subject group(s) currently include exam paper resources.</p>
        </article>

        <article class="student-stat-card">
            <p class="student-stat-label">Study Materials</p>
            <p class="student-stat-value"><?php echo (int)$totalMaterialFiles; ?></p>
            <p class="student-stat-note"><?php echo count($userMaterials); ?> subject group(s) currently include learning materials.</p>
        </article>
    </section>

    <section class="student-dashboard-grid">
        <div class="student-panel">
            <div class="student-panel-header">
                <div>
                    <h2 class="student-panel-title">Student Profile Snapshot</h2>
                    <p class="student-panel-subtitle">Core academic details connected to your account and class allocation.</p>
                </div>
                <span class="student-tag">Profile</span>
            </div>

            <div class="student-detail-list">
                <div class="student-detail-row">
                    <div class="student-detail-label">Student Name</div>
                    <div class="student-detail-value"><?php echo htmlspecialchars($fullName !== '' ? $fullName : (string)$user['username']); ?></div>
                </div>
                <div class="student-detail-row">
                    <div class="student-detail-label">Admission / Roll Number</div>
                    <div class="student-detail-value"><?php echo htmlspecialchars((string)$user['admission_id']); ?></div>
                </div>
                <div class="student-detail-row">
                    <div class="student-detail-label">Class and Section</div>
                    <div class="student-detail-value"><?php echo htmlspecialchars($userClassName . ' - ' . $userSectionName); ?></div>
                </div>
                <div class="student-detail-row">
                    <div class="student-detail-label">Mobile Number</div>
                    <div class="student-detail-value"><?php echo htmlspecialchars((string)$user['mobile_no']); ?></div>
                </div>
            </div>
        </div>

        <aside class="student-panel">
            <div class="student-panel-header">
                <div>
                    <h2 class="student-panel-title">Quick Access</h2>
                    <p class="student-panel-subtitle">Jump straight to the student tools you use most often.</p>
                </div>
                <span class="student-tag">Actions</span>
            </div>

            <div class="student-action-grid">
                <a class="student-action-card" href="<?php echo BASE_URL; ?>/public/pages/user/academics.php">
                    <span class="student-action-icon"><i class="bi bi-journal-bookmark"></i></span>
                    <span>
                        <span class="student-action-title">Open Academics</span>
                        <span class="student-action-copy">Review classroom information, academic links, and subject-facing resources.</span>
                    </span>
                </a>

                <a class="student-action-card accent-soft" href="<?php echo BASE_URL; ?>/public/pages/user/downloads.php">
                    <span class="student-action-icon"><i class="bi bi-cloud-arrow-down"></i></span>
                    <span>
                        <span class="student-action-title">Downloads</span>
                        <span class="student-action-copy">Access files, shared resources, and downloadable academic content in one place.</span>
                    </span>
                </a>

                <a class="student-action-card accent-gold" href="<?php echo BASE_URL; ?>/public/pages/user/studentsupport.php">
                    <span class="student-action-icon"><i class="bi bi-headset"></i></span>
                    <span>
                        <span class="student-action-title">Student Support</span>
                        <span class="student-action-copy">Reach support channels quickly whenever you need help from the portal team.</span>
                    </span>
                </a>
            </div>
        </aside>
    </section>

    <section class="student-panel" id="syllabus-section">
        <div class="student-panel-header">
            <div>
                <h2 class="student-panel-title">Library Resources</h2>
                <p class="student-panel-subtitle">Class-level documents and syllabus materials prepared for your current academic track.</p>
            </div>
            <span class="student-tag"><?php echo htmlspecialchars($userClassName); ?></span>
        </div>

        <?php if ($hasSyllabus) { ?>
            <div class="student-link-list">
                <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/syllabus/<?php echo rawurlencode($userSyllabusFile); ?>" target="_blank" rel="noopener noreferrer">
                    <span>Download Syllabus</span>
                    <i class="bi bi-arrow-up-right-circle"></i>
                </a>
            </div>
        <?php } else { ?>
            <div class="student-empty-note">No syllabus has been uploaded for your class yet. Once the academic team adds it, it will appear here.</div>
        <?php } ?>
    </section>

    <section class="student-dashboard-grid">
        <div class="student-panel">
            <div class="student-panel-header">
                <div>
                    <h2 class="student-panel-title">Previous Year Papers</h2>
                    <p class="student-panel-subtitle">Browse subject-wise question papers available for revision and exam preparation.</p>
                </div>
                <span class="student-tag">Papers</span>
            </div>

            <?php if (!empty($userPapers)) { ?>
                <div class="student-resource-groups">
                    <?php foreach ($userPapers as $paperGroup) { ?>
                        <?php
                            $code = trim((string)($paperGroup['subject_code'] ?? ''));
                            $name = trim((string)($paperGroup['subject_name'] ?? ''));
                            $label = $code;
                            if ($name !== '') {
                                $label = ($code !== '') ? ($code . ' - ' . $name) : $name;
                            }
                        ?>
                        <div class="student-resource-group">
                            <div class="student-resource-head">
                                <h3 class="student-resource-title"><?php echo htmlspecialchars($label); ?></h3>
                                <span class="student-resource-chip"><?php echo count($paperGroup['papers']); ?> file(s)</span>
                            </div>
                            <div class="student-link-list">
                                <?php foreach ($paperGroup['papers'] as $paper) { ?>
                                    <?php
                                        $paperFile = trim((string)$paper['paper_file']);
                                        $paperPath = ROOT_PATH . '/public/uploads/previous_papers/' . $paperFile;
                                        $isValidPaper = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $paperFile) === 1;
                                        $paperName = (string)($paper['paper_name'] ?? 'Question Paper');
                                    ?>
                                    <?php if ($paperFile !== '' && $isValidPaper && file_exists($paperPath)) { ?>
                                        <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/previous_papers/<?php echo rawurlencode($paperFile); ?>" target="_blank" rel="noopener noreferrer">
                                            <span><?php echo htmlspecialchars($paperName, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                        </a>
                                    <?php } else { ?>
                                        <div class="student-empty-note"><?php echo htmlspecialchars($paperName, ENT_QUOTES, 'UTF-8'); ?> is listed, but the file is currently unavailable.</div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="student-empty-note">No previous year papers have been uploaded for your class yet.</div>
            <?php } ?>
        </div>

        <div class="student-panel">
            <div class="student-panel-header">
                <div>
                    <h2 class="student-panel-title">Notes and Materials</h2>
                    <p class="student-panel-subtitle">Subject-wise notes, files, and study references shared for your classroom learning.</p>
                </div>
                <span class="student-tag">Materials</span>
            </div>

            <?php if (!empty($userMaterials)) { ?>
                <div class="student-resource-groups">
                    <?php foreach ($userMaterials as $group) { ?>
                        <?php
                            $code = trim((string)($group['subject_code'] ?? ''));
                            $name = trim((string)($group['subject_name'] ?? ''));
                            $label = $code;
                            if ($name !== '') {
                                $label = ($code !== '') ? ($code . ' - ' . $name) : $name;
                            }
                        ?>
                        <div class="student-resource-group">
                            <div class="student-resource-head">
                                <h3 class="student-resource-title"><?php echo htmlspecialchars($label); ?></h3>
                                <span class="student-resource-chip"><?php echo count($group['materials']); ?> file(s)</span>
                            </div>
                            <div class="student-link-list">
                                <?php foreach ($group['materials'] as $material) { ?>
                                    <?php
                                        $materialFile = trim((string)($material['mater_file'] ?? ''));
                                        $materialPath = ROOT_PATH . '/public/uploads/materials/' . $materialFile;
                                        $isValidMaterial = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $materialFile) === 1;
                                        $materialName = (string)($material['material_name'] ?? 'Material');
                                    ?>
                                    <?php if ($materialFile !== '' && $isValidMaterial && file_exists($materialPath)) { ?>
                                        <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/materials/<?php echo rawurlencode($materialFile); ?>" target="_blank" rel="noopener noreferrer">
                                            <span><?php echo htmlspecialchars($materialName, ENT_QUOTES, 'UTF-8'); ?></span>
                                            <i class="bi bi-file-earmark-arrow-down"></i>
                                        </a>
                                    <?php } else { ?>
                                        <div class="student-empty-note"><?php echo htmlspecialchars($materialName, ENT_QUOTES, 'UTF-8'); ?> is listed, but the file is currently unavailable.</div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="student-empty-note">No materials have been uploaded for your class yet.</div>
            <?php } ?>
        </div>
    </section>
</div>

<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>
