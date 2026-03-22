<?php
if (session_id() == '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');
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
    $facultyImagePath = ROOT_PATH . '/public/assets/images/staff/' . $facultyImage;
    if (is_file($facultyImagePath)) {
        $facultyImageUrl = BASE_URL . '/public/assets/images/staff/' . rawurlencode($facultyImage);
    }
}

$hidePublicNavbar = true;
include_once(INCLUDES_PATH . '/header.php');
$facultyActivePage = 'dashboard';
include_once(__DIR__ . '/layout/main_header.php');
?>

<style>
.faculty-dashboard-page {
    --fd-primary: #173d69;
    --fd-primary-deep: #13345a;
    --fd-accent: #f0b323;
    --fd-accent-deep: #d79a12;
    --fd-surface: #eef4fa;
    --fd-card: #ffffff;
    --fd-border: #d9e3ef;
    --fd-muted: #6b819c;
    --fd-text: #23415f;
    display: grid;
    gap: 20px;
    padding-bottom: 24px;
}

.faculty-hero {
    position: relative;
    overflow: hidden;
    border: 1px solid var(--fd-border);
    border-radius: 26px;
    padding: 28px;
    background:
        radial-gradient(circle at top right, rgba(240, 179, 35, 0.18), transparent 28%),
        linear-gradient(135deg, #f9fbfe 0%, var(--fd-surface) 100%);
    box-shadow: 0 18px 36px rgba(15, 23, 42, 0.08);
}

.faculty-hero::before {
    content: "";
    position: absolute;
    inset: 0 auto 0 0;
    width: 7px;
    background: linear-gradient(180deg, var(--fd-accent), var(--fd-accent-deep));
}

.faculty-hero-grid {
    display: grid;
    grid-template-columns: 200px minmax(0, 1fr);
    gap: 22px;
    align-items: center;
}

.faculty-portrait {
    width: 200px;
    height: 220px;
    border-radius: 24px;
    overflow: hidden;
    border: 4px solid rgba(255, 255, 255, 0.94);
    box-shadow: 0 18px 34px rgba(19, 52, 90, 0.16);
    background: linear-gradient(180deg, #d8e4f1 0%, #c4d4e6 100%);
    display: flex;
    align-items: center;
    justify-content: center;
}

.faculty-portrait img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.faculty-portrait-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--fd-primary-deep);
    font-size: 56px;
    font-weight: 800;
    letter-spacing: 0.08em;
}

.faculty-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
    padding: 8px 14px;
    border-radius: 999px;
    background: rgba(23, 61, 105, 0.08);
    color: var(--fd-primary);
    font-size: 12px;
    font-weight: 800;
    letter-spacing: 0.08em;
    text-transform: uppercase;
}

.faculty-kicker::before {
    content: "";
    width: 8px;
    height: 8px;
    border-radius: 999px;
    background: linear-gradient(135deg, var(--fd-accent), var(--fd-accent-deep));
}

.faculty-display-name {
    margin: 0;
    font-size: clamp(28px, 4vw, 42px);
    line-height: 1.05;
    font-weight: 800;
    letter-spacing: -0.04em;
    color: var(--fd-primary-deep);
    text-transform: uppercase;
}

.faculty-meta-line {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 14px;
}

.faculty-meta-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 14px;
    border-radius: 999px;
    border: 1px solid #d6e2ef;
    background: rgba(255, 255, 255, 0.84);
    color: var(--fd-text);
    font-size: 14px;
    font-weight: 700;
}

.faculty-meta-pill strong {
    color: var(--fd-primary);
}

.faculty-hero-copy {
    margin-top: 16px;
    max-width: 760px;
    color: var(--fd-muted);
    font-size: 15px;
    line-height: 1.7;
}

.faculty-stat-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 16px;
}

.faculty-stat-card {
    border: 1px solid var(--fd-border);
    border-radius: 20px;
    background: var(--fd-card);
    box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
    padding: 20px;
}

.faculty-stat-label {
    margin: 0 0 8px;
    color: var(--fd-muted);
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.faculty-stat-value {
    margin: 0;
    color: var(--fd-primary-deep);
    font-size: 28px;
    font-weight: 800;
    line-height: 1.1;
}

.faculty-stat-note {
    margin: 8px 0 0;
    color: var(--fd-text);
    font-size: 14px;
    line-height: 1.5;
}

.faculty-dashboard-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
    gap: 20px;
}

.faculty-panel {
    border: 1px solid var(--fd-border);
    border-radius: 22px;
    background: var(--fd-card);
    box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
    padding: 22px;
}

.faculty-panel-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 14px;
    margin-bottom: 18px;
}

.faculty-panel-title {
    margin: 0;
    color: var(--fd-primary-deep);
    font-size: 22px;
    font-weight: 800;
    letter-spacing: -0.03em;
}

.faculty-panel-subtitle {
    margin: 6px 0 0;
    color: var(--fd-muted);
    font-size: 14px;
}

