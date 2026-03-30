<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$option = isset($_GET['option']) ? trim((string)$_GET['option']) : '';

$configs = array(
    'classes' => array(
        'title' => 'Years / Semesters',
        'desc' => 'Academic year/semester records used to map subjects, syllabus, and resources.',
        'add_url' => BASE_URL . '/admin/Class/add_class.php',
        'manage_url' => BASE_URL . '/admin/Class/classes.php'
    ),
    'sections' => array(
        'title' => 'Sections',
        'desc' => 'Sections (A/B/C) mapped under each year/semester.',
        'add_url' => BASE_URL . '/admin/Section/add_section.php',
        'manage_url' => BASE_URL . '/admin/Section/sections.php'
    ),
    'streams' => array(
        'title' => 'Streams',
        'desc' => 'Branch and stream configuration.',
        'add_url' => BASE_URL . '/admin/branch/add_branch.php',
        'manage_url' => BASE_URL . '/admin/branch/branch.php'
    ),
    'batches' => array(
        'title' => 'Academic Batches',
        'desc' => 'Academic batches used during student registration (e.g., 2023, 2024).',
        'add_url' => BASE_URL . '/admin/batches/add_batch.php',
        'manage_url' => BASE_URL . '/admin/batches/batch.php'
    ),
    'subjects' => array(
        'title' => 'Subjects',
        'desc' => 'Subjects mapped to classes.',
        'add_url' => BASE_URL . '/admin/Subject/add_subject.php',
        'manage_url' => BASE_URL . '/admin/Subject/subjects.php'
    ),
    'syllabus' => array(
        'title' => 'Syllabus',
        'desc' => 'Syllabus records with class mapping.',
        'add_url' => BASE_URL . '/admin/syllabus/add_syllabus.php',
        'manage_url' => BASE_URL . '/admin/syllabus/syllabus.php'
    ),
    'materials' => array(
        'title' => 'Notes / Materials',
        'desc' => 'Notes and materials uploaded under each subject.',
        'add_url' => BASE_URL . '/admin/Materials/add_materials.php',
        'manage_url' => BASE_URL . '/admin/Materials/materials.php'
    ),
    'previous_papers' => array(
        'title' => 'Previous Papers',
        'desc' => 'Previous question papers uploaded under each subject.',
        'add_url' => BASE_URL . '/admin/papers/add_papers.php',
        'manage_url' => BASE_URL . '/admin/papers/previouspapers.php'
    ),
    'highlights' => array(
        'title' => 'Highlights',
        'desc' => 'Homepage and department highlights.',
        'add_url' => BASE_URL . '/admin/Highlight/add_highlight.php',
        'manage_url' => BASE_URL . '/admin/Highlight/highlights.php'
    ),
    'events' => array(
        'title' => 'Events',
        'desc' => 'Event details and schedule list.',
        'add_url' => BASE_URL . '/admin/events/events.php',
        'manage_url' => BASE_URL . '/admin/events/view_events.php'
    ),
    'event_candidates' => array(
        'title' => 'Registered Candidates',
        'desc' => 'Events currently open for candidate management.',
        'add_url' => '',
        'manage_url' => BASE_URL . '/admin/events/eventregcand.php'
    ),
    'event_results' => array(
        'title' => 'Event Results',
        'desc' => 'Events with results available.',
        'add_url' => '',
        'manage_url' => BASE_URL . '/admin/events/eventresults.php'
    ),
    'support_contact' => array(
        'title' => 'Support Contact',
        'desc' => 'Support email, WhatsApp, and SMTP settings.',
        'add_url' => '',
        'manage_url' => BASE_URL . '/admin/settings/support_contact.php'
    )
);

if (!isset($configs[$option])) {
    $option = 'classes';
}

$current = $configs[$option];
$rows = array();
$headers = array();

$aimlClassCountRow = $fcObj->dbObj->getOnePrepared(
    'SELECT COUNT(*) AS cnt FROM `'.TB_CLASS.'` WHERE LOWER(class_name) LIKE :pattern',
    array(':pattern' => '%aiml%')
);
$hasAimlClasses = ((int)($aimlClassCountRow['cnt'] ?? 0)) > 0;

