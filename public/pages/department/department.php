<?php 
require_once(__DIR__ . '/../../../config.php');

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');


$fcObj = new DataFunctions();

$tbStaffCateg = TB_STAFF_CATEGORY;
$tbStaff      = TB_STAFF;

$staffCateg   = $fcObj->getStaffCategories($tbStaffCateg);
$categoryCnt  = sizeof($staffCateg);

for($i=0; $i<$categoryCnt; $i++){
    $categoryId = $staffCateg[$i]['id'];
    $staffDetails[$i] = $fcObj->getStaffDetails($tbStaff, $categoryId);
}
?>

<style>
    .staff-member-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .staff-member-card .staff-member-image {
        transition: transform 0.25s ease;
    }

    .staff-member-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 14px 30px rgba(15, 30, 52, 0.16) !important;
    }

    .staff-member-card:hover .staff-member-image {
        transform: scale(1.05);
    }
</style>

<div class="container my-5">

    <div class="text-center mb-5">
        <h2 class="fw-bold">AIML Department Team</h2>
        <p class="text-muted">Discover the expertise of our faculty members, technical experts, and dedicated department team.</p>
    </div>

    <?php for($j=0; $j < $categoryCnt; $j++) { ?>

        <div class="mb-5">
            <h4 class="mb-4 border-start border-4 border-warning ps-3">
                <?php echo $staffCateg[$j]['category_name']; ?>
            </h4>

            <div class="row g-4">

                <?php
                $catStafCnt = sizeof($staffDetails[$j]);

                for($k=0; $k<$catStafCnt; $k++) {

                    $image = $staffDetails[$j][$k]['image'];
                    $name  = $staffDetails[$j][$k]['first_name'];
                ?>

                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm border-0 h-100 text-center staff-member-card">

                        <div class="pt-4">
                            <a href="<?php echo BASE_URL; ?>/public/pages/department/view_faculty.php?faculty=<?php echo $staffDetails[$j][$k]['id']; ?>">
                                <img 
                                    src="<?php echo BASE_URL; ?>/public/assets/images/staff/<?php echo $image; ?>" 
                                    class="rounded-circle shadow staff-member-image"
                                    width="120"
                                    height="120"
                                    style="object-fit:cover;"
                                >
                            </a>
                        </div>

                        <div class="card-body">
                            <h6 class="fw-semibold mb-1">
                                <?php echo $name; ?>
                            </h6>

                            <div class="small text-muted">
                                <?php echo str_replace('\,', ',', $staffDetails[$j][$k]['qualification']); ?>
                            </div>

                            <div class="small text-warning fw-semibold">
                                <?php echo $staffDetails[$j][$k]['designation']; ?>
                            </div>
                        </div>

                    </div>
                </div>

                <?php } ?>

            </div>
        </div>

    <?php } ?>

</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
