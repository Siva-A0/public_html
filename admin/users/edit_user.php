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

$userId = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_POST['id'] ?? 0);
$userRows = $fcObj->adminGetUserById($tbUsers, $userId);
$user = !empty($userRows) ? $userRows[0] : null;

$message = '';
$messageType = 'success';

$batchId = (int)($user['batch_id'] ?? 0);
$classId = (int)($user['class_id'] ?? 0);
$sectionId = (int)($user['section_id'] ?? 0);

if (isset($_POST['save'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please try again.';
        $messageType = 'danger';
    } else {
        $batchId = (int)($_POST['batchId'] ?? 0);
        $classId = (int)($_POST['classId'] ?? 0);
        $sectionId = (int)($_POST['sectionId'] ?? 0);

        if ($batchId <= 0 || $classId <= 0 || $sectionId <= 0) {
            $message = 'Please select Batch, Class, and Section.';
            $messageType = 'danger';
        } else {
            $ok = $fcObj->adminUpdateUserAcademic($tbUsers, $userId, $batchId, $sectionId);
            if ($ok !== false) {
                $_SESSION['admin_users_flash_msg'] = 'User academic mapping updated.';
                $_SESSION['admin_users_flash_type'] = 'success';
                header('Location: ' . BASE_URL . '/admin/users/students.php');
                exit;
            }
            $message = 'Unable to update. Make sure the section belongs to the selected batch.';
            $messageType = 'danger';
        }
    }
}

$batches = $fcObj->getBatches($tbBatch);
$classes = $fcObj->getClasses($tbClass);
$sections = array();
if ($batchId > 0 && $classId > 0) {
    $sections = $fcObj->getSections($tbSection, $classId, $batchId);
}

$studentName = '';
if ($user !== null) {
    $studentName = trim((string)($user['firstname'] ?? '') . ' ' . (string)($user['lastname'] ?? ''));
    if ($studentName === '') {
        $studentName = (string)($user['username'] ?? '');
    }
}

include_once(__DIR__ . '/../layout/main_header.php');

if ($user === null) {
    echo '<div class="container-fluid"><div class="alert alert-danger">User not found.</div></div>';
    include_once(__DIR__ . '/../layout/footer.php');
    exit;
}

?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
        <div>
            <h3 class="mb-1" style="font-weight:800;letter-spacing:-0.4px;">Edit Student</h3>
            <div class="text-muted"><?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?> (ID <?php echo (int)$userId; ?>)</div>
        </div>
        <a href="<?php echo BASE_URL; ?>/admin/users/students.php" class="btn btn-outline-secondary">Back</a>
    </div>

    <?php if ($message !== '') { ?>
        <div class="alert alert-<?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?> py-2">
            <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    <?php } ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body" style="max-width: 820px;">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="text-muted small">Roll No</div>
                    <div class="fw-semibold"><?php echo htmlspecialchars((string)($user['admission_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">Email</div>
                    <div class="fw-semibold"><?php echo htmlspecialchars((string)($user['mail_id'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                </div>
            </div>

            <form method="post" action="">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="id" value="<?php echo (int)$userId; ?>">

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Batch</label>
                        <select name="batchId" id="batchId" class="form-select" required>
                            <option value="">Select</option>
                            <?php foreach ($batches as $b) { ?>
                                <option value="<?php echo (int)$b['id']; ?>" <?php echo ((int)$b['id'] === $batchId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$b['batch'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Class</label>
                        <select name="classId" id="classId" class="form-select" required>
                            <option value="">Select</option>
                            <?php foreach ($classes as $c) { ?>
                                <option value="<?php echo (int)$c['id']; ?>" <?php echo ((int)$c['id'] === $classId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$c['class_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Section</label>
                        <select name="sectionId" id="sectionId" class="form-select" required <?php echo ($batchId > 0 && $classId > 0) ? '' : 'disabled'; ?>>
                            <option value="">Select</option>
                            <?php foreach ($sections as $s) { ?>
                                <option value="<?php echo (int)$s['id']; ?>" <?php echo ((int)$s['id'] === $sectionId) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string)$s['section_name'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                    <a class="btn btn-outline-secondary" href="<?php echo BASE_URL; ?>/admin/users/students.php">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function(){
    var batchEl = document.getElementById('batchId');
    var classEl = document.getElementById('classId');
    var sectionEl = document.getElementById('sectionId');

    function refreshSections(){
        if (!batchEl || !classEl || !sectionEl) return;

        var batchId = parseInt(batchEl.value || '0', 10);
        var classId = parseInt(classEl.value || '0', 10);

        sectionEl.innerHTML = '<option value=\"\">Select</option>';
        sectionEl.disabled = true;

        if (!batchId || !classId) return;

        fetch('<?php echo BASE_URL; ?>/admin/users/sections.php?batchId=' + encodeURIComponent(batchId) + '&classId=' + encodeURIComponent(classId), {
            credentials: 'same-origin'
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            if (!data || !data.ok || !Array.isArray(data.sections)) return;
            data.sections.forEach(function(sec){
                var opt = document.createElement('option');
                opt.value = String(sec.id);
                opt.textContent = String(sec.name || sec.code || ('Section ' + sec.id));
                sectionEl.appendChild(opt);
            });
        })
        .catch(function(){})
        .finally(function(){
            sectionEl.disabled = false;
        });
    }

    if (batchEl) batchEl.addEventListener('change', refreshSections);
    if (classEl) classEl.addEventListener('change', refreshSections);
})();
</script>

<?php include_once(__DIR__ . '/../layout/footer.php'); ?>