switch ($option) {
    case 'classes':
        $headers = array('Class Code', 'Class Name');
        $data = $fcObj->getClasses(TB_CLASS);
        foreach ($data as $row) {
            $rows[] = array($row['class_code'], $row['class_name']);
        }
        break;
    case 'sections':
        $headers = array('Batch', 'Class', 'Section Code', 'Section Name');
        $sql = 'SELECT bat.batch AS batch_name, sec.section_code, sec.section_name, cls.class_name
                FROM '.TB_SECTION.' sec
                LEFT JOIN '.TB_CLASS.' cls ON sec.class_id = cls.id
                LEFT JOIN '.TB_BATCH.' bat ON bat.id = sec.batch_id
                WHERE sec.batch_id > 0
                ORDER BY sec.id DESC';
        $data = $fcObj->dbObj->getAllResults($sql);
        foreach ($data as $row) {
            $rows[] = array($row['batch_name'], $row['class_name'], $row['section_code'], $row['section_name']);
        }
        break;
    case 'streams':
        $headers = array('Stream Code', 'Stream Name');
        $data = $fcObj->getStreams(TB_STREAM);
        foreach ($data as $row) {
            $rows[] = array($row['stream_code'], $row['stream_name']);
        }
        break;
    case 'batches':
        $headers = array('Batch');
        $data = $fcObj->getBatches(TB_BATCH);
        foreach ($data as $row) {
            $rows[] = array($row['batch']);
        }
        break;
    case 'subjects':
        $headers = array('Class', 'Subject Code', 'Subject Name');
        $sql = 'SELECT sub.sub_code, sub.sub_name, cls.class_name
                FROM '.TB_SUBJECTS.' sub
                LEFT JOIN '.TB_CLASS.' cls ON sub.class_id = cls.id
                WHERE sub.class_id > 0';
        if ($hasAimlClasses) {
            $sql .= ' AND LOWER(cls.class_name) LIKE "%aiml%"';
        }
        $sql .= ' ORDER BY sub.id DESC';
        $data = $fcObj->dbObj->getAllResults($sql);
        foreach ($data as $row) {
            $rows[] = array($row['class_name'], $row['sub_code'], $row['sub_name']);
        }
        break;
    case 'syllabus':
        $headers = array('Class', 'Syllabus Name');
        $sql = 'SELECT syl.syllabus_name, cls.class_name
                FROM '.TB_SYLLABUS.' syl
                LEFT JOIN '.TB_CLASS.' cls ON syl.class_id = cls.id
                ORDER BY syl.id DESC';
        $data = $fcObj->dbObj->getAllResults($sql);
        foreach ($data as $row) {
            $rows[] = array($row['class_name'], $row['syllabus_name']);
        }
        break;
    case 'materials':
        $headers = array('Class', 'Subject', 'Material', 'File');
        $sql = 'SELECT cls.class_name, subj.sub_code, mater.material_name, mater.mater_file
                FROM '.TB_MATERAILS.' mater
                LEFT JOIN '.TB_SUBJECTS.' subj ON subj.id = mater.sub_id
                LEFT JOIN '.TB_CLASS.' cls ON subj.class_id = cls.id
                ORDER BY mater.id DESC
                LIMIT 80';
        $data = $fcObj->dbObj->getAllResults($sql);
        foreach ($data as $row) {
            $rows[] = array($row['class_name'], $row['sub_code'], $row['material_name'], $row['mater_file']);
        }
        break;
    case 'previous_papers':
        $headers = array('Class', 'Subject', 'Paper', 'File');
        $sql = 'SELECT cls.class_name, subj.sub_code, paper.paper_name, paper.paper_file
                FROM '.TB_PREV_PAPERS.' paper
                LEFT JOIN '.TB_SUBJECTS.' subj ON subj.id = paper.subj_id
                LEFT JOIN '.TB_CLASS.' cls ON subj.class_id = cls.id
                ORDER BY paper.id DESC
                LIMIT 80';
        $data = $fcObj->dbObj->getAllResults($sql);
        foreach ($data as $row) {
            $rows[] = array($row['class_name'], $row['sub_code'], $row['paper_name'], $row['paper_file']);
        }
        break;
    case 'highlights':
        $headers = array('Type', 'Highlight');
        $sql = 'SELECT type, high_light FROM '.TB_HIGHLIGHTS.' ORDER BY id DESC';
        $data = $fcObj->dbObj->getAllResults($sql);
        foreach ($data as $row) {
            $rows[] = array((string)$row['type'], strip_tags((string)$row['high_light']));
        }
        break;
    case 'events':
        $headers = array('Event Name', 'Event Date', 'Registration');
        $data = $fcObj->getEventDetails(TB_EVENTS);
        foreach ($data as $row) {
            $regFlag = ((int)$row['is_registration'] === 1) ? 'Open' : 'Closed';
            $rows[] = array($row['event_name'], $row['event_date'], $regFlag);
        }
        break;
    case 'event_candidates':
        $headers = array('Event Name', 'Event Date', 'Registration Window');
        $data = $fcObj->getRegisteredCandidateEvents(TB_EVENTS, anu);
        foreach ($data as $row) {
            $rows[] = array($row['event_name'], $row['event_date'], $row['reg_frm_date'].' to '.$row['reg_to_date']);
        }
        break;
    case 'event_results':
        $headers = array('Event Name', 'Event Date', 'Registration Window');
        $data = $fcObj->getResultedEvents(TB_EVENTS, anu);
        foreach ($data as $row) {
            $rows[] = array($row['event_name'], $row['event_date'], $row['reg_frm_date'].' to '.$row['reg_to_date']);
        }
        break;
    case 'support_contact':
        $headers = array('Field', 'Value');
        $settings = $fcObj->getSupportSettings(TB_SUPPORT_SETTINGS);
        $rows[] = array('Support Email', (string)($settings['support_email'] ?? ''));
        $rows[] = array('WhatsApp Number', (string)($settings['whatsapp_number'] ?? ''));
        $rows[] = array('SMTP Host', (string)($settings['smtp_host'] ?? ''));
        $rows[] = array('SMTP Port', (string)($settings['smtp_port'] ?? ''));
        $rows[] = array('SMTP Security', (string)($settings['smtp_secure'] ?? ''));
        $rows[] = array('SMTP Username', (string)($settings['smtp_username'] ?? ''));
        $rows[] = array('SMTP From Email', (string)($settings['smtp_from_email'] ?? ''));
        $rows[] = array('SMTP From Name', (string)($settings['smtp_from_name'] ?? ''));
        break;
}

