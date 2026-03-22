<?php
if (session_id() == '') {
    session_start();
}
require_once(__DIR__ . '/../../config.php');

if (!isset($_SESSION['adminId'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbUsers = TB_USERS;
$tbBatch = TB_BATCH;
$tbClass = TB_CLASS;
$tbSection = TB_SECTION;

function admin_users_flash_get(){
    $msg = isset($_SESSION['admin_users_flash_msg']) ? (string)$_SESSION['admin_users_flash_msg'] : '';
    $type = isset($_SESSION['admin_users_flash_type']) ? (string)$_SESSION['admin_users_flash_type'] : 'success';
    unset($_SESSION['admin_users_flash_msg'], $_SESSION['admin_users_flash_type']);
    return array($msg, $type);
}

function admin_users_flash_set($msg, $type){
    $_SESSION['admin_users_flash_msg'] = (string)$msg;
    $_SESSION['admin_users_flash_type'] = (string)$type;
}

function admin_users_redirect_self(){
    $qs = $_SERVER['QUERY_STRING'] ?? '';
    $url = BASE_URL . '/admin/users/manage_users.php' . ($qs !== '' ? ('?' . $qs) : '');
    header('Location: ' . $url);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        admin_users_flash_set('Your session expired. Please try again.', 'danger');
        admin_users_redirect_self();
    }

    $action = trim((string)$_POST['action']);
    $userId = (int)($_POST['user_id'] ?? 0);

    if ($userId <= 0) {
        admin_users_flash_set('Invalid user selected.', 'danger');
        admin_users_redirect_self();
    }

    if ($action === 'approve') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 1);
        admin_users_flash_set($ok !== false ? 'User approved.' : 'Unable to approve user.', $ok !== false ? 'success' : 'danger');
        admin_users_redirect_self();
    }

    if ($action === 'deactivate') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 2);
        admin_users_flash_set($ok !== false ? 'User deactivated.' : 'Unable to deactivate user.', $ok !== false ? 'success' : 'danger');
        admin_users_redirect_self();
    }

    if ($action === 'activate') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 1);
        admin_users_flash_set($ok !== false ? 'User activated.' : 'Unable to activate user.', $ok !== false ? 'success' : 'danger');
        admin_users_redirect_self();
    }

    if ($action === 'delete') {
        $ok = $fcObj->adminDeleteUserById($tbUsers, $userId);
        admin_users_flash_set($ok !== false ? 'User deleted.' : 'Unable to delete user.', $ok !== false ? 'success' : 'danger');
        admin_users_redirect_self();
    }

    if ($action === 'reset_password') {
        $tempPassword = substr(bin2hex(random_bytes(6)), 0, 12);
        $ok = $fcObj->adminUpdateUserPasswordById($tbUsers, $userId, $fcObj->hashPassword($tempPassword));
        if ($ok !== false) {
            admin_users_flash_set('Temporary password set for user ID ' . $userId . ': ' . $tempPassword, 'success');
        } else {
            admin_users_flash_set('Unable to reset password.', 'danger');
        }
        admin_users_redirect_self();
    }

    admin_users_flash_set('Unknown action.', 'danger');
    admin_users_redirect_self();
}

include_once(__DIR__ . '/../layout/main_header.php');

// Filters
$status = isset($_GET['status']) ? trim((string)$_GET['status']) : '';
$batchId = isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
$classId = isset($_GET['classId']) ? (int)$_GET['classId'] : 0;
$sectionId = isset($_GET['sectionId']) ? (int)$_GET['sectionId'] : 0;
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

$statusFilter = null;
if ($status !== '' && is_numeric($status)) {
    $statusFilter = (int)$status;
}

$batches = $fcObj->getBatches($tbBatch);
$classes = $fcObj->getClassesWOPO($tbClass);
$sections = array();
if ($batchId > 0 && $classId > 0) {
    $sections = $fcObj->getSections($tbSection, $classId, $batchId);
}

$filters = array(
    'limit' => 200,
    'q' => $q
);
if ($statusFilter !== null) {
    $filters['status'] = $statusFilter;
}
if ($batchId > 0) {
    $filters['batch_id'] = $batchId;
}
if ($classId > 0) {
    $filters['class_id'] = $classId;
}
if ($sectionId > 0) {
    $filters['section_id'] = $sectionId;
}

$users = $fcObj->adminGetUsersList($tbUsers, $filters);

