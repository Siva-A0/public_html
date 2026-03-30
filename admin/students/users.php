<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();
$tbUsers = TB_USERS;

$regUsers = $fcObj->getTempUsers($tbUsers);
$noOfUsers = sizeof($regUsers);
?>

<div class="pending-users-page">
<div class="pending-header mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h3 class="pending-title">
            Pending User Approvals
        </h3>
        <p class="pending-subtitle">Review and approve newly registered users.</p>
        <div class="stats-pills">
            <span class="stat-pill"><i class="bi bi-person-check me-1"></i>Approval Queue</span>
            <span class="stat-pill"><i class="bi bi-shield-check me-1"></i>Admin Action Required</span>
        </div>
    </div>

    <div class="d-flex flex-column align-items-end gap-2">
        <span class="badge pending-badge">
            <?php echo (int)$noOfUsers; ?> Pending
        </span>
        <a class="btn btn-sm btn-outline-secondary" href="<?php echo BASE_URL; ?>/admin/students/students.php">Manage Students</a>
        <a class="btn btn-sm btn-outline-secondary" href="<?php echo BASE_URL; ?>/admin/students/alumni.php">Manage Alumni</a>
    </div>
</div>

<div class="card pending-card border-0">
    <div class="card-body">

        <form action="userstatus.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input class="table-select" type="checkbox" onclick="toggleAll(this)">
                            </th>
                            <th width="60">#</th>
                            <th>Username</th>
                            <th>Roll No</th>
                            <th>Email</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if($noOfUsers > 0){ ?>

                            <?php for($i=0; $i<$noOfUsers; $i++){ ?>

                                <tr>
                                    <td>
                                        <input type="checkbox"
                                               class="table-select"
                                               name="users[]"
                                               value="<?php echo $regUsers[$i]['id']; ?>">
                                    </td>

                                    <td><?php echo $i+1; ?></td>

                                    <td class="fw-semibold">
                                        <?php echo $regUsers[$i]['username']; ?>
                                    </td>

                                    <td>
                                        <span class="badge admission-pill">
                                            <?php echo $regUsers[$i]['admission_id']; ?>
                                        </span>
                                    </td>

                                    <td class="text-muted">
                                        <?php echo $regUsers[$i]['mail_id']; ?>
                                    </td>
                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted empty-state">
                                    <div class="empty-state-wrap">
                                        <span class="empty-icon"><i class="bi bi-inbox"></i></span>
                                        <span>No pending users found</span>
                                        <small class="empty-subtext">New user requests will appear here.</small>
                                    </div>
                                </td>
                            </tr>

                        <?php } ?>

                    </tbody>
                </table>
            </div>

            <?php if($noOfUsers > 0){ ?>

            <div class="mt-4 d-flex gap-3">

                <button type="submit"
                        name="approveusers"
                        class="btn btn-success px-4">
                    <i class="bi bi-check-circle me-1"></i>
                    Approve Selected
                </button>

                <button type="submit"
                        name="deleteusers"
                        class="btn btn-outline-danger px-4">
                    <i class="bi bi-trash me-1"></i>
                    Delete Selected
                </button>

            </div>

            <?php } ?>

        </form>

    </div>
</div>
</div>

<script>
function toggleAll(source) {
    document.querySelectorAll('input[name="users[]"]')
        .forEach(cb => cb.checked = source.checked);
}
</script>

<?php include_once('../layout/footer.php'); ?>


