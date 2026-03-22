<?php
if (session_id() == '') {
    session_start();
}
require_once(__DIR__ . '/../../config.php');

if (!isset($_SESSION['adminId'])) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit;
}

// Split views: keep this legacy route, but redirect to the dedicated Alumni page.
$qs = $_SERVER['QUERY_STRING'] ?? '';
header('Location: ' . BASE_URL . '/admin/users/alumni.php' . ($qs !== '' ? ('?' . $qs) : ''));
exit;

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbUsers = TB_USERS;
$tbBatch = TB_BATCH;
$tbClass = TB_CLASS;
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
    $url = BASE_URL . '/admin/users/alumni_users.php' . ($qs !== '' ? ('?' . $qs) : '');
    header('Location: ' . $url);
    exit;
}

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
        foreach (preg_split('/\\s*,\\s*/', $bulkIdsRaw) as $idPart) {
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

    if ($action === 'remove_from_alumni') {
        $ok = $fcObj->adminRemoveUserFromAlumniAndRestore($tbUsers, $userId);
        admin_alumni_flash_set($ok !== false ? 'User removed from Alumni.' : 'Unable to remove Alumni status.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    if ($action === 'reset_password') {
        $tempPassword = substr(bin2hex(random_bytes(6)), 0, 12);
        $ok = $fcObj->adminUpdateUserPasswordById($tbUsers, $userId, $fcObj->hashPassword($tempPassword));
        if ($ok !== false) {
            admin_alumni_flash_set('Temporary password set for user ID ' . $userId . ': ' . $tempPassword, 'success');
        } else {
            admin_alumni_flash_set('Unable to reset password.', 'danger');
        }
        admin_alumni_redirect_self();
    }

    if ($action === 'deactivate') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 2);
        admin_alumni_flash_set($ok !== false ? 'User deactivated.' : 'Unable to deactivate user.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    if ($action === 'activate') {
        $ok = $fcObj->adminUpdateUserStatus($tbUsers, $userId, 1);
        admin_alumni_flash_set($ok !== false ? 'User activated.' : 'Unable to activate user.', $ok !== false ? 'success' : 'danger');
        admin_alumni_redirect_self();
    }

    admin_alumni_flash_set('Unknown action.', 'danger');
    admin_alumni_redirect_self();
}

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
$classes = $fcObj->getClasses($tbClass);
$sections = array();
if ($batchId > 0 && $classId > 0) {
    $sections = $fcObj->getSections($tbSection, $classId, $batchId);
}

