<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
include_once('../layout/main_header.php');

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();
$tbUsers = TB_USERS;

$regUsers = $fcObj->getTempUsers($tbUsers);
$noOfUsers = sizeof($regUsers);
?>

<style type="text/css">
    .pending-users-page .pending-header {
        border: 1px solid #cfdced;
        border-radius: 18px;
        padding: 20px 22px;
        background:
            linear-gradient(140deg, rgba(37, 99, 235, 0.06), rgba(15, 118, 110, 0.04)),
            #f8fbff;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    }

    .pending-users-page .pending-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: #0f172a;
        margin: 0;
    }

    .pending-users-page .pending-subtitle {
        margin: 8px 0 0;
        color: #52657d;
        font-size: 15px;
    }

    .pending-users-page .stats-pills {
        margin-top: 12px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .pending-users-page .stat-pill {
        border: 1px solid #bfd4ea;
        border-radius: 999px;
        padding: 7px 12px;
        background: #ecf5fe;
        color: #21496f;
        font-size: 13px;
        font-weight: 700;
    }

    .pending-users-page .pending-badge {
        border-radius: 999px;
        padding: 10px 16px;
        font-size: 16px !important;
        font-weight: 700;
        background: linear-gradient(135deg, #0f2b4e, #1e4f88) !important;
        color: #ffffff !important;
        border: 1px solid #1d4f88;
    }

    .pending-users-page .pending-card {
        border: 1px solid #d7dde6;
        border-radius: 16px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        overflow: hidden;
        background: #ffffff;
    }

    .pending-users-page .table {
        margin-bottom: 0;
    }

    .pending-users-page .table thead th {
        background: linear-gradient(90deg, #f8fbff, #f3f8ff);
        color: #1f2937;
        font-size: 17px;
        font-weight: 700;
        border-bottom: 1px solid #d6e3f1;
        padding-top: 14px;
        padding-bottom: 14px;
    }

    .pending-users-page .table tbody td {
        font-size: 16px;
        color: #334155;
        border-bottom: 1px solid #e8eef5;
        padding-top: 13px;
        padding-bottom: 13px;
    }

    .pending-users-page .table tbody tr:hover td {
        background: #f8fbff;
    }

    .pending-users-page .table-select {
        width: 18px;
        height: 18px;
        border-radius: 4px;
    }

    .pending-users-page .admission-pill {
        border: 1px solid #cfe0f2 !important;
        border-radius: 10px;
        background: #f2f8ff !important;
        color: #17406e !important;
        font-size: 14px;
        font-weight: 700;
    }

    .pending-users-page .empty-state {
        color: #64748b !important;
        font-size: 18px;
        padding: 38px 10px !important;
    }

    .pending-users-page .empty-state-wrap {
        text-align: center;
        padding: 6px 0;
    }

    .pending-users-page .empty-icon {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        margin: 0 auto 10px;
        background: linear-gradient(135deg, #e0ecf8, #d8e6f6);
        color: #3b5f86;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .pending-users-page .empty-subtext {
        display: block;
        margin-top: 4px;
        color: #70849e;
        font-size: 14px;
    }

    .pending-users-page .btn-success {
        border: 0;
        border-radius: 12px;
        padding: 10px 18px;
        background: linear-gradient(135deg, #059669, #047857);
        font-weight: 700;
        box-shadow: 0 8px 16px rgba(5, 150, 105, 0.2);
    }

    .pending-users-page .btn-outline-danger {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 700;
    }

    @media (max-width: 768px) {
        .pending-users-page .pending-title {
            font-size: 26px;
        }
    }
</style>

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
        <a class="btn btn-sm btn-outline-secondary" href="<?php echo BASE_URL; ?>/admin/users/students.php">Manage Students</a>
        <a class="btn btn-sm btn-outline-secondary" href="<?php echo BASE_URL; ?>/admin/users/alumni.php">Manage Alumni</a>
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
