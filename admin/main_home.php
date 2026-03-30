<?php
include_once('layout/main_header.php');
require_once("../libraries/functions.class.php");
require_once("../libraries/constants.php");

$fcObj = new DataFunctions();

$totalStudents = (int)$fcObj->getCount(TB_USERS);
$totalStaff = (int)$fcObj->getCount(TB_STAFF);
$totalCourses = (int)$fcObj->getCount(TB_SUBJECTS);
$totalEvents = (int)$fcObj->getCount(TB_EVENTS);
$activities = $fcObj->getLatestActivities();
$upcomingEvents = $fcObj->getUpcomingEvents(6);
$enrollmentByBatch = $fcObj->getEnrollmentByBatch(4);
$pendingApprovals = count($fcObj->getTempUsers(TB_USERS));
$todayDate = date('Y-m-d');
$todayEventsCount = 0;

foreach ($upcomingEvents as $eventRow) {
    if (($eventRow['event_date'] ?? '') === $todayDate) {
        $todayEventsCount++;
    }
}

$taskItems = array();

if ($pendingApprovals > 0) {
    $taskItems[] = array(
        'title' => 'Approve ' . $pendingApprovals . ' new student enrollment' . ($pendingApprovals === 1 ? '' : 's'),
        'meta' => 'Pending approvals',
        'link' => BASE_URL . '/admin/students/users.php',
        'accent' => 'warning'
    );
}

if (!empty($upcomingEvents)) {
    $nextEvent = $upcomingEvents[0];
    $taskItems[] = array(
        'title' => 'Review next event: ' . $nextEvent['event_name'],
        'meta' => date('d M, Y', strtotime($nextEvent['event_date'])),
        'link' => BASE_URL . '/admin/events/view_events.php',
        'accent' => 'primary'
    );
}

if (!empty($enrollmentByBatch)) {
    $topBatch = $enrollmentByBatch[0];
    $taskItems[] = array(
        'title' => 'Check ' . $topBatch['batch_name'] . ' enrollment snapshot',
        'meta' => (int)$topBatch['total'] . ' students mapped',
        'link' => BASE_URL . '/admin/students/manage_users.php',
        'accent' => 'success'
    );
}

$taskItems[] = array(
    'title' => 'Update course and core settings',
    'meta' => $totalCourses . ' courses currently configured',
    'link' => BASE_URL . '/admin/settings/otheroperations.php',
    'accent' => 'neutral'
);
?>

<link rel="stylesheet" href="../public/assets/css/admin_dashboard.css?v=5">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/lucide@latest"></script>


