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

$tbComtCtg = TB_COMT_CATEG;
$tbComt    = TB_COMMITTEE;

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

<div class="committee-header">
    <h3 class="committee-page-title">AIML Association Committee</h3>
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
</div>

<div class="card committee-shell border-0">
    <div class="committee-body">

        <?php if ($categoryCnt > 0) { ?>
            <div class="committee-grid">

            <?php for ($j = 0; $j < $categoryCnt; $j++) { ?>
                <?php $memberCount = !empty($CmtMemDet[$j]) ? sizeof($CmtMemDet[$j]) : 0; ?>

                <div class="committee-category">
                    <div class="committee-category-head">
                        <h5 class="committee-category-title">
                            <?php echo $ComtCateg[$j]['category_name']; ?>
                        </h5>
                        <span class="committee-member-count"><?php echo $memberCount; ?> Members</span>
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

        <div class="mt-5">
            <a href="addmem.php" class="btn committee-add-btn">
                <i class="bi bi-plus-circle me-1"></i>
                Add Committee Member
            </a>
        </div>

    </div>
</div>

<?php include_once('../layout/footer.php'); ?>


