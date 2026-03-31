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
$tbSection = TB_SECTION;

function admin_alumni_flash_get(){
    $msg = isset($_SESSION['admin_alumni_flash_msg']) ? (string)$_SESSION['admin_alumni_flash_msg'] : '';
    $type = isset($_SESSION['admin_alumni_flash_type']) ? (string)$_SESSION['admin_alumni_flash_type'] : 'success';
    unset($_SESSION['admin_alumni_flash_msg'], $_SESSION['admin_alumni_flash_type']);
    return array($msg, $type);
}

function admin_alumni_flash_set($msg, $type){
    $_SESSION['admin_alumni_flash_msg'] = (string)$msg;
    $_SESSION['admin_alumni_flash_type'] = (string)$type;
}

function admin_alumni_redirect_self(){
    $qs = $_SERVER['QUERY_STRING'] ?? '';
    header('Location: ' . BASE_URL . '/admin/students/alumni.php' . ($qs !== '' ? ('?' . $qs) : ''));
    exit;
}

// Actions (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        admin_alumni_flash_set('Your session expired. Please try again.', 'danger');
        admin_alumni_redirect_self();
    }

    $action = trim((string)$_POST['action']);
    $userId = (int)($_POST['user_id'] ?? 0);

    $bulkIdsRaw = trim((string)($_POST['user_ids'] ?? ''));
    $bulkIds = array();
    if ($bulkIdsRaw !== '') {
        foreach (preg_split('/\s*,\s*/', $bulkIdsRaw) as $idPart) {
            $idPart = trim((string)$idPart);
            if ($idPart !== '' && ctype_digit($idPart)) {
                $bulkIds[] = (int)$idPart;
            }
        }
        $bulkIds = array_values(array_unique(array_filter($bulkIds, function($v){ return (int)$v > 0; })));
    }

    if ($action === 'bulk_remove_from_alumni') {
        if (empty($bulkIds)) {
            admin_alumni_flash_set('Select at least one alumni user to remove.', 'warning');
            admin_alumni_redirect_self();
        }

        $removed = 0;
        $failed = 0;
        foreach ($bulkIds as $id) {
            $ok = $fcObj->adminRemoveUserFromAlumniAndRestore($tbUsers, $id);
            if ($ok !== false) {
                $removed++;
            } else {
                $failed++;
            }
        }

        $msg = 'Removed from alumni: ' . $removed;
        if ($failed > 0) {
            $msg .= ' · Failed: ' . $failed;
        }
        admin_alumni_flash_set($msg, $failed > 0 ? 'warning' : 'success');
        admin_alumni_redirect_self();
    }

    if ($userId <= 0) {
        admin_alumni_flash_set('Invalid user selected.', 'danger');
        admin_alumni_redirect_self();
    }

    if ($action === 'approve') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 1);
        admin_alumni_flash_set($ok !== false ? 'User approved.' : 'Unable to approve user.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    if ($action === 'activate') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 1);
        admin_alumni_flash_set($ok !== false ? 'User activated.' : 'Unable to update user.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    if ($action === 'deactivate') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 2);
        admin_alumni_flash_set($ok !== false ? 'User deactivated.' : 'Unable to update user.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    if ($action === 'reset_password') {
        $tempPassword = substr(bin2hex(random_bytes(6)), 0, 12);
        $ok = $fcObj->adminUpdateUserPasswordById($tbUsers, $userId, $fcObj->hashPassword($tempPassword));
        admin_alumni_flash_set(
            $ok !== false ? ('Temporary password for user ID ' . $userId . ': ' . $tempPassword) : 'Unable to reset password.',
            $ok !== false ? 'success' : 'danger'
        );
        admin_alumni_redirect_self();
    }

    if ($action === 'remove_from_alumni') {
        $ok = $fcObj->adminRemoveUserFromAlumniAndRestore($tbUsers, $userId);
        admin_alumni_flash_set($ok !== false ? 'User removed from Alumni.' : 'Unable to remove alumni status.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    if ($action === 'delete') {
        $ok = $fcObj->adminDeleteUserById($tbUsers, $userId);
        admin_alumni_flash_set($ok !== false ? 'User deleted.' : 'Unable to delete user.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    admin_alumni_flash_set('Unknown action.', 'danger');
    admin_alumni_redirect_self();
}

// Filters
$status = isset($_GET['status']) ? trim((string)$_GET['status']) : '';
$batchId = isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
$sectionId = isset($_GET['sectionId']) ? (int)$_GET['sectionId'] : 0;
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';

$statusFilter = null;
if ($status !== '' && is_numeric($status)) {
    $statusFilter = (int)$status;
}

$batches = $fcObj->getBatches($tbBatch);
$sections = array();
if ($batchId > 0) {
    $sections = $fcObj->getSections($tbSection, null, $batchId);
}

$filters = array(
    'limit' => 500,
    'type' => 'alumni',
    'q' => $q
);
if ($statusFilter !== null) {
    $filters['status'] = $statusFilter;
}
if ($batchId > 0) {
    $filters['batch_id'] = $batchId;
}
if ($sectionId > 0) {
    $filters['section_id'] = $sectionId;
}

$users = $fcObj->adminGetUsersList($tbUsers, $filters);
$totalAlumni = (int)count($users);
list($flashMsg, $flashType) = admin_alumni_flash_get();

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_feature_pages.css';

include_once(__DIR__ . '/../layout/main_header.php');
?>



<div class="container-fluid users-dashboard">
    <div class="users-shell">
        <div class="users-hero">
            <h3>Alumni</h3>
            <!-- <div class="users-subtitle">Keep alumni records organized with the same academic dashboard styling and clearer table actions.</div> -->
            <div class="users-count">Total Alumni: <?php echo $totalAlumni; ?></div>
        </div>

        <ul class="nav users-tabs">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo BASE_URL; ?>/admin/students/students.php">Students</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo BASE_URL; ?>/admin/students/alumni.php">Alumni</a>
            </li>
        </ul>

        <?php if ($flashMsg !== '') { ?>
            <div class="alert alert-<?php echo htmlspecialchars($flashType, ENT_QUOTES, 'UTF-8'); ?> py-2 mb-3">
                <?php echo htmlspecialchars($flashMsg, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <div class="users-card users-filter-card">
            <div class="users-card-body">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
                    <div>
                        <h4 class="users-section-title">Filter alumni</h4>
                        <!-- <p class="users-section-copy">Search alumni by batch, section, status, username, email, or roll details.</p> -->
                    </div>
                </div>
                <form method="get" class="row g-3 align-items-end">
                    <div class="col-12 col-sm-6 col-lg-2">
                        <label class="form-label users-form-label mb-1">Status</label>
                        <select name="status" class="form-select">
                            <option value="" <?php echo $status === '' ? 'selected' : ''; ?>>All</option>
                            <option value="0" <?php echo $status === '0' ? 'selected' : ''; ?>>Pending</option>
                            <option value="1" <?php echo $status === '1' ? 'selected' : ''; ?>>Active</option>
                            <option value="2" <?php echo $status === '2' ? 'selected' : ''; ?>>Inactive</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <label class="form-label users-form-label mb-1">Batch</label>
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
                        <label class="form-label users-form-label mb-1">Section</label>
                        <select name="sectionId" class="form-select" <?php echo $batchId > 0 ? '' : 'disabled'; ?>>
                            <option value="">All</option>
                            <?php foreach ($sections as $s) { ?>
                                <option value="<?php echo (int)$s['id']; ?>" <?php echo ((int)$s['id'] === $sectionId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$s['section_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                        <!-- <div class="form-text">Select a batch to load sections.</div> -->
                    </div>
                    <div class="col-12 col-lg-3">
                        <label class="form-label users-form-label mb-1">Search</label>
                        <input type="text" name="q" class="form-control" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>" placeholder="username / email / roll">
                    </div>
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <a class="btn btn-outline-secondary px-4" href="<?php echo BASE_URL; ?>/admin/students/alumni.php">Reset</a>
                        <button class="btn btn-primary px-4" type="submit">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="users-card">
            <div class="users-card-body">
                <div class="users-table-toolbar">
                    <div class="users-toolbar-note"><?php echo $totalAlumni; ?> alumni user(s)</div>
                    <form method="post" class="d-flex align-items-center gap-2 m-0">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="bulk_remove_from_alumni">
                        <input type="hidden" name="user_ids" id="bulkAlumniUserIds" value="">
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return submitBulkRemove();">Remove Selected From Alumni</button>
                    </form>
                </div>
                <div class="users-table-wrap">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th style="width:44px;">
                                        <input type="checkbox" class="form-check-input" id="selectAllAlumni" onclick="toggleAllAlumni(this)">
                                    </th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Batch</th>
                                    <th>Section</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($users)) { ?>
                                    <tr><td colspan="7" class="users-empty">No alumni found.</td></tr>
                                <?php } else { ?>
                                    <?php foreach ($users as $u) { ?>
                                        <?php
                                            $uId = (int)($u['id'] ?? 0);
                                            $statusVal = (int)($u['status'] ?? 0);
                                            $statusLabel = $statusVal === 1 ? 'Active' : ($statusVal === 2 ? 'Inactive' : 'Pending');
                                            $statusClass = $statusVal === 1 ? 'text-bg-success' : ($statusVal === 2 ? 'text-bg-secondary' : 'text-bg-warning');
                                            $username = (string)($u['username'] ?? '');
                                            $email = (string)($u['mail_id'] ?? '');
                                            $batchName = (string)($u['batch_name'] ?? '');
                                            $sectionName = (string)($u['section_name'] ?? '');
                                            $origSectionName = (string)($u['original_section_name'] ?? '');
                                            $origSectionLabel = (string)($u['original_section_label'] ?? '');
                                            $displaySection = $origSectionName !== '' ? $origSectionName : ($sectionName !== '' ? $sectionName : ($origSectionLabel !== '' ? ('Section-' . $origSectionLabel) : ''));
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="form-check-input alumni-select" value="<?php echo $uId; ?>">
                                            </td>
                                            <td>
                                                <span class="users-name"><?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></span>
                                                <span class="users-role-pill">Alumni</span>
                                            </td>
                                            <td><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($batchName, ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars($displaySection !== '' ? $displaySection : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                                            <td class="text-end">
                                                <div class="users-table-actions">
                                                    <?php if ($statusVal !== 1) { ?>
                                                        <form method="post" class="m-0">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                            <input type="hidden" name="action" value="approve">
                                                            <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                            <button class="btn btn-success btn-sm" type="submit">Approve</button>
                                                        </form>
                                                    <?php } ?>

                                                    <?php if ($statusVal === 1) { ?>
                                                        <form method="post" class="m-0">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                            <input type="hidden" name="action" value="deactivate">
                                                            <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                            <button class="btn btn-outline-secondary btn-sm" type="submit">Deactivate</button>
                                                        </form>
                                                    <?php } elseif ($statusVal === 2) { ?>
                                                        <form method="post" class="m-0">
                                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                            <input type="hidden" name="action" value="activate">
                                                            <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                            <button class="btn btn-outline-primary btn-sm" type="submit">Activate</button>
                                                        </form>
                                                    <?php } ?>

                                                    <form method="post" class="m-0">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="action" value="reset_password">
                                                        <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                        <button class="btn btn-outline-dark btn-sm" type="submit">Reset Password</button>
                                                    </form>

                                                    <form method="post" class="m-0">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="action" value="remove_from_alumni">
                                                        <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                        <button class="btn btn-outline-danger btn-sm" type="submit">Remove</button>
                                                    </form>

                                                    <form method="post" class="m-0" onsubmit="return confirm('Delete this user permanently?');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="action" value="delete">
                                                        <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                        <button class="btn btn-danger btn-sm" type="submit">Delete</button>
                                                    </form>
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
</div>

<script>
function getSelectedAlumniIds() {
    const ids = [];
    document.querySelectorAll('.alumni-select:checked').forEach((cb) => {
        const val = (cb && cb.value) ? String(cb.value).trim() : '';
        if (val) ids.push(val);
    });
    return ids;
}

function toggleAllAlumni(master) {
    const checked = !!(master && master.checked);
    document.querySelectorAll('.alumni-select').forEach((cb) => { cb.checked = checked; });
}

function submitBulkRemove() {
    const ids = getSelectedAlumniIds();
    if (ids.length === 0) {
        alert('Select at least one alumni user first.');
        return false;
    }
    document.getElementById('bulkAlumniUserIds').value = ids.join(',');
    return true;
}
</script>

<?php include_once(__DIR__ . '/../layout/footer.php'); ?>


