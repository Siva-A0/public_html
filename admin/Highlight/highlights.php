<?php require_once(__DIR__ . '/../../config.php');

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbHighLights = TB_HIGHLIGHTS;

$aimlHighlights = $fcObj->getHighLights($tbHighLights, AIML);
$deptHighlights = $fcObj->getHighLights($tbHighLights, DEPARTMENT);

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>

<div id="page">
    <div id="content" class="single-panel-layout">
        <div class="post">
            <span class="alignCenter">
                <h4>AIML Department </h4>
            </span>
            <p></p>
        </div>
        <!-- <div id='content_left' class='content_left'>
            <?php include_once('../layout/other_leftnav.php'); ?>
        </div> -->
        <div id='content_right' class='content_right'>
            <div class="highlights-page">
            <div class="highlights-hero">
                <h3 class="highlights-title">AIML Department</h3>
                <!-- <p class="highlights-subtitle">Manage AIML and Department highlights.</p> -->
            </div>

            <div class="comteeMem highlights-card">
                <div class="highlights-section">
                    <div class="committeeTitle">
                        <div class='eventCandName'>AIML Highlights</div>
                        <div class='eventCandName'>Action</div>
                    </div>
                    <?php if (!empty($aimlHighlights)) { ?>
                        <?php foreach ($aimlHighlights as $row) { ?>
                            <div class="highlights-list-row">
                                <div class='highlight-text'><?php echo htmlspecialchars($row['high_light'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class='highlight-actions'>
                                    <a href="delete_highLight.php?highlight=<?php echo $row['id']; ?>" onclick="return confirm('Do You Want To Continue To Delete');">
                                        <input type="button" class="button" value="Delete" />
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="highlights-empty">No AIML highlights available.</div>
                    <?php } ?>
                </div>

                <div class="highlights-section">
                    <div class="committeeTitle">
                        <div class='eventCandName'>Department Highlights</div>
                        <div class='eventCandName'>Action</div>
                    </div>
                    <?php if (!empty($deptHighlights)) { ?>
                        <?php foreach ($deptHighlights as $row) { ?>
                            <div class="highlights-list-row">
                                <div class='highlight-text'><?php echo htmlspecialchars($row['high_light'], ENT_QUOTES, 'UTF-8'); ?></div>
                                <div class='highlight-actions'>
                                    <a href="delete_highLight.php?highlight=<?php echo $row['id']; ?>" onclick="return confirm('Do You Want To Continue To Delete');">
                                        <input type="button" class="button" value="Delete" />
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="highlights-empty">No Department highlights available.</div>
                    <?php } ?>
                </div>

            </div>
            </div>
        </div>
        <br class="clearfix" />
    </div>
                    <div class="mt-3">
                    <a href="../settings/department_option.php?option=highlights" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>
    <br class="clearfix" />
</div>
</div>
<?php include_once('../layout/footer.php'); ?>

