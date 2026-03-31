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
    .department-directory-wrap {
        padding-top: 26px;
        padding-bottom: 56px;
    }

    .department-team-page {
        --dept-primary: #163a61;
        --dept-primary-deep: #102c4c;
        --dept-accent: #f0b323;
        --dept-accent-soft: #fff3d1;
        --dept-surface: #eef4fa;
        --dept-surface-soft: #f8fbfe;
        --dept-border: #d8e4ef;
        --dept-text: #23415f;
        --dept-muted: #7187a0;
        position: relative;
        padding: 0;
        background:
            radial-gradient(circle at top right, rgba(240, 179, 35, 0.08), transparent 22%),
            linear-gradient(180deg, #f7fbff 0%, #eef4fa 100%);
        border-radius: 28px;
    }

    .department-team-hero {
        position: relative;
        overflow: hidden;
        margin-bottom: 28px;
        padding: 26px 28px 24px;
        border: 1px solid var(--dept-border);
        border-radius: 24px;
        background:
            radial-gradient(circle at top right, rgba(240, 179, 35, 0.12), transparent 26%),
            linear-gradient(135deg, #ffffff 0%, var(--dept-surface-soft) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.05);
    }

    .department-team-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 7px;
        background: linear-gradient(180deg, var(--dept-accent), #d79a12);
    }

    .department-team-title {
        margin: 0;
        color: var(--dept-primary-deep);
        font-size: clamp(22px, 3vw, 28px);
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .department-team-subtitle {
        margin: 8px 0 0;
        max-width: 520px;
        color: var(--dept-muted);
        font-size: 13px;
        line-height: 1.6;
    }

    .department-team-grid {
        display: grid;
        gap: 26px;
    }

    .staff-category-section {
        border: 1px solid var(--dept-border);
        border-radius: 24px;
        padding: 22px;
        background: rgba(255, 255, 255, 0.76);
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.04);
    }

    .staff-category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }

    .staff-category-heading {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 15px;
        border-radius: 999px;
        background: rgba(22, 58, 97, 0.08);
        color: var(--dept-primary);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .staff-category-heading::before {
        content: "";
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: linear-gradient(135deg, var(--dept-accent), #d79a12);
        box-shadow: 0 0 0 6px rgba(240, 179, 35, 0.16);
    }

    .staff-category-count {
        color: var(--dept-muted);
        font-size: 13px;
        font-weight: 700;
    }

    .staff-member-card {
        overflow: hidden;
        position: relative;
        border: 1px solid #cfdae7;
        border-radius: 18px;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.05);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    }

    .staff-member-card::before {
        content: "";
        position: absolute;
        inset: 0 0 auto 0;
        height: 3px;
        background: linear-gradient(90deg, #d6e3f0 0%, var(--dept-accent) 50%, #d6e3f0 100%);
        opacity: 0.9;
    }

    .staff-member-card:hover {
        transform: translateY(-4px);
        border-color: #b8cddd;
        box-shadow: 0 14px 28px rgba(15, 30, 52, 0.1) !important;
    }

    .staff-member-link {
        color: inherit;
        text-decoration: none;
    }

    .staff-member-media {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 126px;
        padding: 18px 18px 8px;
        background:
            linear-gradient(180deg, #f7fafe 0%, #edf3f9 100%);
    }

    .staff-member-image,
    .staff-member-avatar {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        border: 4px solid rgba(255, 255, 255, 0.96);
        box-shadow: 0 10px 20px rgba(22, 58, 97, 0.12);
        transition: transform 0.25s ease;
    }

    .staff-member-card:hover .staff-member-image,
    .staff-member-card:hover .staff-member-avatar {
        transform: scale(1.05);
    }

    .staff-member-image {
        object-fit: cover;
        background: #dbe6f1;
    }

    .staff-member-avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--dept-primary), var(--dept-primary-deep));
        color: #ffffff;
        font-size: 24px;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .staff-member-body {
        padding: 0 18px 18px;
        position: relative;
    }

    .staff-member-name {
        margin: 0;
        color: var(--dept-primary-deep);
        font-size: 15px;
        font-weight: 800;
        line-height: 1.4;
    }

    .staff-member-qualification {
        margin-top: 8px;
        color: var(--dept-muted);
        font-size: 13px;
        line-height: 1.55;
        min-height: 40px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e8eef5;
    }

    .staff-member-designation {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-top: 12px;
        padding: 7px 11px;
        border-radius: 999px;
        background: linear-gradient(180deg, #eef4fb 0%, #e4edf7 100%);
        border: 1px solid #c8d7e7;
        color: #21486d;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .staff-member-designation::before {
        content: "";
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--dept-accent), #d79a12);
        box-shadow: 0 0 0 4px rgba(240, 179, 35, 0.12);
    }

    .staff-empty-state {
        padding: 24px;
        border: 1px dashed var(--dept-border);
        border-radius: 18px;
        background: rgba(255, 255, 255, 0.72);
        color: var(--dept-muted);
        text-align: center;
    }

    @media (max-width: 767px) {
        .department-directory-wrap {
            padding-top: 18px;
            padding-bottom: 40px;
        }

        .department-team-page {
            border-radius: 20px;
        }

        .department-team-hero {
            padding: 22px 18px 20px;
            border-radius: 20px;
        }

        .department-team-meta,
        .staff-category-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .staff-category-section {
            padding: 18px;
            border-radius: 20px;
        }

        .staff-member-media {
            min-height: 118px;
        }
    }
</style>

<?php
$totalFaculty = 0;
for ($s = 0; $s < $categoryCnt; $s++) {
    $totalFaculty += !empty($staffDetails[$s]) ? count($staffDetails[$s]) : 0;
}
?>

<div class="container department-directory-wrap">
    <div class="department-team-page">

    <div class="department-team-hero">
        <h2 class="department-team-title">Department Faculty</h2>
        <p class="department-team-subtitle">Faculty listing for the AIML department.</p>
    </div>

    <div class="department-team-grid">
    <?php for($j=0; $j < $categoryCnt; $j++) { ?>

        <?php $catStafCnt = sizeof($staffDetails[$j]); ?>

        <section class="staff-category-section">
            <div class="staff-category-header">
                <h4 class="staff-category-heading">
                    <?php echo htmlspecialchars((string)$staffCateg[$j]['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                </h4>
                <div class="staff-category-count"><?php echo (int)$catStafCnt; ?> member<?php echo $catStafCnt === 1 ? '' : 's'; ?></div>
            </div>

            <div class="row g-4">

                <?php
                if ($catStafCnt === 0) {
                ?>
                <div class="col-12">
                    <div class="staff-empty-state">Faculty profiles for this category will appear here once records are added.</div>
                </div>
                <?php
                }

                for($k=0; $k<$catStafCnt; $k++) {

                    $image = trim((string)$staffDetails[$j][$k]['image']);
                    $firstName = trim((string)$staffDetails[$j][$k]['first_name']);
                    $lastName = trim((string)$staffDetails[$j][$k]['last_name']);
                    $fullName = trim($firstName . ' ' . $lastName);
                    if ($fullName === '') {
                        $fullName = 'Faculty Member';
                    }

                    $qualification = str_replace('\,', ',', (string)$staffDetails[$j][$k]['qualification']);
                    $designation = trim((string)$staffDetails[$j][$k]['designation']);
                    $initials = strtoupper(substr($firstName !== '' ? $firstName : $fullName, 0, 1) . substr($lastName, 0, 1));
                    if ($initials === '') {
                        $initials = strtoupper(substr($fullName, 0, 1));
                    }

                    $imageUrl = BASE_URL . '/public/assets/images/faculty/' . rawurlencode($image);
                    $imageFsPath = ROOT_PATH . '/public/assets/images/faculty/' . $image;
                    $hasImage = $image !== '' && is_file($imageFsPath);
                ?>

                <div class="col-md-4 col-lg-3">
                    <div class="card h-100 text-center staff-member-card">
                        <div class="staff-member-media">
                            <a class="staff-member-link" href="<?php echo BASE_URL; ?>/public/pages/department/view_faculty.php?faculty=<?php echo $staffDetails[$j][$k]['id']; ?>">
                                <?php if ($hasImage) { ?>
                                <img 
                                    src="<?php echo htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8'); ?>" 
                                    class="staff-member-image"
                                    alt="<?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>"
                                >
                                <?php } else { ?>
                                <span class="staff-member-avatar"><?php echo htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php } ?>
                            </a>
                        </div>

                        <div class="card-body staff-member-body">
                            <h6 class="staff-member-name">
                                <?php echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); ?>
                            </h6>

                            <div class="staff-member-qualification">
                                <?php echo htmlspecialchars($qualification !== '' ? $qualification : 'Qualification will be updated soon.', ENT_QUOTES, 'UTF-8'); ?>
                            </div>

                            <div class="staff-member-designation">
                                <?php echo htmlspecialchars($designation !== '' ? $designation : 'Faculty', ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        </div>

                    </div>
                </div>

                <?php } ?>

            </div>
        </section>

    <?php } ?>
    </div>

    </div>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>