$totalRecords = count($rows);
?>

<div class="container-fluid dept-option-page">
    <div class="page-shell">
        <div class="page-hero">
            <h3 class="page-title"><?php echo htmlspecialchars($current['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p class="page-subtitle"><?php echo htmlspecialchars($current['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="status-grid">
            <div class="status-card">
                <span class="status-label">Total Records</span>
                <span class="status-value"><?php echo (int)$totalRecords; ?></span>
            </div>
        </div>

        <div class="data-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <?php foreach ($headers as $head) { ?>
                            <th><?php echo htmlspecialchars($head, ENT_QUOTES, 'UTF-8'); ?></th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rows)) { ?>
                        <tr>
                            <td class="empty-row" colspan="<?php echo count($headers) > 0 ? count($headers) : 1; ?>">No records found.</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($rows as $r) { ?>
                            <tr>
                                <?php foreach ($r as $cell) { ?>
                                    <td><?php echo htmlspecialchars((string)$cell, ENT_QUOTES, 'UTF-8'); ?></td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="actions">
            <a href="otheroperations.php" class="btn btn-outline-secondary btn-action">Back</a>
            <?php if ($current['add_url'] !== '') { ?>
                <a href="<?php echo htmlspecialchars($current['add_url'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-outline-primary btn-action">Add</a>
            <?php } ?>
            <a href="<?php echo htmlspecialchars($current['manage_url'], ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-primary btn-action">Manage</a>
        </div>
    </div>
</div>

<?php include_once('../layout/footer.php'); ?>

