
<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
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

<style type="text/css">
    .staff-page {
        --staff-primary: #173d69;
        --staff-primary-deep: #13345a;
        --staff-accent: #f0b323;
        --staff-accent-deep: #d79a12;
        --staff-accent-soft: #fff5da;
        --staff-surface: #eef4fa;
        --staff-card: #ffffff;
        --staff-border: #d9e3ef;
        --staff-border-strong: #c8d6e6;
        --staff-text: #163a61;
        --staff-muted: #6b819c;
        padding-bottom: 14px;
    }

    .staff-page .page-shell {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--staff-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .staff-page .page-header {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--staff-border);
        border-radius: 22px;
        padding: 24px 26px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--staff-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    }

    .staff-page .page-header::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--staff-accent), var(--staff-accent-deep));
    }

    .staff-page .page-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--staff-primary-deep);
        margin: 0;
    }

    .staff-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--staff-muted);
        font-size: 15px;
    }

    .staff-page .page-pills {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .staff-page .page-pill {
        border: 1px solid #ead290;
        border-radius: 999px;
        padding: 7px 12px;
        background: var(--staff-accent-soft);
        color: #8b6510;
        font-size: 13px;
        font-weight: 700;
    }

    .staff-page .add-staff-btn {
        border: 0;
        border-radius: 12px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--staff-primary-deep), var(--staff-primary));
        color: #fff;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 10px 20px rgba(19, 52, 90, 0.24);
    }

    .staff-page .add-staff-btn:hover {
        color: #fff;
        filter: brightness(1.06);
    }

    .staff-page .manage-categories-btn {
        border-radius: 999px;
        padding: 12px 20px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        border-color: var(--staff-border-strong);
        color: var(--staff-primary);
        background: #f9fbfe;
    }

    .staff-page .staff-group {
        border: 1px solid var(--staff-border);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
        background: var(--staff-card);
    }

    .staff-page .staff-group-header {
        background: linear-gradient(90deg, #f9fbfe, #f2f6fb);
        color: var(--staff-primary-deep);
        font-size: 20px;
        font-weight: 700;
        padding: 14px 18px;
        border-bottom: 1px solid var(--staff-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .staff-page .staff-count {
        font-size: 12px;
        font-weight: 700;
        color: #8b6510;
        background: var(--staff-accent-soft);
        border-radius: 999px;
        padding: 4px 10px;
    }

    .staff-page .staff-group-body {
        background: #ffffff;
        padding: 18px;
    }

    .staff-page .staff-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 14px;
    }

    .staff-page .staff-card {
        border: 1px solid #e0e8f1;
        border-radius: 14px;
        box-shadow: 0 6px 14px rgba(15, 23, 42, 0.05);
        transition: transform .2s ease, box-shadow .2s ease;
        background: #fff;
    }

    .staff-page .staff-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.1);
    }

    .staff-page .staff-card .card-body {
        display: flex;
        align-items: center;
        gap: 14px;
        text-align: left;
    }

    .staff-page .staff-media {
        flex-shrink: 0;
    }

    .staff-page .staff-image,
    .staff-page .staff-avatar {
        width: 104px;
        height: 104px;
        border-radius: 50%;
    }

    .staff-page .staff-image {
        object-fit: cover;
        border: 4px solid var(--staff-accent-soft);
    }

    .staff-page .staff-avatar {
        display: none;
        align-items: center;
        justify-content: center;
        border: 4px solid var(--staff-accent-soft);
        background: linear-gradient(135deg, var(--staff-primary), var(--staff-primary-deep));
        color: #fff;
        font-size: 30px;
        font-weight: 800;
    }

    .staff-page .staff-name {
        font-size: 19px;
        font-weight: 700;
        color: var(--staff-primary-deep);
        margin: 0 0 4px;
    }

    .staff-page .staff-qual {
        color: var(--staff-muted);
        font-size: 14px;
        margin-bottom: 6px;
    }

    .staff-page .staff-designation {
        color: var(--staff-primary);
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .staff-page .staff-action {
        border-radius: 10px;
        font-weight: 700;
        padding: 6px 12px;
        font-size: 12px;
    }

    .staff-page .staff-empty {
        border: 1px dashed #cfd8e3;
        border-radius: 12px;
        background: #f8fafc;
        color: var(--staff-muted);
        font-weight: 600;
        padding: 14px;
        text-align: center;
    }

    @media (max-width: 768px) {
        .staff-page .page-title {
            font-size: 26px;
        }

        .staff-page .staff-card .card-body {
            flex-direction: column;
            text-align: center;
        }
    }
</style>

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
                                    <img src="../../public/assets/images/staff/<?php echo $staffImage; ?>"
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
