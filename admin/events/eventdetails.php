<?php require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbEvents = TB_EVENTS;

$eventId = isset($_GET['event']) ? (int)$_GET['event'] : 0;
$eventDetails = array();
if ($eventId > 0) {
    $eventDetails = $fcObj->getEventDetails($tbEvents, $eventId);
}

if (empty($eventDetails)) {
    header('Location: view_events.php');
    exit;
}

$event = $eventDetails[0];

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>

<div class="event-detail-page">
<div id="page">
    <div id="content">
        <div class="post">
            <span class="alignCenter"></span>
            <p></p>
        </div>
        <div id='content_left' class='content_left'></div>
        <div id='content_right' class='content_right'>
            <div class="event-detail-hero">
                <h1>Event Details</h1>
                <!-- <p>View the full event information and jump directly into edit or delete actions.</p> -->
            </div>
            <div class="event-detail-card">
                <div class="event-detail-row">
                    <div class="event-label">Event Title</div>
                    <div class="event-value"><?php echo htmlspecialchars((string)$event['event_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="event-detail-row">
                    <div class="event-label">Event Description</div>
                    <div class="event-value"><?php echo nl2br(htmlspecialchars((string)$event['event_desc'], ENT_QUOTES, 'UTF-8')); ?></div>
                </div>
                <div class="event-detail-row">
                    <div class="event-label">Event Date</div>
                    <div class="event-value"><?php echo htmlspecialchars((string)$event['event_date'], ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="event-detail-row">
                    <div class="event-label">Venue</div>
                    <div class="event-value"><?php echo nl2br(htmlspecialchars((string)$event['event_address'], ENT_QUOTES, 'UTF-8')); ?></div>
                </div>
                <div class="event-detail-row">
                    <div class="event-label">Registration Dates</div>
                    <div class="event-value">
                        <?php echo htmlspecialchars((string)$event['reg_frm_date'], ENT_QUOTES, 'UTF-8'); ?> to
                        <?php echo htmlspecialchars((string)$event['reg_to_date'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>

                <div class="event-actions">
                    <a href="edit_event.php?event=<?php echo (int)$event['id']; ?>">
                        <input type="button" class="button btn-edit" value="Edit" />
                    </a>
                    <a href="delete_event.php?event=<?php echo (int)$event['id']; ?>" onclick="return confirm('Do You Want To Continue To Delete');">
                        <input type="button" class="button btn-delete" value="Delete" />
                    </a>
                </div>
            </div>
        </div>
        <br class="clearfix" />
    </div>
                    <div class="mt-3">
                    <a href="../settings/department_option.php?option=events" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>
    <br class="clearfix" />
</div>
</div>
</div>

<?php include_once('../layout/footer.php'); ?>