.faculty-tag {
    display: inline-flex;
    align-items: center;
    padding: 8px 12px;
    border-radius: 999px;
    background: rgba(23, 61, 105, 0.08);
    color: var(--fd-primary);
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

.faculty-detail-list {
    display: grid;
    gap: 12px;
}

.faculty-detail-row {
    display: grid;
    grid-template-columns: minmax(150px, 220px) 1fr;
    gap: 14px;
    align-items: start;
    padding: 14px 16px;
    border: 1px solid #e2ebf5;
    border-radius: 16px;
    background: linear-gradient(180deg, #fbfdff 0%, #f6f9fc 100%);
}

.faculty-detail-label {
    color: var(--fd-primary);
    font-size: 14px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.faculty-detail-value {
    color: var(--fd-text);
    font-size: 15px;
    line-height: 1.6;
    overflow-wrap: anywhere;
}

.faculty-research-block {
    border: 1px solid #e2ebf5;
    border-radius: 18px;
    background: linear-gradient(180deg, #fbfdff 0%, #f6f9fc 100%);
    padding: 18px;
    color: var(--fd-text);
    line-height: 1.8;
    font-size: 15px;
}

.faculty-empty-note {
    border: 1px dashed #cdd9e7;
    border-radius: 16px;
    padding: 16px;
    background: #f8fbfe;
    color: var(--fd-muted);
    font-size: 14px;
    line-height: 1.6;
}

.faculty-action-stack {
    display: grid;
    gap: 12px;
}

.faculty-action-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    text-decoration: none;
    border: 1px solid #dbe6f2;
    border-radius: 18px;
    padding: 16px;
    background: linear-gradient(180deg, #ffffff 0%, #f9fbfe 100%);
    box-shadow: 0 10px 18px rgba(15, 23, 42, 0.05);
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.faculty-action-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 14px 24px rgba(15, 23, 42, 0.08);
    border-color: #c7d7e8;
}

.faculty-action-icon {
    width: 46px;
    height: 46px;
    flex: 0 0 46px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #13345a, #173d69);
    color: #ffffff;
    font-size: 20px;
    box-shadow: 0 10px 20px rgba(19, 52, 90, 0.16);
}

.faculty-action-card.accent-gold .faculty-action-icon {
    background: linear-gradient(135deg, #f0b323, #d79a12);
    color: #13345a;
}

.faculty-action-card.accent-soft .faculty-action-icon {
    background: linear-gradient(135deg, #dfeaf7, #c8d9ed);
    color: #173d69;
}

.faculty-action-title {
    margin: 0;
    color: var(--fd-primary-deep);
    font-size: 17px;
    font-weight: 800;
}

.faculty-action-text {
    margin: 6px 0 0;
    color: var(--fd-muted);
    font-size: 14px;
    line-height: 1.55;
}

@media (max-width: 1199px) {
    .faculty-stat-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .faculty-dashboard-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 767px) {
    .faculty-dashboard-page {
        gap: 16px;
    }

    .faculty-hero {
        padding: 18px;
        border-radius: 22px;
    }

    .faculty-hero-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .faculty-portrait {
        width: 160px;
        height: 180px;
        margin: 0 auto;
    }

    .faculty-display-name {
        font-size: 28px;
    }

    .faculty-meta-line {
        gap: 8px;
    }

    .faculty-meta-pill {
        width: 100%;
        justify-content: flex-start;
    }

    .faculty-stat-grid {
        grid-template-columns: 1fr;
    }

    .faculty-panel {
        padding: 18px;
        border-radius: 18px;
    }

    .faculty-panel-header {
        flex-direction: column;
        align-items: flex-start;
    }

    .faculty-detail-row {
        grid-template-columns: 1fr;
        gap: 8px;
    }
}
</style>

<div class="faculty-dashboard-page">
    <section class="faculty-hero">
        <div class="faculty-hero-grid">
            <div class="faculty-portrait">
                <?php if ($facultyImageUrl !== '') { ?>
                    <img src="<?php echo htmlspecialchars($facultyImageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($facultyDisplayName, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="faculty-portrait-fallback" style="display:none;"><?php echo htmlspecialchars(strtoupper(substr($facultyName, 0, 2)), ENT_QUOTES, 'UTF-8'); ?></div>
                <?php } else { ?>
                    <div class="faculty-portrait-fallback"><?php echo htmlspecialchars(strtoupper(substr($facultyName, 0, 2)), ENT_QUOTES, 'UTF-8'); ?></div>
                <?php } ?>
            </div>

            <div>
                <div class="faculty-kicker">Faculty Dashboard</div>
                <h1 class="faculty-display-name"><?php echo htmlspecialchars($facultyDisplayName, ENT_QUOTES, 'UTF-8'); ?></h1>
                <div class="faculty-meta-line">
                    <div class="faculty-meta-pill"><strong>Designation</strong><span><?php echo htmlspecialchars($facultyDesignation !== '' ? $facultyDesignation : 'Not available', ENT_QUOTES, 'UTF-8'); ?></span></div>
                    <div class="faculty-meta-pill"><strong>Email</strong><span><?php echo htmlspecialchars($facultyEmail !== '' ? $facultyEmail : 'Not available', ENT_QUOTES, 'UTF-8'); ?></span></div>
                </div>
                <p class="faculty-hero-copy">This workspace brings your faculty profile, experience snapshot, research notes, and quick navigation into one cleaner dashboard that also adapts better on mobile screens.</p>
            </div>
        </div>
    </section>

    <section class="faculty-stat-grid">
        <article class="faculty-stat-card">
            <p class="faculty-stat-label">Qualification</p>
            <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyQualification !== '' ? $facultyQualification : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="faculty-stat-note">Academic credentials currently attached to your faculty profile.</p>
        </article>
        <article class="faculty-stat-card">
            <p class="faculty-stat-label">Industry Experience</p>
            <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyIndustryExp !== '' ? $facultyIndustryExp : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="faculty-stat-note">Professional exposure recorded in your department profile.</p>
        </article>
        <article class="faculty-stat-card">
            <p class="faculty-stat-label">Teaching Experience</p>
            <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyTeachingExp !== '' ? $facultyTeachingExp : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="faculty-stat-note">Teaching background available to students and the department.</p>
        </article>
        <article class="faculty-stat-card">
            <p class="faculty-stat-label">Profile Status</p>
            <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyResearch !== '' ? 'Updated' : 'Basic', ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="faculty-stat-note">Add or refine research details to make your faculty profile more complete.</p>
        </article>
    </section>

    <section class="faculty-dashboard-grid">
        <section class="faculty-panel">
            <div class="faculty-panel-header">
                <div>
                    <h2 class="faculty-panel-title">Profile Snapshot</h2>
                    <p class="faculty-panel-subtitle">Core faculty details organized for quick reading on desktop and mobile.</p>
                </div>
                <div class="faculty-tag">Academic Profile</div>
            </div>

            <div class="faculty-detail-list">
                <div class="faculty-detail-row">
                    <div class="faculty-detail-label">Faculty Email</div>
                    <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyEmail !== '' ? $facultyEmail : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="faculty-detail-row">
                    <div class="faculty-detail-label">Designation</div>
                    <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyDesignation !== '' ? $facultyDesignation : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="faculty-detail-row">
                    <div class="faculty-detail-label">Qualification</div>
                    <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyQualification !== '' ? $facultyQualification : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="faculty-detail-row">
                    <div class="faculty-detail-label">Industry Experience</div>
                    <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyIndustryExp !== '' ? $facultyIndustryExp : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="faculty-detail-row">
                    <div class="faculty-detail-label">Teaching Experience</div>
                    <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyTeachingExp !== '' ? $facultyTeachingExp : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>
        </section>

        <section class="faculty-panel">
            <div class="faculty-panel-header">
                <div>
                    <h2 class="faculty-panel-title">Quick Actions</h2>
                    <p class="faculty-panel-subtitle">Fast links for the most common next steps inside the faculty flow.</p>
                </div>
                <div class="faculty-tag">Navigation</div>
            </div>

            <div class="faculty-action-stack">
                <a href="<?php echo BASE_URL; ?>/public/pages/department/department.php" class="faculty-action-card">
                    <span class="faculty-action-icon"><i class="bi bi-building"></i></span>
                    <span>
                        <span class="faculty-action-title">View Department</span>
                        <span class="faculty-action-text">Open the public department page and review faculty-facing academic content.</span>
                    </span>
                </a>
                <a href="<?php echo BASE_URL; ?>/public/pages/gallery.php" class="faculty-action-card accent-soft">
                    <span class="faculty-action-icon"><i class="bi bi-image"></i></span>
                    <span>
                        <span class="faculty-action-title">Open Gallery</span>
                        <span class="faculty-action-text">Browse department visuals, event memories, and public media in one click.</span>
                    </span>
                </a>
                <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/logout.php" class="faculty-action-card accent-gold">
                    <span class="faculty-action-icon"><i class="bi bi-box-arrow-right"></i></span>
                    <span>
                        <span class="faculty-action-title">Logout Securely</span>
                        <span class="faculty-action-text">Sign out of the faculty dashboard safely when you are done.</span>
                    </span>
                </a>
            </div>
        </section>
    </section>

    <section class="faculty-panel">
        <div class="faculty-panel-header">
            <div>
                <h2 class="faculty-panel-title">Research and Expertise</h2>
                <p class="faculty-panel-subtitle">Your research, interests, and expertise summary shown in a cleaner reading layout.</p>
            </div>
            <div class="faculty-tag">Research</div>
        </div>

        <?php if ($facultyResearch !== '') { ?>
            <div class="faculty-research-block"><?php echo nl2br(htmlspecialchars($facultyResearch, ENT_QUOTES, 'UTF-8')); ?></div>
        <?php } else { ?>
            <div class="faculty-empty-note">No research details have been added yet. This section is ready for future profile enrichment.</div>
        <?php } ?>
    </section>
</div>

<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>