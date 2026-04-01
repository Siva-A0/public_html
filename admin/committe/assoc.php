<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
session_start();

if (!isset($_SESSION['adminId'])) {
    header('Location: ../index.php');
    exit;
}

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

$fcObj = new DataFunctions();

$tbComtCtg = TB_COMT_CATEG;
$tbComt    = TB_COMMITTEE;

$status = trim((string)($_GET['status'] ?? ''));
$message = '';
if ($status === 'member_added') {
    $message = 'Association member added successfully.';
} elseif ($status === 'member_updated') {
    $message = 'Association member updated successfully.';
} elseif ($status === 'member_deleted') {
    $message = 'Association member deleted successfully.';
}

if (isset($_POST['delete_member'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: assoc.php');
        exit;
    }

    $memberId = (int)($_POST['member_id'] ?? 0);
    if ($memberId > 0) {
        $memberRows = $fcObj->getCommitteeMemberById($tbComt, $memberId);
        if (!empty($memberRows)) {
            $memberImage = trim((string)($memberRows[0]['member_image'] ?? ''));
            $deleted = $fcObj->deleteCommitteeMember($tbComt, $memberId);
            if ($deleted) {
                if ($memberImage !== '') {
                    $imagePath = ROOT_PATH . '/public/assets/images/students/' . $memberImage;
                    if (is_file($imagePath)) {
                        @unlink($imagePath);
                    }
                }
                header('Location: assoc.php?status=member_deleted');
                exit;
            }
        }
    }
}

include_once('../layout/main_header.php');

$ComtCateg = $fcObj->getComiteCatg($tbComtCtg);
$categoryCnt = sizeof($ComtCateg);

$CmtMemDet = array();

for ($i = 0; $i < $categoryCnt; $i++) {
    $categoryId = $ComtCateg[$i]['id'];
    $CmtMemDet[$i] = $fcObj->getCmtMembers($tbComt, $categoryId);
}
?>

<?php
$totalMembers = 0;
for ($i = 0; $i < $categoryCnt; $i++) {
    $totalMembers += !empty($CmtMemDet[$i]) ? count($CmtMemDet[$i]) : 0;
}
?>

<style>
    .committee-grid {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 16px !important;
    }

    .committee-category-head {
        cursor: pointer;
    }

    .committee-category-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        border-radius: 999px;
        border: 1px solid #d8e3ef;
        background: #ffffff;
        color: #173d69;
        flex-shrink: 0;
        transition: transform 0.2s ease, background-color 0.2s ease, border-color 0.2s ease;
    }

    .committee-category.is-collapsed .committee-category-toggle {
        transform: rotate(-90deg);
    }

    .committee-category.is-collapsed .committee-members-grid {
        display: none;
    }
</style>

<div class="committee-header">
    <h3 class="committee-page-title">AIML Committee</h3>
    <p class="committee-subtitle"></p>
    <div class="committee-stats">
        <span class="committee-stat-pill">
            <i class="bi bi-diagram-3 me-1"></i>
            <?php echo (int)$categoryCnt; ?> Categories
        </span>
        <span class="committee-stat-pill">
            <i class="bi bi-people me-1"></i>
            <?php echo (int)$totalMembers; ?> Total Members
        </span>
    </div>
    <div class="mt-3 d-flex gap-2 flex-wrap">
        <a href="categories.php" class="btn btn-outline-secondary">
            <i class="bi bi-tags me-1"></i>
            Manage Categories
        </a>
        <a href="addmem.php" class="btn committee-add-btn">
            <i class="bi bi-plus-circle me-1"></i>
            Add Member by Category
        </a>
    </div>
</div>

<?php if ($message !== '') { ?>
    <div class="alert alert-success mb-4">
        <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
    </div>
<?php } ?>

