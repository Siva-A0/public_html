
<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

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

<div class="container-fluid core-settings-page">
    <div class="page-shell">
        <div class="page-hero">
            <h3 class="page-title">Department Settings</h3>
            <!-- <p class="page-subtitle">Click any module to open complete data view with actions.</p> -->
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

