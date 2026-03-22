<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/submission_helpers.php');

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

$certifications = $fcObj->getFacultyCertifications(TB_ACHIEVEMENTS, (string)$staffRows[0]['id']);

$hidePublicNavbar = true;
include_once(INCLUDES_PATH . '/header.php');
$facultyActivePage = 'my_certifications';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.faculty-page{--fp-primary:#173d69;--fp-primary-deep:#13345a;--fp-accent:#f0b323;--fp-accent-deep:#d79a12;--fp-surface:#eef4fa;--fp-card:#fff;--fp-border:#d8e3ef;--fp-text:#284767;--fp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}.faculty-hero{position:relative;overflow:hidden;border:1px solid var(--fp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--fp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}.faculty-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--fp-accent),var(--fp-accent-deep))}.faculty-kicker,.faculty-tag,.faculty-type-badge{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--fp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}.faculty-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--fp-accent),var(--fp-accent-deep))}.faculty-hero h1{margin:14px 0 10px;color:var(--fp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.faculty-hero p{margin:0;max-width:820px;color:var(--fp-muted);font-size:15px;line-height:1.7}.faculty-meta-line{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}.faculty-meta-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #d5e1ee;background:rgba(255,255,255,.88);color:var(--fp-text);font-size:14px;font-weight:700}.faculty-meta-pill strong{color:var(--fp-primary)}.faculty-stat-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}.faculty-stat-card,.faculty-panel,.faculty-achievement-card{border:1px solid var(--fp-border);border-radius:22px;background:var(--fp-card);box-shadow:0 12px 24px rgba(15,23,42,.06)}.faculty-stat-card{padding:20px}.faculty-panel{padding:22px}.faculty-stat-label{margin:0 0 8px;color:var(--fp-muted);font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.faculty-stat-value{margin:0;color:var(--fp-primary-deep);font-size:28px;font-weight:800;line-height:1.1}.faculty-stat-note{margin:8px 0 0;color:var(--fp-text);font-size:14px;line-height:1.5}.faculty-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.faculty-panel-title{margin:0;color:var(--fp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.faculty-panel-subtitle{margin:6px 0 0;color:var(--fp-muted);font-size:14px}.faculty-achievement-list{display:grid;gap:14px}.faculty-achievement-card{padding:18px}.faculty-achievement-top{display:flex;justify-content:space-between;gap:12px;align-items:flex-start;margin-bottom:12px}.faculty-achievement-title{margin:0;color:var(--fp-primary-deep);font-size:18px;font-weight:800;line-height:1.5}.faculty-achievement-context{margin:6px 0 0;color:var(--fp-muted);font-size:14px}.faculty-achievement-footer{display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:14px}.faculty-link-btn{display:inline-flex;align-items:center;justify-content:center;border:none;border-radius:14px;padding:11px 16px;font-weight:800;text-decoration:none;background:linear-gradient(135deg,var(--fp-primary),var(--fp-primary-deep));color:#fff;box-shadow:0 14px 24px rgba(23,61,105,.18)}.faculty-date{color:var(--fp-muted);font-size:13px;font-weight:700}.faculty-empty-note{border:1px dashed #ccd9e8;border-radius:16px;padding:16px;color:var(--fp-muted);background:#f9fbfe;font-size:14px;line-height:1.6}
@media(max-width:1199px){.faculty-stat-grid{grid-template-columns:1fr}}
@media(max-width:767px){.faculty-page{gap:16px}.faculty-hero,.faculty-panel,.faculty-stat-card,.faculty-achievement-card{padding:18px;border-radius:20px}.faculty-panel-header,.faculty-achievement-top{flex-direction:column;align-items:flex-start}}
</style>
<div class="faculty-page">
    <section class="faculty-hero">
        <span class="faculty-kicker">My Certifications</span>
        <h1>Certification History</h1>
        <p>Access every faculty certificate you have uploaded and open the supporting files from one place.</p>
        <div class="faculty-meta-line">
            <span class="faculty-meta-pill"><strong>Total</strong> <?php echo count($certifications); ?></span>
        </div>
    </section>

    <section class="faculty-stat-grid">
        <article class="faculty-stat-card"><p class="faculty-stat-label">All Certifications</p><p class="faculty-stat-value"><?php echo count($certifications); ?></p><p class="faculty-stat-note">Uploaded certifications linked to your faculty account.</p></article>
        <article class="faculty-stat-card"><p class="faculty-stat-label">Document Entries</p><p class="faculty-stat-value"><?php echo count($certifications); ?></p><p class="faculty-stat-note">Each certification is stored as a file-backed document entry.</p></article>
    </section>

    <section class="faculty-panel">
        <div class="faculty-panel-header">
            <div><h2 class="faculty-panel-title">Submitted Certifications</h2><p class="faculty-panel-subtitle">Review your certifications and open the attached proof files.</p></div>
            <a class="faculty-link-btn" href="<?php echo BASE_URL; ?>/public/pages/faculty/certifications.php">Upload New</a>
        </div>

        <?php if (empty($certifications)) { ?>
            <div class="faculty-empty-note">No certifications submitted yet. Use the upload page to add your first certification.</div>
        <?php } else { ?>
            <div class="faculty-achievement-list">
                <?php foreach ($certifications as $row) { ?>
                    <?php
                        $split = app_split_submission_file((string)($row['achievement_desc'] ?? ''));
                        $meta = app_format_submission_meta($split['text']);
                        $fileUrl = app_safe_submission_file_url($split['file']);
                        $submittedAt = $split['file'] !== '' ? app_guess_submission_time($split['file']) : '';
                    ?>
                    <article class="faculty-achievement-card">
                        <div class="faculty-achievement-top">
                            <div>
                                <h3 class="faculty-achievement-title"><?php echo htmlspecialchars($meta['text'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <?php if ($meta['context'] !== '') { ?><p class="faculty-achievement-context"><?php echo htmlspecialchars($meta['context'], ENT_QUOTES, 'UTF-8'); ?></p><?php } ?>
                            </div>
                            <span class="faculty-type-badge">Document</span>
                        </div>
                        <div class="faculty-achievement-footer">
                            <?php if ($fileUrl !== '') { ?><a class="faculty-link-btn" href="<?php echo htmlspecialchars($fileUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">Open File</a><?php } ?>
                            <span class="faculty-date"><?php echo $submittedAt !== '' ? htmlspecialchars($submittedAt, ENT_QUOTES, 'UTF-8') : 'Submission time unavailable'; ?></span>
                        </div>
                    </article>
                <?php } ?>
            </div>
        <?php } ?>
    </section>
</div>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>