<div class="card committee-shell border-0">
    <div class="committee-body">

        <?php if ($categoryCnt === 0) { ?>
            <div class="alert alert-warning mb-4">
                No association categories found yet. Create categories first, then add members under each category.
            </div>
        <?php } ?>

        <?php if ($categoryCnt > 0) { ?>
            <div class="committee-grid">

            <?php for ($j = 0; $j < $categoryCnt; $j++) { ?>
                <?php $memberCount = !empty($CmtMemDet[$j]) ? sizeof($CmtMemDet[$j]) : 0; ?>

                <div class="committee-category is-collapsed">
                    <div class="committee-category-head" role="button" tabindex="0" aria-expanded="false">
                        <div class="d-flex align-items-center gap-2">
                        <h5 class="committee-category-title">
                            <?php echo $ComtCateg[$j]['category_name']; ?>
                        </h5>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                        <span class="committee-member-count"><?php echo $memberCount; ?> Members</span>
                        <span class="committee-category-toggle" aria-hidden="true">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                        </div>
                    </div>

                    <div class="committee-members-grid">

                        <?php if (!empty($CmtMemDet[$j])) { ?>

                            <?php foreach ($CmtMemDet[$j] as $member) { ?>
                                <?php
                                    $memberName = trim((string)($member['member_name'] ?? ''));
                                    $memberAbout = (string)($member['member_about'] ?? '');
                                    $memberImage = trim((string)($member['member_image'] ?? ''));
                                    $imagePath = BASE_URL.'/public/assets/images/students/'.rawurlencode($memberImage !== '' ? $memberImage : 'default.png');
                                    $initial = strtoupper(substr($memberName !== '' ? $memberName : 'M', 0, 1));
                                ?>

                                <div>
                                    <div class="card committee-member-card border-0 h-100">

                                        <div class="card-body">
                                            <div class="committee-member-media">
                                                <img 
                                                    src="<?php echo $imagePath; ?>"
                                                    class="committee-member-img"
                                                    alt="<?php echo htmlspecialchars($memberName); ?>"
                                                    onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';"
                                                >
                                                <span class="committee-member-avatar" style="display:none;"><?php echo htmlspecialchars($initial, ENT_QUOTES, 'UTF-8'); ?></span>
                                            </div>

                                            <div class="committee-member-content">
                                                <h6 class="fw-semibold mb-1">
                                                    <?php echo htmlspecialchars($memberName !== '' ? $memberName : 'Member'); ?>
                                                </h6>

                                                <p class="committee-member-meta">
                                                    <?php echo htmlspecialchars($memberAbout !== '' ? $memberAbout : 'No profile details available.'); ?>
                                                </p>

                                                <div class="d-flex gap-2 flex-wrap mt-3">
                                                    <a href="addmem.php?member=<?php echo (int)$member['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    <form method="POST" action="assoc.php" onsubmit="return confirm('Delete this member?');">
                                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <input type="hidden" name="member_id" value="<?php echo (int)$member['id']; ?>">
                                                        <button type="submit" name="delete_member" class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                            <?php } ?>

                        <?php } else { ?>

                            <div class="committee-empty">
                                
                            </div>

                        <?php } ?>

                    </div>
                </div>

            <?php } ?>
            </div>

        <?php } else { ?>

            <p class="text-muted"></p>

        <?php } ?>

        <div class="mt-5 d-flex gap-2 flex-wrap">
            <a href="addmem.php" class="btn committee-add-btn">
                <i class="bi bi-plus-circle me-1"></i>
                Add Committee Member
            </a>
        </div>

    </div>
</div>

<?php include_once('../layout/footer.php'); ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var headers = document.querySelectorAll('.committee-category-head');
    headers.forEach(function (header) {
        var category = header.closest('.committee-category');
        if (!category) {
            return;
        }

        var toggleCategory = function () {
            var collapsed = category.classList.toggle('is-collapsed');
            header.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
        };

        header.addEventListener('click', toggleCategory);
        header.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleCategory();
            }
        });
    });
});
</script>
