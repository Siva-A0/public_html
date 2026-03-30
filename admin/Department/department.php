
<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbStaffCateg = TB_STAFF_CATEGORY;
$tbStaff      = TB_STAFF;

$staffCateg = $fcObj->getStaffCategories($tbStaffCateg);
$categoryCnt = sizeof($staffCateg);

for($i=0; $i<$categoryCnt; $i++){
    $categoryId = $staffCateg[$i]['id'];
    $staffDetails[$i] = $fcObj->getStaffDetails($tbStaff, $categoryId);
}
?>

<div class="container-fluid staff-page">

    <?php
        $totalStaff = 0;
        for ($si = 0; $si < $categoryCnt; $si++) {
            $totalStaff += !empty($staffDetails[$si]) ? count($staffDetails[$si]) : 0;
        }
    ?>

    <div class="page-shell">
        <div class="page-header mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <h3 class="page-title">Department Faculty Management</h3>
                <p class="page-subtitle">Manage faculty and non-teaching members category-wise.</p>
                <div class="page-pills">
                    <span class="page-pill"><i class="bi bi-collection me-1"></i><?php echo (int)$categoryCnt; ?> Categories</span>
                    <span class="page-pill"><i class="bi bi-people me-1"></i><?php echo (int)$totalStaff; ?> Total Faculty</span>
                </div>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="categories.php" class="btn btn-outline-primary manage-categories-btn">
                    <i class="bi bi-tags me-1"></i> Manage Categories
                </a>
                <a href="../faculty/addfaculty.php" class="btn add-staff-btn">
                    <i class="bi bi-plus-circle me-1"></i> Add Faculty
                </a>
            </div>
        </div>

        <?php for($j=0; $j<$categoryCnt; $j++) { ?>
            <?php $catStafCnt = sizeof($staffDetails[$j]); ?>

            <div class="card staff-group border-0 mb-4">
                <div class="card-header staff-group-header fw-semibold">
                    <span><?php echo $staffCateg[$j]['category_name']; ?></span>
                    <span class="staff-count"><?php echo (int)$catStafCnt; ?> Members</span>
                </div>

                <div class="card-body staff-group-body">

                    <div class="staff-grid">

                    <?php
                        if ($catStafCnt == 0) {
                    ?>
                        <div>
                            <div class="staff-empty">No faculty members added in this category yet.</div>
                        </div>
                    <?php
                        }

                        for($k=0; $k<$catStafCnt; $k++) {
                            $staff = $staffDetails[$j][$k];
                            $staffImage = rawurlencode((string)$staff['image']);
                            $staffName = trim((string)$staff['first_name'].' '.(string)$staff['last_name']);
                            $staffInitial = strtoupper(substr($staffName !== '' ? $staffName : 'S', 0, 1));
                    ?>

                        <div>
                            <div class="card h-100 staff-card border-0">

                                <div class="card-body">

                                    <div class="staff-media">
                                    <img src="../../public/assets/images/faculty/<?php echo $staffImage; ?>"
                                         class="staff-image"
                                         width="100" height="100"
                                         alt="<?php echo htmlspecialchars($staffName, ENT_QUOTES, 'UTF-8'); ?>"
                                         onerror="this.style.display='none';this.nextElementSibling.style.display='inline-flex';">
                                    <span class="staff-avatar"><?php echo htmlspecialchars($staffInitial, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>

                                    <div class="staff-content">
                                        <h6 class="staff-name">
                                            <?php echo htmlspecialchars($staffName, ENT_QUOTES, 'UTF-8'); ?>
                                        </h6>

                                        <div class="staff-qual">
                                            <?php echo htmlspecialchars(str_replace('\,', ',', (string)$staff['qualification']), ENT_QUOTES, 'UTF-8'); ?>
                                        </div>

                                        <div class="staff-designation">
                                            <?php echo htmlspecialchars((string)$staff['designation'], ENT_QUOTES, 'UTF-8'); ?>
                                        </div>

                                        <div class="d-flex gap-2 flex-wrap">
                                            <a href="../faculty/editfaculty.php?faculty=<?php echo $staff['id']; ?>"
                                               class="btn btn-sm btn-outline-primary staff-action">
                                                Edit
                                            </a>

                                            <a href="../faculty/delete_faculty.php?faculty=<?php echo $staff['id']; ?>"
                                               class="btn btn-sm btn-outline-danger staff-action"
                                               onclick="return confirm('Are you sure you want to delete this faculty member?')">
                                                Delete
                                            </a>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    <?php } ?>

                    </div>

                </div>
            </div>

        <?php } ?>
    </div>

</div>

<?php include_once('../layout/footer.php'); ?>


