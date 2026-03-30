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
$userClassSection = $fcObj->getClsBySec(TB_SECTION, $user['section']);
$userClassName = !empty($userClassSection) ? (string)$userClassSection[0]['class_name'] : '';

$userYear = '3';
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
        $userYear = $yearMap[$yearKey];
    }
}

$academicCalendars = array(
    '1' => array(
        'year_label' => 'B.Tech I Year',
        'sem_1' => array(
            array('1', 'Induction Programme', '11.08.2025', '18.08.2025', '1'),
            array('2', '1st Spell of Instructions (Including Dussehra Recess)', '19.08.2025', '18.10.2025', '9'),
            array('3', 'First Mid Term Examinations', '21.10.2025', '27.10.2025', '1'),
            array('4', '2nd Spell of Instructions', '28.10.2025', '15.12.2025', '7'),
            array('5', 'Second Mid Term Examinations', '16.12.2025', '20.12.2025', '1'),
            array('6', 'End Semester Examinations', '22.12.2025', '03.01.2026', '2'),
            array('7', 'Lab Examinations', '05.01.2026', '09.01.2026', '1')
        ),
        'sem_2' => array(
            array('1', 'Commencement of II Semester class work', '12.01.2026', '-', '-'),
            array('2', '1st Spell of Instructions', '12.01.2026', '07.03.2026', '8'),
            array('3', 'First Mid Term Examinations', '09.03.2026', '14.03.2026', '1'),
            array('4', '2nd Spell of Instructions', '16.03.2026', '08.05.2026', '8'),
            array('5', 'Summer Vacation', '09.05.2026', '31.05.2026', '3'),
            array('6', 'Second Mid Term Examinations', '01.06.2026', '06.06.2026', '1'),
            array('7', 'End Semester Examinations', '08.06.2026', '20.06.2026', '2'),
            array('8', 'Lab Examinations', '22.06.2026', '27.06.2026', '1')
        )
    ),
    '2' => array(
        'year_label' => 'B.Tech II Year',
        'sem_1' => array(
            array('1', 'Commencement of I Semester class work', '23.07.2025', '-', '-'),
            array('2', '1st Spell of Instructions', '23.07.2025', '16.09.2025', '8'),
            array('3', 'First Mid Term Examinations', '17.09.2025', '23.09.2025', '1'),
            array('4', '2nd Spell of Instructions (Including Dussehra Recess)', '24.09.2025', '18.11.2025', '8'),
            array('5', 'Dussehra Recess', '29.09.2025', '04.10.2025', '1'),
            array('6', 'Second Mid Term Examinations', '19.11.2025', '25.11.2025', '1'),
            array('7', 'Preparation Holiday & Lab Examinations', '26.11.2025', '02.12.2025', '1'),
            array('8', 'End Semester Examinations', '03.12.2025', '16.12.2025', '2')
        ),
        'sem_2' => array(
            array('1', 'Commencement of II Semester class work', '19.12.2025', '-', '-'),
            array('2', '1st Spell of Instructions', '19.12.2025', '12.02.2026', '8'),
            array('3', 'First Mid Term Examinations', '13.02.2026', '19.02.2026', '1'),
            array('4', '2nd Spell of Instructions', '20.02.2026', '11.04.2026', '7'),
            array('5', 'Second Mid Term Examinations', '13.04.2026', '18.04.2026', '1'),
            array('6', 'End Semester Examinations', '20.04.2026', '02.05.2026', '2'),
            array('7', 'Lab Examinations', '04.05.2026', '09.05.2026', '1')
        )
    ),
    '3' => array(
        'year_label' => 'B.Tech III Year',
        'sem_1' => array(
            array('1', 'Commencement of I Semester class work', '30.06.2025', '-', '-'),
            array('2', '1st Spell of Instructions', '30.06.2025', '30.08.2025', '9'),
            array('3', 'First Mid Term Examinations', '01.09.2025', '06.09.2025', '1'),
            array('4', '2nd Spell of Instructions [Including Dussehra Recess]', '08.09.2025', '18.11.2025', '9'),
            array('5', 'Dussehra Recess', '29.09.2025', '04.10.2025', '1'),
            array('6', 'Second Mid Term Examinations', '10.11.2025', '15.11.2025', '1'),
            array('7', 'End Semester Examinations', '17.11.2025', '29.11.2025', '2'),
            array('8', 'Lab Examinations', '01.12.2025', '06.12.2025', '1')
        ),
        'sem_2' => array(
            array('1', 'Commencement of II Semester class work', '08.12.2025', '-', '-'),
            array('2', '1st Spell of Instructions', '08.12.2025', '07.02.2026', '9'),
            array('3', 'First Mid Term Examinations', '09.02.2026', '14.02.2026', '1'),
            array('4', '2nd Spell of Instructions', '16.02.2026', '11.04.2026', '8'),
            array('5', 'Second Mid Term Examinations', '13.04.2026', '18.04.2026', '1'),
            array('6', 'End Semester Examinations', '20.04.2026', '02.05.2026', '2'),
            array('7', 'Lab Examinations', '04.05.2026', '09.05.2026', '1')
        )
    )
);

