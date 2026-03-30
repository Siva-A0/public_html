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
$userBatchId = (int)($user['batch_id'] ?? 0);
$userClassSection = $fcObj->getClsBySec(TB_SECTION, $user['section']);

$userClassId = 0;
$userClassName = 'N/A';
if (!empty($userClassSection)) {
    $userClassId = (int)$userClassSection[0]['class_id'];
    $userClassName = $userClassSection[0]['class_name'];
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

$userSyllabusFile = !empty($userSyllabus) ? trim((string)$userSyllabus[0]['syllabus_name']) : '';
$userSyllabusPath = ROOT_PATH . '/public/uploads/syllabus/' . $userSyllabusFile;
$isValidUserSyllabus = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $userSyllabusFile) === 1;
$hasSyllabus = $userSyllabusFile !== '' && $isValidUserSyllabus && file_exists($userSyllabusPath);
$totalPaperFiles = 0;
foreach ($userPapers as $paperGroup) {
    $totalPaperFiles += count($paperGroup['papers']);
}
$totalMaterialFiles = 0;
foreach ($userMaterials as $group) {
    $totalMaterialFiles += count($group['materials']);
}

include_once(INCLUDES_PATH . '/header.php');

$userActivePage = 'downloads';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.student-page{--sp-primary:#173d69;--sp-primary-deep:#13345a;--sp-accent:#f0b323;--sp-accent-deep:#d79a12;--sp-surface:#eef4fa;--sp-card:#fff;--sp-border:#d8e3ef;--sp-text:#284767;--sp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.student-hero{position:relative;overflow:hidden;border:1px solid var(--sp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--sp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.student-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--sp-accent),var(--sp-accent-deep))}.student-kicker,.student-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--sp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.student-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--sp-accent),var(--sp-accent-deep))}.student-hero h1{margin:14px 0 10px;color:var(--sp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.student-hero p{margin:0;max-width:820px;color:var(--sp-muted);font-size:15px;line-height:1.7}.student-meta-line{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}.student-meta-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #d5e1ee;background:rgba(255,255,255,.88);color:var(--sp-text);font-size:14px;font-weight:700}.student-meta-pill strong{color:var(--sp-primary)}.student-stat-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.student-stat-card,.student-panel{border:1px solid var(--sp-border);border-radius:22px;background:var(--sp-card);box-shadow:0 12px 24px rgba(15,23,42,.06)}.student-stat-card{padding:20px}.student-panel{padding:22px}.student-stat-label{margin:0 0 8px;color:var(--sp-muted);font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.student-stat-value{margin:0;color:var(--sp-primary-deep);font-size:28px;font-weight:800;line-height:1.1}.student-stat-note{margin:8px 0 0;color:var(--sp-text);font-size:14px;line-height:1.5}.student-resource-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:20px}.student-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.student-panel-title{margin:0;color:var(--sp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.student-panel-subtitle{margin:6px 0 0;color:var(--sp-muted);font-size:14px}.student-resource-groups,.student-link-list{display:grid;gap:12px}.student-resource-group{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.student-resource-head{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:12px}.student-resource-title{margin:0;color:var(--sp-primary-deep);font-size:16px;font-weight:800}.student-resource-chip{display:inline-flex;align-items:center;padding:7px 10px;border-radius:999px;background:rgba(240,179,35,.16);color:#8a5a00;font-size:12px;font-weight:800}.student-resource-link{text-decoration:none;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 14px;border:1px solid #dbe6f1;border-radius:14px;background:#fff;color:var(--sp-text);font-weight:700;transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease}.student-resource-link:hover{transform:translateY(-2px);border-color:#b8cee4;box-shadow:0 10px 20px rgba(23,61,105,.08);color:var(--sp-primary-deep)}.student-resource-link i{color:var(--sp-primary);font-size:18px}.student-empty-note{border:1px dashed #ccd9e8;border-radius:16px;padding:16px;color:var(--sp-muted);background:#f9fbfe;font-size:14px;line-height:1.6}
@media(max-width:1199px){.student-stat-grid,.student-resource-grid{grid-template-columns:1fr}}
@media(max-width:767px){.student-page{gap:16px}.student-hero,.student-panel,.student-stat-card{padding:18px;border-radius:20px}.student-panel-header,.student-resource-head{flex-direction:column;align-items:flex-start}.student-stat-grid{grid-template-columns:1fr}}
</style>
<div class="student-page">
    <section class="student-hero">
        <span class="student-kicker">Downloads Hub</span>
        <h1>Student Downloads</h1>
        <p>Everything available for your class lives here in one place, including syllabus, materials, and previous papers, with the same cleaner dashboard layout on desktop and mobile.</p>
        <div class="student-meta-line">
            <span class="student-meta-pill"><strong>Class</strong> <?php echo htmlspecialchars($userClassName); ?></span>
            <span class="student-meta-pill"><strong>Syllabus</strong> <?php echo $hasSyllabus ? 'Available' : 'Pending'; ?></span>
            <span class="student-meta-pill"><strong>Resources</strong> <?php echo $totalMaterialFiles + $totalPaperFiles + ($hasSyllabus ? 1 : 0); ?></span>
        </div>
    </section>

    <section class="student-stat-grid">
        <article class="student-stat-card"><p class="student-stat-label">Syllabus</p><p class="student-stat-value"><?php echo $hasSyllabus ? '1' : '0'; ?></p><p class="student-stat-note">Class syllabus available for direct download.</p></article>
        <article class="student-stat-card"><p class="student-stat-label">Materials</p><p class="student-stat-value"><?php echo $totalMaterialFiles; ?></p><p class="student-stat-note"><?php echo count($userMaterials); ?> subject group(s) currently include notes or files.</p></article>
        <article class="student-stat-card"><p class="student-stat-label">Previous Papers</p><p class="student-stat-value"><?php echo $totalPaperFiles; ?></p><p class="student-stat-note"><?php echo count($userPapers); ?> subject group(s) currently include exam papers.</p></article>
    </section>

    <section class="student-panel">
        <div class="student-panel-header">
            <div><h2 class="student-panel-title">Syllabus</h2><p class="student-panel-subtitle">Official class syllabus provided for your current academic batch.</p></div>
            <span class="student-tag"><?php echo htmlspecialchars($userClassName); ?></span>
        </div>
        <?php if ($hasSyllabus) { ?>
            <div class="student-link-list">
                <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/syllabus/<?php echo rawurlencode($userSyllabusFile); ?>" target="_blank" rel="noopener noreferrer"><span>Download Syllabus</span><i class="bi bi-file-earmark-arrow-down"></i></a>
            </div>
        <?php } else { ?>
            <div class="student-empty-note">No syllabus has been uploaded for your class yet.</div>
        <?php } ?>
    </section>

    <section class="student-resource-grid">
        <div class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Notes and Materials</h2><p class="student-panel-subtitle">Study files shared subject-wise for your learning support.</p></div>
                <span class="student-tag">Materials</span>
            </div>
            <?php if (!empty($userMaterials)) { ?>
                <div class="student-resource-groups">
                    <?php foreach ($userMaterials as $group) { ?>
                        <?php $code = trim((string)($group['subject_code'] ?? '')); $name = trim((string)($group['subject_name'] ?? '')); $label = $name !== '' ? (($code !== '') ? ($code . ' - ' . $name) : $name) : $code; ?>
                        <div class="student-resource-group">
                            <div class="student-resource-head"><h3 class="student-resource-title"><?php echo htmlspecialchars($label); ?></h3><span class="student-resource-chip"><?php echo count($group['materials']); ?> file(s)</span></div>
                            <div class="student-link-list">
                                <?php foreach ($group['materials'] as $material) { ?>
                                    <?php $materialFile = trim((string)($material['mater_file'] ?? '')); $materialPath = ROOT_PATH . '/public/uploads/materials/' . $materialFile; $isValidMaterial = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $materialFile) === 1; $materialName = (string)($material['material_name'] ?? 'Material'); ?>
                                    <?php if ($materialFile !== '' && $isValidMaterial && file_exists($materialPath)) { ?>
                                        <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/materials/<?php echo rawurlencode($materialFile); ?>" target="_blank" rel="noopener noreferrer"><span><?php echo htmlspecialchars($materialName, ENT_QUOTES, 'UTF-8'); ?></span><i class="bi bi-file-earmark-arrow-down"></i></a>
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

        <div class="student-panel">
            <div class="student-panel-header">
                <div><h2 class="student-panel-title">Previous Year Papers</h2><p class="student-panel-subtitle">Question papers arranged by subject for exam practice.</p></div>
                <span class="student-tag">Papers</span>
            </div>
            <?php if (!empty($userPapers)) { ?>
                <div class="student-resource-groups">
                    <?php foreach ($userPapers as $paperGroup) { ?>
                        <?php $code = trim((string)($paperGroup['subject_code'] ?? '')); $name = trim((string)($paperGroup['subject_name'] ?? '')); $label = $name !== '' ? (($code !== '') ? ($code . ' - ' . $name) : $name) : $code; ?>
                        <div class="student-resource-group">
                            <div class="student-resource-head"><h3 class="student-resource-title"><?php echo htmlspecialchars($label); ?></h3><span class="student-resource-chip"><?php echo count($paperGroup['papers']); ?> file(s)</span></div>
                            <div class="student-link-list">
                                <?php foreach ($paperGroup['papers'] as $paper) { ?>
                                    <?php $paperFile = trim((string)$paper['paper_file']); $paperPath = ROOT_PATH . '/public/uploads/previous_papers/' . $paperFile; $isValidPaper = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $paperFile) === 1; $paperName = (string)($paper['paper_name'] ?? 'Question Paper'); ?>
                                    <?php if ($paperFile !== '' && $isValidPaper && file_exists($paperPath)) { ?>
                                        <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/previous_papers/<?php echo rawurlencode($paperFile); ?>" target="_blank" rel="noopener noreferrer"><span><?php echo htmlspecialchars($paperName, ENT_QUOTES, 'UTF-8'); ?></span><i class="bi bi-file-earmark-arrow-down"></i></a>
                                    <?php } else { ?>
                                        <div class="student-empty-note"><?php echo htmlspecialchars($paperName, ENT_QUOTES, 'UTF-8'); ?> is listed, but the file is currently unavailable.</div>
                                    <?php } ?>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="student-empty-note">No previous papers have been uploaded for your class yet.</div>
            <?php } ?>
        </div>
    </section>
</div>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>
