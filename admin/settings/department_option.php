<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php
include_once('../layout/main_header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$option = isset($_GET['option']) ? trim((string)$_GET['option']) : '';

$configs = array(
    'classes' => array(
        'title' => 'Years / Semesters',
        'desc' => 'Academic year/semester records used to map subjects, syllabus, and resources.',
        'add_url' => '../Class/add_class.php',
        'manage_url' => '../Class/classes.php'
    ),
    'sections' => array(
        'title' => 'Sections',
        'desc' => 'Sections (A/B/C) mapped under each year/semester.',
        'add_url' => '../Section/add_section.php',
        'manage_url' => '../Section/sections.php'
    ),
    'streams' => array(
        'title' => 'Streams',
        'desc' => 'Branch and stream configuration.',
        'add_url' => '../branch/add_branch.php',
        'manage_url' => '../branch/branch.php'
    ),
    'batches' => array(
        'title' => 'Academic Batches',
        'desc' => 'Academic batches used during student registration (e.g., 2023, 2024).',
        'add_url' => '../batches/add_batch.php',
        'manage_url' => '../batches/batch.php'
    ),
    'subjects' => array(
        'title' => 'Subjects',
        'desc' => 'Subjects mapped to classes.',
        'add_url' => '../Subject/add_subject.php',
        'manage_url' => '../Subject/subjects.php'
    ),
    'syllabus' => array(
        'title' => 'Syllabus',
        'desc' => 'Syllabus records with class mapping.',
        'add_url' => '../syllabus/add_syllabus.php',
        'manage_url' => '../syllabus/syllabus.php'
    ),
    'materials' => array(
        'title' => 'Notes / Materials',
        'desc' => 'Notes and materials uploaded under each subject.',
        'add_url' => '../Materials/add_materials.php',
        'manage_url' => '../Materials/materials.php'
    ),
    'previous_papers' => array(
        'title' => 'Previous Papers',
        'desc' => 'Previous question papers uploaded under each subject.',
        'add_url' => '../papers/add_papers.php',
        'manage_url' => '../papers/previouspapers.php'
    ),
    'highlights' => array(
        'title' => 'Highlights',
        'desc' => 'Homepage and department highlights.',
        'add_url' => '../Highlight/add_highlight.php',
        'manage_url' => '../Highlight/highlights.php'
    ),
    'events' => array(
        'title' => 'Events',
        'desc' => 'Event details and schedule list.',
        'add_url' => '../events/events.php',
        'manage_url' => '../events/view_events.php'
    ),
    'event_candidates' => array(
        'title' => 'Registered Candidates',
        'desc' => 'Events currently open for candidate management.',
        'add_url' => '',
        'manage_url' => '../events/eventregcand.php'
    ),
    'event_results' => array(
        'title' => 'Event Results',
        'desc' => 'Events with results available.',
        'add_url' => '',
        'manage_url' => '../events/eventresults.php'
    ),
    'support_contact' => array(
        'title' => 'Support Contact',
        'desc' => 'Support email, WhatsApp, and SMTP settings.',
        'add_url' => '',
        'manage_url' => 'support_contact.php'
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

<style type="text/css">
    .dept-option-page {
        --dept-primary: #173d69;
        --dept-primary-deep: #13345a;
        --dept-accent: #f0b323;
        --dept-accent-deep: #d79a12;
        --dept-accent-soft: #fff5da;
        --dept-surface: #eef4fa;
        --dept-card: #ffffff;
        --dept-border: #d9e3ef;
        --dept-border-strong: #c8d6e6;
        --dept-text: #163a61;
        --dept-muted: #6b819c;
    }

    .dept-option-page .page-shell {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--dept-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .dept-option-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--dept-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--dept-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

    .dept-option-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--dept-accent), var(--dept-accent-deep));
    }

    .dept-option-page .page-title {
        margin: 0;
        font-size: 30px;
        letter-spacing: -0.6px;
        font-weight: 800;
        color: var(--dept-primary-deep);
    }

    .dept-option-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--dept-muted);
        font-size: 14px;
    }

    .dept-option-page .data-wrap {
        border: 1px solid var(--dept-border);
        border-radius: 16px;
        overflow: auto;
        background: var(--dept-card);
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
    }

    .dept-option-page .status-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 8px;
        margin-bottom: 12px;
    }

    .dept-option-page .status-card {
        border: 1px solid var(--dept-border);
        border-radius: 10px;
        background: #fff;
        padding: 10px 12px;
    }

    .dept-option-page .status-label {
        display: block;
        color: var(--dept-muted);
        font-size: 12px;
        margin-bottom: 3px;
    }

    .dept-option-page .status-value {
        display: block;
        font-weight: 800;
        color: var(--dept-primary-deep);
        font-size: 16px;
    }

    .dept-option-page .data-table {
        width: 100%;
        min-width: 680px;
        border-collapse: collapse;
    }

    .dept-option-page .data-table th {
        background: linear-gradient(180deg, #f7f9fc 0%, #f1f5fa 100%);
        color: var(--dept-primary);
        text-align: left;
        padding: 10px 12px;
        border-bottom: 1px solid var(--dept-border);
        font-size: 13px;
        font-weight: 700;
    }

    .dept-option-page .data-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #edf2fa;
        color: #334e6f;
        font-size: 13px;
        vertical-align: top;
    }

    .dept-option-page .data-table tr:last-child td {
        border-bottom: 0;
    }

    .dept-option-page .empty-row {
        text-align: center;
        color: var(--dept-muted);
        padding: 24px !important;
    }

    .dept-option-page .actions {
        margin-top: 14px;
        border: 1px solid var(--dept-border);
        border-radius: 12px;
        background: #fbfdff;
        padding: 12px;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .dept-option-page .manage-note {
        margin-right: auto;
        color: var(--dept-primary);
        font-size: 12px;
        font-weight: 700;
        align-self: center;
    }

    .dept-option-page .btn-action {
        border-radius: 10px;
        padding: 9px 14px;
        font-weight: 700;
    }
</style>

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
