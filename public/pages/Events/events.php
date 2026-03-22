<?php 
require_once(__DIR__ . '/../../../config.php');

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');


$fcObj = new DataFunctions();

$tbEvents = TB_EVENTS;

$pastEvents   = $fcObj->getPastEvents($tbEvents);
$curEvents    = $fcObj->getCurrentEvents($tbEvents);
$futureEvents = $fcObj->getFutureEvents($tbEvents);

$userDetails = array();
$tbUsers = TB_USERS;

if (isset($_SESSION['userName'])) {
    $userDetails = $fcObj->userCheck($tbUsers, $_SESSION['userName']);
}
?>

<div class="container my-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold">AIML Association Events</h2>
        <p class="text-muted">Stay updated with academic and association activities</p>
    </div>

    <!-- Bootstrap Tabs -->
    <ul class="nav nav-pills justify-content-center mb-4" id="eventTabs">
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#past">
                Past Events
            </button>
        </li>    
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#current">
                Current Events
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#future">
                Future Events
            </button>
        </li>
    </ul>

    <div class="tab-content">

        <!-- CURRENT EVENTS -->
        <div class="tab-pane fade show active" id="current">
            <div class="row g-4">
                <?php foreach ($curEvents as $event) { ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">

                                <span class="badge bg-success mb-2">Live</span>

                                <h5 class="fw-semibold">
                                    <a href="eventdetails.php?event=<?php echo $event['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo $event['event_name']; ?>
                                    </a>
                                </h5>

                                <p class="small text-muted mb-2">
                                    <strong>Date:</strong>
                                    <?php echo date("d M Y", strtotime($event['event_date'])); ?>
                                </p>

                                <p class="small text-muted">
                                    <strong>Registration:</strong><br>
                                    <?php
                                        echo date("d M", strtotime($event['reg_frm_date'])) .
                                             " - " .
                                             date("d M Y", strtotime($event['reg_to_date']));
                                    ?>
                                </p>

                                <?php
                                    $today = strtotime(date('Y-m-d'));
                                    $from  = strtotime($event['reg_frm_date']);
                                    $to    = strtotime($event['reg_to_date']);

                                    if (
                                        $event['is_registration'] == 1 &&
                                        ($to >= $today && $today >= $from) &&
                                        isset($_SESSION['userId']) &&
                                        ($userDetails[0]['section'] != PASSOUT)
                                    ) {
                                ?>
                                    <form method="POST" action="eventsregister.php">
                                        <input type="hidden" name="event<?php echo $event['id']; ?>" value="<?php echo $event['id']; ?>">
                                        <button class="btn btn-warning btn-sm mt-2 w-100">
                                            Register Now
                                        </button>
                                    </form>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- FUTURE EVENTS -->
        <div class="tab-pane fade" id="future">
            <div class="row g-4">
                <?php foreach ($futureEvents as $event) { ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2">Upcoming</span>

                                <h5 class="fw-semibold">
                                    <a href="eventdetails.php?event=<?php echo $event['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo $event['event_name']; ?>
                                    </a>
                                </h5>

                                <p class="small text-muted">
                                    <?php echo date("d M Y", strtotime($event['event_date'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- PAST EVENTS -->
        <div class="tab-pane fade" id="past">
            <div class="row g-4">
                <?php foreach ($pastEvents as $event) { ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <span class="badge bg-secondary mb-2">Completed</span>

                                <h5 class="fw-semibold">
                                    <a href="eventdetails.php?event=<?php echo $event['id']; ?>" class="text-decoration-none text-dark">
                                        <?php echo $event['event_name']; ?>
                                    </a>
                                </h5>

                                <p class="small text-muted">
                                    <?php echo date("d M Y", strtotime($event['event_date'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>

</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
