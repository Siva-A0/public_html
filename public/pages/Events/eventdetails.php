<?php 
require_once(__DIR__ . '/../../../config.php');

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$eventId = isset($_GET['event']) ? (int)$_GET['event'] : 0;
$tbEvents = TB_EVENTS;
$eventDetails = $fcObj->getEventDetails($tbEvents, $eventId);
$event = !empty($eventDetails) ? $eventDetails[0] : null;

$today = strtotime(date('Y-m-d'));
$eventTime = $event ? strtotime((string)$event['event_date']) : false;
$monthStart = strtotime(date('Y-m-01'));
$monthEnd = strtotime(date('Y-m-t'));

$statusLabel = 'Completed';
$statusClass = 'past';
if ($eventTime !== false) {
    if ($eventTime > $monthEnd) {
        $statusLabel = 'Upcoming';
        $statusClass = 'future';
    } elseif ($eventTime >= $monthStart && $eventTime <= $monthEnd) {
        $statusLabel = 'Current';
        $statusClass = 'current';
    }
}

$canRegister = false;
if ($event) {
    $from = strtotime((string)$event['reg_frm_date']);
    $to = strtotime((string)$event['reg_to_date']);
    $canRegister = isset($_SESSION['userId'])
        && (int)$event['is_registration'] === 1
        && $from !== false
        && $to !== false
        && $today >= $from
        && $today <= $to;
}
?>

