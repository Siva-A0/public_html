<?php require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbEvents = TB_EVENTS;
$tbEventTypes = TB_EVENT_TYPES;

$eventTypes = $fcObj->getEventTypes($tbEventTypes);
$eventDetails = array();
$message = '';

$eventId = 0;
if (isset($_GET['event'])) {
    $eventId = (int)$_GET['event'];
}
if (isset($_POST['eventId'])) {
    $eventId = (int)$_POST['eventId'];
}

if ($eventId > 0) {
    $eventDetails = $fcObj->getEventDetails($tbEvents, $eventId);
}

if (empty($eventDetails) && !isset($_POST['updateEvent'])) {
    header('Location: view_events.php');
    exit;
}

if (isset($_POST['updateEvent'])) {
    $eventId = (int)($_POST['eventId'] ?? 0);
    $varArray = array();
    $typedEventType = trim((string)($_POST['eventType'] ?? ''));
    $varArray['event_type_id'] = $fcObj->getOrCreateEventTypeId($tbEventTypes, $typedEventType);
    $varArray['event_name'] = trim((string)($_POST['eventName'] ?? ''));
    $varArray['event_desc'] = trim((string)($_POST['eventDesc'] ?? ''));
    $varArray['event_address'] = trim((string)($_POST['eventVenue'] ?? ''));
    $varArray['event_date'] = trim((string)($_POST['eventDate'] ?? ''));
    $varArray['reg_frm_date'] = trim((string)($_POST['eventRegDate1'] ?? ''));
    $varArray['reg_to_date'] = trim((string)($_POST['eventRegDate2'] ?? ''));
    $varArray['is_registration'] = isset($_POST['isReg']) ? 1 : 0;

    if ($eventId <= 0 || $varArray['event_type_id'] <= 0 || $varArray['event_name'] === '' || $varArray['event_date'] === '') {
        $message = 'Please fill required fields (event type, event name, event date).';
    } else {
        $updated = $fcObj->updateEvent($tbEvents, $varArray, $eventId);
        if ($updated) {
            header('Location: view_events.php');
            exit;
        }
        $message = 'Sorry, Please Try Again';
    }

    $eventDetails = $fcObj->getEventDetails($tbEvents, $eventId);
}

if (empty($eventDetails)) {
    header('Location: view_events.php');
    exit;
}

$event = $eventDetails[0];
$currentEventType = '';
for ($eti = 0; $eti < count($eventTypes); $eti++) {
    if ((int)$eventTypes[$eti]['id'] === (int)$event['event_type_id']) {
        $currentEventType = (string)$eventTypes[$eti]['event_type'];
        break;
    }
}

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>

<div class="event-edit-page">
<div id="page">
    <div id="content">
        <div class="post">
            <span class="alignCenter"></span>
            <p></p>
        </div>
        <div id='content_left' class='content_left'></div>
        <div id='content_right' class='content_right'>
            <div class="event-edit-hero">
                <h1>Edit Event</h1>
                <!-- <p>Update event information, registration dates, and venue details.</p> -->
            </div>
            <div id="eventDetails">
                <?php if ($message !== '') { ?>
                    <div class="comteeMemRow">
                        <div class="usersDetHeader"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
                    </div>
                <?php } ?>

                <form id='editEvent' action='edit_event.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
                    <div class="form_row">
                        <div class="form_label">
                            <label for='eventType'>Event Type:</label>
                        </div>
                        <div class="form_field">
                            <input type="text" name="eventType" id="eventType" class="eventTypeId" value="<?php echo htmlspecialchars($currentEventType, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Type event type" required />
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label">
                            <label for='eventName'>Event Name:</label>
                        </div>
                        <div class="form_field">
                            <input type="text" name="eventName" id="eventName" class="eventName" value="<?php echo htmlspecialchars((string)$event['event_name'], ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label">
                            <label for='eventDesc'>Event Description:</label>
                        </div>
                        <div class="form_field">
                            <textarea rows="5" cols="17" name="eventDesc" id="eventDesc" class="eventDesc"><?php echo htmlspecialchars((string)$event['event_desc'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label">
                            <label for='eventVenue'>Event Venue:</label>
                        </div>
                        <div class="form_field">
                            <textarea rows="5" cols="17" name="eventVenue" id="eventVenue" class="eventVenue"><?php echo htmlspecialchars((string)$event['event_address'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label">
                            <label for='eventDate'>Event Date:</label>
                        </div>
                        <div class="form_field">
                            <input type="date" name="eventDate" id="eventDate" value="<?php echo htmlspecialchars((string)$event['event_date'], ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label">
                            <label for='eventRegDate1'>Registration Start Date:</label>
                        </div>
                        <div class="form_field">
                            <input type="date" name="eventRegDate1" id="eventRegDate1" value="<?php echo htmlspecialchars((string)$event['reg_frm_date'], ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label">
                            <label for='eventRegDate2'>Registration End Date:</label>
                        </div>
                        <div class="form_field">
                            <input type="date" name="eventRegDate2" id="eventRegDate2" value="<?php echo htmlspecialchars((string)$event['reg_to_date'], ENT_QUOTES, 'UTF-8'); ?>" />
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label">
                            <input type="checkbox" name="isReg" id="isReg" class="isReg" <?php echo ((int)$event['is_registration'] === 1) ? 'checked="checked"' : ''; ?> />
                        </div>
                        <div class="form_field">
                            Is Registration Allowed
                        </div>
                    </div>
                    <div class="form_row">
                        <div class="form_label"></div>
                        <div class="form_field">
                            <input type='hidden' name='eventId' value='<?php echo (int)$event['id']; ?>' />
                            <input type='submit' name='updateEvent' class="button" value='Update Event' />
                        </div>
                    </div>
                </form>
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