$filters = array(
    'limit' => 500,
    'q' => $q,
    'is_alumni' => 1
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

list($flashMsg, $flashType) = admin_alumni_flash_get();

include_once(__DIR__ . '/../layout/main_header.php');
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
        <div>
            <h3 class="mb-1" style="font-weight:800; letter-spacing:-0.4px;">Alumni Users</h3>
            <div class="text-muted">Accounts that can log in and are marked as alumni.</div>

        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="<?php echo BASE_URL; ?>/admin/users/manage_users.php" class="btn btn-outline-secondary">Back to Users</a>
        </div>
    </div>

    <?php if ($flashMsg !== '') { ?>
        <div class="alert alert-<?php echo htmlspecialchars($flashType, ENT_QUOTES, 'UTF-8'); ?> py-2">
            <?php echo htmlspecialchars($flashMsg, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php } ?>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-12 col-sm-6 col-lg-2">
                    <label class="form-label text-muted small mb-1">Status</label>
                    <select name="status" class="form-select">
                        <option value="" <?php echo $status === '' ? 'selected' : ''; ?>>All</option>
                        <option value="0" <?php echo $status === '0' ? 'selected' : ''; ?>>Pending</option>
                        <option value="1" <?php echo $status === '1' ? 'selected' : ''; ?>>Active</option>
                        <option value="2" <?php echo $status === '2' ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-lg-3">
                    <label class="form-label text-muted small mb-1">Batch</label>
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
                    <label class="form-label text-muted small mb-1">Class</label>
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
                    <label class="form-label text-muted small mb-1">Section</label>
                    <select name="sectionId" class="form-select">
                        <option value="">All</option>
                        <?php foreach ($sections as $s) { ?>
                            <option value="<?php echo (int)$s['id']; ?>" <?php echo ((int)$s['id'] === $sectionId) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars((string)$s['section_name'], ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <div class="form-text">Pick batch+class to load sections.</div>
                </div>
                <div class="col-12 col-lg-2">
                    <label class="form-label text-muted small mb-1">Search</label>
                    <input type="text" name="q" class="form-control" value="<?php echo htmlspecialchars($q, ENT_QUOTES, 'UTF-8'); ?>" placeholder="username / email / roll">
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>/admin/users/alumni_users.php">Reset</a>
                    <button class="btn btn-primary" type="submit">Apply Filters</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                <div class="text-muted small"><?php echo (int)count($users); ?> alumni user(s)</div>
                <form method="post" class="d-flex align-items-center gap-2 m-0">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="action" value="bulk_remove_from_alumni">
                    <input type="hidden" name="user_ids" id="bulkAlumniUserIds" value="">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return submitBulkRemoveAlumni();">
                        Remove Selected From Alumni
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:44px;">
                                <input type="checkbox" class="form-check-input" id="selectAllAlumniUsers" onclick="toggleAllAlumniUsers(this)">
                            </th>
                            <th style="min-width:70px;">ID</th>
                            <th style="min-width:220px;">User</th>
                            <th style="min-width:240px;">Contact</th>
                            <th style="min-width:260px;">Academic</th>
                            <th style="min-width:110px;">Status</th>
                            <th style="min-width:240px;" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)) { ?>
                            <tr><td colspan="7" class="text-muted py-4 text-center">No alumni users found.</td></tr>
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
                                    $origClassName = trim((string)($u['original_class_name'] ?? ''));
                                    $origSectionName = trim((string)($u['original_section_name'] ?? ''));
                                    $origSectionLabel = trim((string)($u['original_section_label'] ?? ''));
                                    $email = (string)($u['mail_id'] ?? '');
                                    $roll = (string)($u['admission_id'] ?? '');
                                ?>
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input alumni-user-select" value="<?php echo $uId; ?>">
                                    </td>
                                    <td><?php echo $uId; ?></td>
                                    <td>
                                        <div class="fw-bold"><?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <?php if ($username !== '') { ?>
                                            <div class="text-muted small">@<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <?php } ?>
                                        <span class="badge text-bg-primary mt-1">Alumni</span>
                                    </td>
                                    <td>
                                        <div class="text-muted small"><?php echo htmlspecialchars($roll !== '' ? $roll : '-', ENT_QUOTES, 'UTF-8'); ?></div>
                                        <?php if ($email !== '') { ?>
                                            <div class="text-muted small text-truncate" style="max-width: 320px;"><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="text-muted small">
                                            <?php echo htmlspecialchars($batchName !== '' ? $batchName : '-', ENT_QUOTES, 'UTF-8'); ?>
                                            &middot; <?php echo htmlspecialchars($className !== '' ? $className : '-', ENT_QUOTES, 'UTF-8'); ?>
                                            &middot; <?php echo htmlspecialchars($sectionName !== '' ? $sectionName : '-', ENT_QUOTES, 'UTF-8'); ?>
                                        </div>
                                        <?php if ($origClassName !== '' || $origSectionName !== '' || $origSectionLabel !== '') { ?>
                                            <div class="text-muted small">
                                                <span class="badge text-bg-light">Original</span>
                                                <?php echo htmlspecialchars(($origClassName !== '' ? $origClassName : '-') . ' · ' . ($origSectionName !== '' ? $origSectionName : ($origSectionLabel !== '' ? ('Section-' . $origSectionLabel) : '-')), ENT_QUOTES, 'UTF-8'); ?>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td><span class="badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                                    <td class="text-end">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a class="btn btn-outline-primary" href="<?php echo BASE_URL; ?>/admin/users/edit_user.php?id=<?php echo $uId; ?>">Edit</a>
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
                                            <form method="post" class="m-0">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="reset_password">
                                                <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                <button class="btn btn-outline-secondary" type="submit" onclick="return confirm('Set a new temporary password for this user?');">Reset PW</button>
                                            </form>
                                            <form method="post" class="m-0">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="action" value="remove_from_alumni">
                                                <input type="hidden" name="user_id" value="<?php echo $uId; ?>">
                                                <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Remove alumni status for this user?');">Remove</button>
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

<script>
function getSelectedAlumniUserIds() {
    const ids = [];
    document.querySelectorAll('.alumni-user-select:checked').forEach((cb) => {
        const val = (cb && cb.value) ? String(cb.value).trim() : '';
        if (val) ids.push(val);
    });
    return ids;
}

function toggleAllAlumniUsers(master) {
    const checked = !!(master && master.checked);
    document.querySelectorAll('.alumni-user-select').forEach((cb) => { cb.checked = checked; });
}

function submitBulkRemoveAlumni() {
    const ids = getSelectedAlumniUserIds();
    if (ids.length === 0) {
        alert('Select at least one alumni user.');
        return false;
    }
    document.getElementById('bulkAlumniUserIds').value = ids.join(',');
    return confirm('Remove alumni status for ' + ids.length + ' user(s)?');
}
</script>

<?php include_once(__DIR__ . '/../layout/footer.php'); ?>