<style>
    .event-detail-shell {
        max-width: 1240px;
    }

    .event-detail-hero {
        border: 1px solid #d8e4ef;
        border-radius: 22px;
        padding: 30px;
        margin-bottom: 26px;
        background:
            radial-gradient(circle at top right, rgba(13, 103, 213, 0.16), transparent 28%),
            radial-gradient(circle at left bottom, rgba(52, 168, 83, 0.12), transparent 24%),
            linear-gradient(135deg, #0b1d3a, #123f74 56%, #1d78d8 100%);
        box-shadow: 0 18px 40px rgba(10, 28, 54, 0.18);
        color: #ffffff;
    }

    .event-detail-kicker {
        display: inline-flex;
        align-items: center;
        padding: 7px 12px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.12);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .event-detail-title {
        margin: 16px 0 10px;
        color: #ffffff;
        font-size: clamp(28px, 4vw, 44px);
        font-weight: 800;
        letter-spacing: -0.04em;
    }

    .event-detail-summary {
        margin: 0;
        max-width: 760px;
        color: rgba(236, 245, 255, 0.9);
        font-size: 16px;
        line-height: 1.75;
    }

    .event-detail-badges {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 18px;
    }

    .event-detail-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        border: 1px solid rgba(255, 255, 255, 0.18);
        background: rgba(255, 255, 255, 0.12);
        color: #ffffff;
        font-size: 12px;
        font-weight: 700;
    }

    .event-detail-layout {
        display: grid;
        grid-template-columns: minmax(220px, 250px) minmax(0, 1fr) minmax(220px, 260px);
        gap: 24px;
        align-items: start;
    }

    .event-side-card,
    .event-main-card {
        border: 1px solid #d8e4ef;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        box-shadow: 0 14px 32px rgba(15, 30, 52, 0.08);
        overflow: hidden;
    }

    .event-main-card {
        padding: 22px;
    }

    .event-main-title {
        margin: 0 0 18px;
        color: #12365f;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .event-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 20px;
    }

    .event-info-card {
        border: 1px solid #dde7f2;
        border-radius: 16px;
        background: #fbfdff;
        padding: 14px 16px;
    }

    .event-info-card strong {
        display: block;
        color: #5f748d;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .event-info-card span {
        color: #17375a;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.5;
    }

    .event-description-box {
        border-top: 1px solid #e1eaf4;
        padding-top: 20px;
    }

    .event-description-box p {
        margin: 0;
        color: #425a74;
        line-height: 1.8;
        font-size: 15px;
        white-space: pre-line;
    }

    .event-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 22px;
    }

    .event-back-btn,
    .event-register-btn {
        border-radius: 12px;
        font-weight: 700;
        padding: 11px 18px;
    }

    .event-register-btn {
        border: 0;
        background: linear-gradient(135deg, #f59e0b, #f2b53d);
        color: #1f2937;
        box-shadow: 0 12px 24px rgba(245, 158, 11, 0.22);
    }

    .event-empty {
        border: 1px solid #d8e4ef;
        border-radius: 20px;
        background: linear-gradient(180deg, #ffffff, #f8fbff);
        box-shadow: 0 14px 32px rgba(15, 30, 52, 0.08);
        padding: 28px;
        text-align: center;
    }

    .event-empty h3 {
        margin-bottom: 10px;
        font-size: 28px;
        color: #12365f;
    }

    .event-empty p {
        margin: 0;
        color: #60748b;
    }

    @media (max-width: 991px) {
        .event-detail-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .event-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container my-5 event-detail-shell">
    <?php if (!$event) { ?>
        <div class="event-empty">
            <h3>Event Not Found</h3>
            <p>The event you selected is unavailable.</p>
        </div>
    <?php } else { ?>
        <div class="event-detail-hero">
            <span class="event-detail-kicker">Event Details</span>
            <h1 class="event-detail-title"><?php echo htmlspecialchars((string)$event['event_name'], ENT_QUOTES, 'UTF-8'); ?></h1>
            <p class="event-detail-summary">
                <?php echo htmlspecialchars(trim((string)$event['event_desc']) !== '' ? (string)$event['event_desc'] : 'Explore the event schedule, venue, and registration information below.', ENT_QUOTES, 'UTF-8'); ?>
            </p>
            <div class="event-detail-badges">
                <span class="event-detail-badge"><?php echo htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></span>
                <?php if ((int)$event['is_registration'] === 1) { ?>
                    <span class="event-detail-badge">Registration Enabled</span>
                <?php } ?>
            </div>
        </div>

        <div class="event-detail-layout">
            <aside class="event-side-card">
                <?php include_once('leftnav.php'); ?>
            </aside>

            <section class="event-main-card">
                <h2 class="event-main-title">Event Overview</h2>

                <div class="event-info-grid">
                    <div class="event-info-card">
                        <strong>Event Date</strong>
                        <span><?php echo date("d M Y", strtotime($event['event_date'])); ?></span>
                    </div>
                    <div class="event-info-card">
                        <strong>Venue</strong>
                        <span><?php echo htmlspecialchars((string)$event['event_address'], ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                    <div class="event-info-card">
                        <strong>Registration Window</strong>
                        <span>
                            <?php echo date("d M Y", strtotime($event['reg_frm_date'])); ?>
                            to
                            <?php echo date("d M Y", strtotime($event['reg_to_date'])); ?>
                        </span>
                    </div>
                    <div class="event-info-card">
                        <strong>Association</strong>
                        <span>AIML Association</span>
                    </div>
                </div>

                <div class="event-description-box">
                    <p><?php echo htmlspecialchars((string)$event['event_desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>

                <div class="event-actions">
                    <?php if ($canRegister) { ?>
                        <form action="eventsregister.php" method="POST" class="m-0">
                            <input type="hidden" name="event<?php echo (int)$event['id']; ?>" value="<?php echo (int)$event['id']; ?>" />
                            <button type="submit" name="currentEventReg" class="btn event-register-btn">Register</button>
                        </form>
                    <?php } ?>
                    <a href="<?php echo BASE_URL; ?>/public/pages/Events/events.php" class="btn btn-outline-primary event-back-btn">Back to Events</a>
                </div>
            </section>

            <aside class="event-side-card">
                <?php include_once('sidebar.php'); ?>
            </aside>
        </div>
    <?php } ?>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