$calendarYear = isset($academicCalendars[$userYear]) ? $userYear : '3';
$calendar = $academicCalendars[$calendarYear];
$semesterOneCount = count($calendar['sem_1']);
$semesterTwoCount = count($calendar['sem_2']);
$totalAcademicRows = $semesterOneCount + $semesterTwoCount;

include_once(INCLUDES_PATH . '/header.php');

$userActivePage = 'academics';
include_once(__DIR__ . '/layout/main_header.php');
?>
<style>
.student-page{--sp-primary:#173d69;--sp-primary-deep:#13345a;--sp-accent:#f0b323;--sp-accent-deep:#d79a12;--sp-surface:#eef4fa;--sp-card:#fff;--sp-border:#d8e3ef;--sp-text:#284767;--sp-muted:#6b819c;display:grid;gap:20px;padding-bottom:28px}
.student-hero{position:relative;overflow:hidden;border:1px solid var(--sp-border);border-radius:26px;padding:28px;background:radial-gradient(circle at top right,rgba(240,179,35,.18),transparent 30%),linear-gradient(135deg,#f9fbfe 0%,var(--sp-surface) 100%);box-shadow:0 18px 36px rgba(15,23,42,.08)}
.student-hero:before{content:"";position:absolute;inset:0 auto 0 0;width:7px;background:linear-gradient(180deg,var(--sp-accent),var(--sp-accent-deep))}
.student-kicker,.student-tag{display:inline-flex;align-items:center;gap:8px;padding:8px 14px;border-radius:999px;background:rgba(23,61,105,.08);color:var(--sp-primary);font-size:12px;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
.student-kicker:before{content:"";width:8px;height:8px;border-radius:999px;background:linear-gradient(135deg,var(--sp-accent),var(--sp-accent-deep))}
.student-hero h1{margin:14px 0 10px;color:var(--sp-primary-deep);font-size:clamp(28px,4vw,42px);font-weight:800;line-height:1.04;letter-spacing:-.04em}.student-hero p{margin:0;max-width:860px;color:var(--sp-muted);font-size:15px;line-height:1.7}
.student-meta-line{display:flex;flex-wrap:wrap;gap:10px;margin-top:16px}.student-meta-pill{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border-radius:999px;border:1px solid #d5e1ee;background:rgba(255,255,255,.88);color:var(--sp-text);font-size:14px;font-weight:700}.student-meta-pill strong{color:var(--sp-primary)}
.student-stat-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.student-stat-card,.student-panel{border:1px solid var(--sp-border);border-radius:22px;background:var(--sp-card);box-shadow:0 12px 24px rgba(15,23,42,.06)}.student-stat-card{padding:20px}.student-panel{padding:22px}.student-stat-label{margin:0 0 8px;color:var(--sp-muted);font-size:13px;font-weight:800;letter-spacing:.06em;text-transform:uppercase}.student-stat-value{margin:0;color:var(--sp-primary-deep);font-size:28px;font-weight:800;line-height:1.1}.student-stat-note{margin:8px 0 0;color:var(--sp-text);font-size:14px;line-height:1.5}
.student-panel-header{display:flex;align-items:flex-start;justify-content:space-between;gap:14px;margin-bottom:18px}.student-panel-title{margin:0;color:var(--sp-primary-deep);font-size:22px;font-weight:800;letter-spacing:-.03em}.student-panel-subtitle{margin:6px 0 0;color:var(--sp-muted);font-size:14px}
.student-calendar-table{width:100%;border-collapse:separate;border-spacing:0 10px}.student-calendar-table thead th{padding:0 14px 10px;color:var(--sp-muted);font-size:12px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;border:none}.student-calendar-table tbody td{padding:14px;border:1px solid #e2ebf5;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);color:var(--sp-text);vertical-align:top}.student-calendar-table tbody td:first-child{border-radius:16px 0 0 16px;font-weight:800;color:var(--sp-primary)}.student-calendar-table tbody td:last-child{border-radius:0 16px 16px 0}.student-table-index{width:70px}.student-table-weeks{width:170px}.student-calendar-mobile{display:none;gap:12px}.student-calendar-mobile-card{border:1px solid #e2ebf5;border-radius:18px;background:linear-gradient(180deg,#fbfdff 0%,#f6f9fc 100%);padding:16px}.student-calendar-mobile-head{display:flex;justify-content:space-between;gap:12px;align-items:center;margin-bottom:10px}.student-calendar-mobile-title{margin:0;color:var(--sp-primary-deep);font-size:16px;font-weight:800}.student-calendar-mobile-meta{display:grid;gap:8px}.student-calendar-mobile-meta div{font-size:14px;color:var(--sp-text)}.student-calendar-mobile-meta strong{color:var(--sp-primary)}
@media(max-width:1199px){.student-stat-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:767px){.student-page{gap:16px}.student-hero,.student-panel,.student-stat-card{padding:18px;border-radius:20px}.student-stat-grid{grid-template-columns:1fr}.student-panel-header{flex-direction:column;align-items:flex-start}.student-calendar-table{display:none}.student-calendar-mobile{display:grid}}
</style>
<div class="student-page" id="academics-section">
    <section class="student-hero">
        <span class="student-kicker">Academic Calendar</span>
        <h1><?php echo htmlspecialchars($calendar['year_label']); ?></h1>
        <p>Your academics page brings the official calendar into a cleaner dashboard layout so class work, mid exams, semester exams, and lab schedules are easier to scan on desktop and mobile.</p>
        <div class="student-meta-line">
            <span class="student-meta-pill"><strong>Class</strong> <?php echo htmlspecialchars($userClassName !== '' ? $userClassName : 'Not Assigned'); ?></span>
            <span class="student-meta-pill"><strong>Year</strong> <?php echo htmlspecialchars($calendarYear); ?></span>
            <span class="student-meta-pill"><strong>Session</strong> 2025-26</span>
        </div>
    </section>

    <section class="student-stat-grid">
        <article class="student-stat-card"><p class="student-stat-label">I Semester Items</p><p class="student-stat-value"><?php echo $semesterOneCount; ?></p><p class="student-stat-note">Milestones planned for the first semester schedule.</p></article>
        <article class="student-stat-card"><p class="student-stat-label">II Semester Items</p><p class="student-stat-value"><?php echo $semesterTwoCount; ?></p><p class="student-stat-note">Milestones planned for the second semester schedule.</p></article>
        <article class="student-stat-card"><p class="student-stat-label">Total Academic Rows</p><p class="student-stat-value"><?php echo $totalAcademicRows; ?></p><p class="student-stat-note">Combined calendar activities shown for this academic year.</p></article>
        <article class="student-stat-card"><p class="student-stat-label">Current Track</p><p class="student-stat-value"><?php echo htmlspecialchars($calendarYear); ?></p><p class="student-stat-note">The displayed calendar is matched to your current year mapping.</p></article>
    </section>

    <?php foreach (array('sem_1' => 'I Semester', 'sem_2' => 'II Semester') as $semesterKey => $semesterTitle) { ?>
        <section class="student-panel">
            <div class="student-panel-header">
                <div>
                    <h2 class="student-panel-title"><?php echo htmlspecialchars($semesterTitle); ?></h2>
                    <p class="student-panel-subtitle">Official schedule entries for <?php echo htmlspecialchars($calendar['year_label']); ?>.</p>
                </div>
                <span class="student-tag"><?php echo count($calendar[$semesterKey]); ?> items</span>
            </div>

            <div class="table-responsive">
                <table class="student-calendar-table">
                    <thead>
                        <tr>
                            <th class="student-table-index">S.No.</th>
                            <th>Description</th>
                            <th>From</th>
                            <th>To</th>
                            <th class="student-table-weeks">Duration (Weeks)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($calendar[$semesterKey] as $row) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row[0]); ?></td>
                                <td><?php echo htmlspecialchars($row[1]); ?></td>
                                <td><?php echo htmlspecialchars($row[2]); ?></td>
                                <td><?php echo htmlspecialchars($row[3]); ?></td>
                                <td><?php echo htmlspecialchars($row[4]); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div class="student-calendar-mobile">
                <?php foreach ($calendar[$semesterKey] as $row) { ?>
                    <article class="student-calendar-mobile-card">
                        <div class="student-calendar-mobile-head">
                            <h3 class="student-calendar-mobile-title"><?php echo htmlspecialchars($row[1]); ?></h3>
                            <span class="student-tag">#<?php echo htmlspecialchars($row[0]); ?></span>
                        </div>
                        <div class="student-calendar-mobile-meta">
                            <div><strong>From:</strong> <?php echo htmlspecialchars($row[2]); ?></div>
                            <div><strong>To:</strong> <?php echo htmlspecialchars($row[3]); ?></div>
                            <div><strong>Duration:</strong> <?php echo htmlspecialchars($row[4]); ?> week(s)</div>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </section>
    <?php } ?>
</div>
<?php include_once(__DIR__ . '/layout/main_footer.php'); ?>
