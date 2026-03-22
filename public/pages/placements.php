<?php require_once(__DIR__ . '/../../config.php');?>

<?php 

    include_once(INCLUDES_PATH . '/header.php');
    
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/placements.css">
<?php

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbPlacements = TB_PLACEMENTS;

$cat_id = NON_DOCUMENT;
$placements = $fcObj->getPlacements($tbPlacements, $cat_id);
$placementsCnt = sizeof($placements);

$cat_id = DOCUMENT;
$placementDocs = $fcObj->getPlacements($tbPlacements, $cat_id);
$placementDocsCnt = sizeof($placementDocs);

?>

<!-- ================= HERO SECTION ================= -->

<div class="placement-hero">
    <div class="placement-hero-card">
        <span class="hero-kicker">Career Success Hub</span>
        <h1>Placements</h1>
        <p>Empowering careers through industry-leading placement opportunities.</p>
        <div class="hero-chips">
            <span>95% Students Placed</span>
            <span>50+ Recruiters</span>
            <span>Rs. 8.5L Avg. Package</span>
        </div>
    </div>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="placement-container">

    <!-- LEFT CONTENT -->
    <div class="placement-left">

        <!-- Placement Highlights -->
        <section class="content-block">
            <div class="section-title">Placement Highlights</div>
            <p class="section-subtitle">Recent outcomes and success indicators from department placement activities.</p>

            <div class="placements-list">

                <?php for($i=0; $i<$placementsCnt; $i++){ ?>
                    <div class="placement-card">
                        <div class="placement-number">
                            <?php echo str_pad($i+1, 2, "0", STR_PAD_LEFT); ?>
                        </div>
                        <div class="placement-text">
                            <?php echo htmlspecialchars((string)$placements[$i]['placement_desc'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($placementsCnt === 0) { ?>
                    <div class="empty-state">Placement highlights will be published soon.</div>
                <?php } ?>

            </div>
        </section>

        <!-- Documents Section -->
        <section class="content-block section-gap">
            <div class="section-title">Documents & Reports</div>
            <p class="section-subtitle">Access placement reports, year-wise summaries, and official department documents.</p>

            <div class="documents-list">
                <?php
                    $renderedDocs = 0;
                    for($i=0; $i<$placementDocsCnt; $i++){

                        $placementDoc = $placementDocs[$i]['placement_desc'];
                        $placeDocs = explode('$$',$placementDoc);
                        $docTitle = isset($placeDocs[0]) ? trim((string)$placeDocs[0]) : '';
                        $docFile = isset($placeDocs[1]) ? trim((string)$placeDocs[1]) : '';
                        if ($docTitle === '' || $docFile === '') {
                            continue;
                        }
                        $renderedDocs++;
                        $docExt = strtoupper(pathinfo($docFile, PATHINFO_EXTENSION));
                        if ($docExt === '') {
                            $docExt = 'FILE';
                        }
                ?>
                    <div class="document-card">
                        <div class="document-icon"><?php echo htmlspecialchars($docExt, ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="document-title">
                            <?php echo htmlspecialchars($docTitle, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="document-link">
                            <a href="<?php echo BASE_URL; ?>/public/uploads/placements/<?php echo rawurlencode($docFile); ?>" target="_blank" rel="noopener noreferrer">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php } ?>

                <?php if ($renderedDocs === 0) { ?>
                    <div class="empty-state">Placement reports and documents will appear here soon.</div>
                <?php } ?>
            </div>
        </section>

    </div>

</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
