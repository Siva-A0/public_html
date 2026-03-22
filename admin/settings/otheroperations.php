
<?php
include_once('../layout/main_header.php');

$options = array(
    'batches' => array('title' => 'Academic Batches', 'icon' => 'bi-calendar3', 'desc' => 'Create batches like 2023, 2024, 2025 (used during student registration).'),
    'classes' => array('title' => 'Years / Semesters', 'icon' => 'bi-layers', 'desc' => 'Define the academic year/semester records used for subjects, syllabus, and resources.'),
    'sections' => array('title' => 'Sections', 'icon' => 'bi-diagram-3', 'desc' => 'Manage section mapping under each year (A/B/C) as student strength changes.'),
    'subjects' => array('title' => 'Subjects', 'icon' => 'bi-journal-bookmark', 'desc' => 'Map subjects to each year/semester for consistent resource organization.'),
    'syllabus' => array('title' => 'Syllabus', 'icon' => 'bi-file-earmark-text', 'desc' => 'Upload and maintain syllabus files for each year/semester.'),
    'materials' => array('title' => 'Notes / Materials', 'icon' => 'bi-journal-text', 'desc' => 'Upload notes and learning materials, organized by subject.'),
    'previous_papers' => array('title' => 'Previous Papers', 'icon' => 'bi-archive', 'desc' => 'Upload previous question papers, organized by subject.'),
    'events' => array('title' => 'Events', 'icon' => 'bi-calendar-event', 'desc' => 'Configure department events and registration timelines.'),
    'highlights' => array('title' => 'Highlights', 'icon' => 'bi-star', 'desc' => 'Update homepage highlight content.'),
    'support_contact' => array('title' => 'Support Contact', 'icon' => 'bi-headset', 'desc' => 'Email, WhatsApp and SMTP settings for the student support desk.')
);
?>

<style type="text/css">
    .core-settings-page {
        --cs-primary: #173d69;
        --cs-primary-deep: #13345a;
        --cs-accent: #f0b323;
        --cs-accent-soft: #fff5da;
        --cs-surface: #eef4fa;
        --cs-card: #ffffff;
        --cs-border: #d9e3ef;
        --cs-border-strong: #c8d6e6;
        --cs-text: #163a61;
        --cs-muted: #6b819c;
        --cs-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    }

    .core-settings-page .page-shell {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--cs-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .core-settings-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--cs-border);
        border-radius: 22px;
        padding: 24px 26px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--cs-surface) 100%);
        box-shadow: var(--cs-shadow);
        margin-bottom: 18px;
    }

    .core-settings-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--cs-accent), #d79a12);
    }

    .core-settings-page .page-title {
        margin: 0;
        font-size: 31px;
        letter-spacing: -0.6px;
        font-weight: 800;
        color: var(--cs-primary-deep);
    }

    .core-settings-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--cs-muted);
        font-size: 14px;
    }

    .core-settings-page .settings-list {
        display: grid;
        gap: 10px;
    }

    .core-settings-page .setting-link {
        text-decoration: none;
        color: inherit;
    }

    .core-settings-page .setting-row {
        border: 1px solid var(--cs-border);
        border-radius: 16px;
        background: var(--cs-card);
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }

    .core-settings-page .setting-row:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 24px rgba(19, 52, 90, 0.08);
        border-color: #b8ccdf;
    }

    .core-settings-page .setting-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: var(--cs-accent-soft);
        border: 1px solid #f1d78d;
        color: #8b6510;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }

    .core-settings-page .setting-title {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        color: var(--cs-primary-deep);
    }

    .core-settings-page .setting-desc {
        margin: 0;
        color: var(--cs-muted);
        font-size: 12px;
    }
</style>

<div class="container-fluid core-settings-page">
    <div class="page-shell">
        <div class="page-hero">
            <h3 class="page-title">Department Settings</h3>
            <p class="page-subtitle">Click any module to open complete data view with actions.</p>
        </div>

        <div class="settings-list">
            <?php foreach ($options as $key => $item) { ?>
                <a class="setting-link" href="department_option.php?option=<?php echo urlencode($key); ?>">
                    <div class="setting-row">
                        <span class="setting-icon">
                            <i class="bi <?php echo htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                        </span>
                        <div>
                            <h5 class="setting-title"><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            <p class="setting-desc"><?php echo htmlspecialchars($item['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

<?php include_once('../layout/footer.php'); ?>
