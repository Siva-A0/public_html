<?php require_once(__DIR__ . '/../../config.php');

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbClass = TB_CLASS;

$classes = $fcObj->getClasses($tbClass);
$classesCnt = sizeof($classes);

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>

<div id="page">
    <div id="content">
        <div class="post">
            <span class="alignCenter"></span>
            <p></p>
        </div>

        <div id='content_left' class='content_left'></div>
        
        <div id='content_right' class='content_right'>
            <div class="class-manage-page">
            <div class="class-list-hero">
                <h3 class="class-list-title">Manage Classes</h3>
                <!-- <p class="class-list-subtitle">Align, edit, and maintain class records in one place.</p> -->
            </div>
            </div>

            <div class="class-list-card">
                <?php if (isset($_GET['delete'])) { ?>
                    <?php if ($_GET['delete'] === 'success') { ?>
                        <div class="class-feedback class-feedback-success">Class deleted successfully.</div>
                    <?php } elseif ($_GET['delete'] === 'notfound') { ?>
                        <div class="class-feedback class-feedback-warn">Class not found or already deleted.</div>
                    <?php } elseif ($_GET['delete'] === 'error') { ?>
                        <div class="class-feedback class-feedback-error">Could not delete class. Remove linked records first, then retry.</div>
                    <?php } else { ?>
                        <div class="class-feedback class-feedback-warn">Invalid class selected for deletion.</div>
                    <?php } ?>
                <?php } ?>

                <div class="class-list-head">
                    <div>Class Name</div>
                    <div style="text-align:right;">Actions</div>
                </div>

                <?php if ($classesCnt > 0) { ?>
                    <?php for ($j = 0; $j < $classesCnt; $j++) { ?>
                    <div class="class-list-row">
                        <div class="class-name">
                            <?php echo htmlspecialchars((string)$classes[$j]['class_name'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="class-actions">
                            <a class="class-btn class-btn-edit" href="edit_class.php?class=<?php echo (int)$classes[$j]['id']; ?>">
                                Edit
                            </a>
                            <a class="class-btn class-btn-delete" href="delete_class.php?class=<?php echo (int)$classes[$j]['id']; ?>" onclick="return confirm('Do You Want To Continue To Delete');">
                                Delete
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="class-empty">No classes found.</div>
                <?php } ?>

            </div>
        </div>
        <br class="clearfix" />
    </div>
                    <div class="mt-3">
                    <a href="../settings/department_option.php?option=classes" class="btn btn-outline-secondary">Back</a>
                </div><?php include_once('../layout/sidebar.php'); ?>
    <br class="clearfix" />
</div>
</div>
<?php include_once('../layout/footer.php'); ?>