list($flashMsg, $flashType) = admin_users_flash_get();
?>

<div class="container-fluid">
    <style>
        .manage-users-page .page-title {
            font-weight: 800;
            letter-spacing: -0.4px;
        }

        .manage-users-page .page-subtitle {
            color: #64748b;
        }

        .manage-users-page .filters-card .form-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .manage-users-page .user-table thead th {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #475569;
            background: #f8fafc;
        }

        .manage-users-page .user-table tbody td {
            vertical-align: middle;
        }

        .manage-users-page .cell-title {
            font-weight: 700;
            color: #0f172a;
        }

        .manage-users-page .cell-sub {
            font-size: 12px;
            color: #64748b;
        }

        .manage-users-page .pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid #d7e3f2;
            background: #f2f7ff;
            font-size: 12px;
            font-weight: 700;
            color: #1f3b63;
        }

        .manage-users-page .pill-muted {
            background: #f1f5f9;
            border-color: #e2e8f0;
            color: #475569;
        }

        .manage-users-page .actions {
            display: flex;
            justify-content: flex-end;
        }

        .manage-users-page .actions .btn {
            white-space: nowrap;
        }

        @media (max-width: 991px) {
            .manage-users-page .actions {
                justify-content: flex-start;
            }
        }
    </style>

    <div class="manage-users-page">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <h3 class="mb-1 page-title">Manage Students</h3>
                <div class="page-subtitle">Approve, deactivate, edit academic mapping, and reset passwords.</div>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="<?php echo BASE_URL; ?>/admin/users/users.php" class="btn btn-outline-secondary">Pending Approvals</a>
            </div>
        </div>

        <?php if ($flashMsg !== '') { ?>
            <div class="alert alert-<?php echo htmlspecialchars($flashType, ENT_QUOTES, 'UTF-8'); ?> py-2">
                <?php echo htmlspecialchars($flashMsg, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <div class="card border-0 shadow-sm mb-3 filters-card">
            <div class="card-body">
                <form method="get" class="row g-3 align-items-end">
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="" <?php echo $status === '' ? 'selected' : ''; ?>>All</option>
                            <option value="0" <?php echo $status === '0' ? 'selected' : ''; ?>>Pending</option>
                            <option value="1" <?php echo $status === '1' ? 'selected' : ''; ?>>Active</option>
                            <option value="2" <?php echo $status === '2' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label">Batch</label>
                        <select name="batchId" class="form-select">
                            <option value="">All</option>
                            <?php foreach ($batches as $b) { ?>
                                <option value="<?php echo (int)$b['id']; ?>" <?php echo ((int)$b['id'] === $batchId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$b['batch'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-3">
                        <label class="form-label">Class</label>
                        <select name="classId" class="form-select">
                            <option value="">All</option>
                            <?php foreach ($classes as $c) { ?>
                                <option value="<?php echo (int)$c['id']; ?>" <?php echo ((int)$c['id'] === $classId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$c['class_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label">Section</label>
                        <select name="sectionId" class="form-select" <?php echo ($batchId > 0 && $classId > 0) ? '' : 'disabled'; ?>>
                            <option value="">All</option>
                            <?php foreach ($sections as $s) { ?>
                                <option value="<?php echo (int)$s['id']; ?>" <?php echo ((int)$s['id'] === $sectionId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$s['section_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label">Search</label>
                        <input name="q" class="form-control" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>" placeholder="username / roll / email">
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>/admin/users/manage_users.php">Reset</a>
                        <button class="btn btn-primary" type="submit">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <div class="text-muted small"><?php echo (int)count($users); ?> result(s)</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 user-table">
                        <thead class="table-light">
                            <tr>
                                <th style="min-width:70px;">ID</th>
                                <th style="min-width:220px;">Student</th>
                                <th style="min-width:240px;">Contact</th>
                                <th style="min-width:260px;">Academic</th>
                                <th style="min-width:110px;">Status</th>
                                <th style="min-width:210px;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)) { ?>
                                <tr><td colspan="6" class="text-muted py-4 text-center">No users found.</td></tr>
                            <?php } else { ?>
                                <?php foreach ($users as $u) { ?>
                                    <?php
                                        $uId = (int)($u['id'] ?? 0);
                                        $statusVal = (int)($u['status'] ?? 0);
                                        $statusLabel = $statusVal === 1 ? 'Active' : ($statusVal === 2 ? 'Inactive' : 'Pending');
                                        $statusClass = $statusVal === 1 ? 'bg-success' : ($statusVal === 2 ? 'bg-secondary' : 'bg-warning text-dark');
                                        $studentName = trim((string)($u['firstname'] ?? '') . ' ' . (string)($u['lastname'] ?? ''));
                                        if ($studentName === '') {
                                            $studentName = (string)($u['username'] ?? '');
                                        }
                                        $username = (string)($u['username'] ?? '');
                                        $batchName = trim((string)($u['batch_name'] ?? ''));
                                        $className = trim((string)($u['class_name'] ?? ''));
                                        $sectionName = trim((string)($u['section_name'] ?? ''));
                                        $email = (string)($u['mail_id'] ?? '');
                                        $roll = (string)($u['admission_id'] ?? '');
                                        $dropdownId = 'userMore_' . $uId;
                                    ?>
                                    <tr>
                                        <td><?php echo $uId; ?></td>
                                        <td>
                                            <div class="cell-title"><?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></div>
                                            <?php if ($username !== '') { ?>
                                                <div class="cell-sub">@<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <span class="pill"><i class="bi bi-hash"></i><?php echo htmlspecialchars($roll !== '' ? $roll : '-', ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                            <?php if ($email !== '') { ?>
                                                <div class="cell-sub mt-1 text-truncate" style="max-width: 280px;">
                                                    <i class="bi bi-envelope me-1"></i><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="pill pill-muted"><i class="bi bi-calendar3"></i><?php echo htmlspecialchars($batchName !== '' ? $batchName : '-', ENT_QUOTES, 'UTF-8'); ?></span>
                                                <span class="pill"><i class="bi bi-mortarboard"></i><?php echo htmlspecialchars($className !== '' ? $className : '-', ENT_QUOTES, 'UTF-8'); ?></span>
                                                <span class="pill pill-muted"><i class="bi bi-diagram-3"></i><?php echo htmlspecialchars($sectionName !== '' ? $sectionName : '-', ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>
                                        </td>
                                        <td><span class="badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                                        <td class="text-end">
                                            <div class="actions">
                                                <div class="btn-group btn-group-sm" role="group" aria-label="User actions">
                                                    <?php if ($statusVal !== 1) { ?>
                                                        <form method="post" class="m-0">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                            <input type="hidden" name="action" value="approve">
                                                            <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                            <button class="btn btn-success" type="submit">Approve</button>
                                                        </form>
                                                    <?php } ?>

                                                    <?php if ($statusVal === 1) { ?>
                                                        <form method="post" class="m-0">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                            <input type="hidden" name="action" value="deactivate">
                                                            <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                            <button class="btn btn-outline-secondary" type="submit">Deactivate</button>
                                                        </form>
                                                    <?php } elseif ($statusVal === 2) { ?>
                                                        <form method="post" class="m-0">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                            <input type="hidden" name="action" value="activate">
                                                            <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                            <button class="btn btn-outline-primary" type="submit">Activate</button>
                                                        </form>
                                                    <?php } ?>

                                                    <a class="btn btn-outline-primary" href="<?php echo BASE_URL; ?>/admin/users/edit_user.php?id=<?php echo $uId; ?>">Edit</a>

                                                    <button
                                                        class="btn btn-outline-secondary dropdown-toggle"
                                                        type="button"
                                                        id="<?php echo htmlspecialchars($dropdownId, ENT_QUOTES, 'UTF-8'); ?>"
                                                        data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                    >
                                                        More
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="<?php echo htmlspecialchars($dropdownId, ENT_QUOTES, 'UTF-8'); ?>">
                                                        <li>
                                                            <form method="post" class="m-0">
                                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                                <input type="hidden" name="action" value="reset_password">
                                                                <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                                <button class="dropdown-item" type="submit" onclick="return confirm('Set a new temporary password for this user?');">
                                                                    <i class="bi bi-key me-2"></i>Reset Password
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <form method="post" class="m-0">
                                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                                <input type="hidden" name="action" value="delete">
                                                                <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                                <button class="dropdown-item text-danger" type="submit" onclick="return confirm('Delete this user permanently?');">
                                                                    <i class="bi bi-trash me-2"></i>Delete User
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once(__DIR__ . '/../layout/footer.php'); ?>