<div class="hybrid-wrapper">
    <header class="hybrid-topbar" id="overview">
        <div class="hybrid-title-block">
            <h1>Dashboard Overview</h1>
            <p class="hybrid-subtitle">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['adminFirstName'] ?? 'Admin', ENT_QUOTES, 'UTF-8'); ?></strong>!</p>
        </div>
    </header>

    <section class="hybrid-kpis">
        <article class="metric-card">
            <div class="metric-card-icon navy">
                <i data-lucide="users"></i>
            </div>
            <div class="metric-card-body">
                <p class="metric-card-label">Total Students</p>
                <div class="metric-card-value"><?php echo $totalStudents; ?></div>
                <p class="metric-card-note">Registered student accounts</p>
            </div>
        </article>

        <article class="metric-card">
            <div class="metric-card-icon teal">
                <i data-lucide="graduation-cap"></i>
            </div>
            <div class="metric-card-body">
                <p class="metric-card-label">Total Faculty</p>
                <div class="metric-card-value"><?php echo $totalStaff; ?></div>
                <p class="metric-card-note positive">Department faculty records</p>
            </div>
        </article>

        <article class="metric-card">
            <div class="metric-card-icon gold">
                <i data-lucide="calendar-days"></i>
            </div>
            <div class="metric-card-body">
                <p class="metric-card-label">Active Events</p>
                <div class="metric-card-value"><?php echo count($upcomingEvents); ?> <span>upcoming</span></div>
                <p class="metric-card-note"><?php echo $todayEventsCount; ?> today</p>
            </div>
        </article>

        <article class="metric-card">
            <div class="metric-card-icon orange">
                <i data-lucide="user-plus"></i>
            </div>
            <div class="metric-card-body">
                <p class="metric-card-label">New Approvals</p>
                <div class="metric-card-value"><?php echo $pendingApprovals; ?></div>
                <p class="metric-card-note"><?php echo $pendingApprovals; ?> pending student approvals</p>
            </div>
        </article>
    </section>

    <section class="hybrid-showcase">
        <section class="dashboard-panel">
            <div class="panel-head">
                <h2>Recent Activity &amp; Events</h2>
                <a href="<?php echo BASE_URL; ?>/admin/events/view_events.php" class="panel-action">More</a>
            </div>

            <div class="panel-body">
                <div class="panel-subhead">
                    <span>Upcoming Events</span>
                    <a href="<?php echo BASE_URL; ?>/admin/events/view_events.php">See all</a>
                </div>

                <div class="events-list">
                    <?php if (!empty($upcomingEvents)) { ?>
                        <?php foreach ($upcomingEvents as $event) { ?>
                            <a class="event-row" href="<?php echo BASE_URL; ?>/admin/events/view_events.php">
                                <div class="event-date">
                                    <strong><?php echo date('d', strtotime($event['event_date'])); ?></strong>
                                    <span><?php echo strtoupper(date('M', strtotime($event['event_date']))); ?></span>
                                </div>
                                <div class="event-content">
                                    <h3><?php echo htmlspecialchars($event['event_name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                    <p><?php echo date('d M, Y', strtotime($event['event_date'])); ?><?php echo ($event['event_date'] === $todayDate) ? ' · Today' : ' · Scheduled'; ?></p>
                                </div>
                                <i class="bi bi-chevron-right event-arrow"></i>
                            </a>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="empty-panel-state">No upcoming events scheduled yet.</div>
                    <?php } ?>
                </div>

                <?php if (!empty($activities)) { ?>
                    <div class="activity-strip">
                        <?php foreach (array_slice($activities, 0, 3) as $row) { ?>
                            <div class="activity-pill">
                                <span><?php echo htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <small><?php echo date('d M', strtotime($row['created_at'])); ?></small>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>

        <section class="dashboard-panel">
            <div class="panel-head">
                <h2>Task Center</h2>
                <a href="<?php echo BASE_URL; ?>/admin/students/manage_users.php" class="panel-filter">Filter</a>
            </div>

            <div class="panel-body">
                <div class="tasks-list">
                    <?php foreach ($taskItems as $task) { ?>
                        <a class="task-row <?php echo $task['accent']; ?>" href="<?php echo $task['link']; ?>">
                            <span class="task-dot"></span>
                            <div class="task-content">
                                <h3><?php echo htmlspecialchars($task['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                <p><?php echo htmlspecialchars($task['meta'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                            <i class="bi bi-chevron-right task-arrow"></i>
                        </a>
                    <?php } ?>
                </div>

                <div class="mini-stats-grid">
                    <div class="mini-stat">
                        <span class="mini-stat-label">Courses</span>
                        <strong><?php echo $totalCourses; ?></strong>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-label">Faculty</span>
                        <strong><?php echo $totalStaff; ?></strong>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-label">Events</span>
                        <strong><?php echo $totalEvents; ?></strong>
                    </div>
                    <div class="mini-stat">
                        <span class="mini-stat-label">Batches</span>
                        <strong><?php echo count($enrollmentByBatch); ?></strong>
                    </div>
                </div>
            </div>
        </section>
    </section>
</div>

<script>
lucide.createIcons();
</script>

<?php include_once('layout/footer.php'); ?>

