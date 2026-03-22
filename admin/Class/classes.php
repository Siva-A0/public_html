<?php require_once(__DIR__ . '/../../config.php');

require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();
$tbClass = TB_CLASS;

$classes = $fcObj->getClasses($tbClass);
$classesCnt = sizeof($classes);

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
    .class-manage-page {
        --cp-primary: #173d69;
        --cp-primary-deep: #13345a;
        --cp-accent: #f0b323;
        --cp-accent-deep: #d79a12;
        --cp-accent-soft: #fff5da;
        --cp-surface: #eef4fa;
        --cp-card: #ffffff;
        --cp-border: #d9e3ef;
        --cp-muted: #6b819c;
    }

    #content_left {
        display: none;
    }

    #content {
        grid-template-columns: 1fr;
        gap: 0;
    }

    #page {
        max-width: none;
    }

    .class-manage-page {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--cp-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .class-list-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--cp-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--cp-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

    .class-list-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--cp-accent), var(--cp-accent-deep));
    }

    .class-list-title {
        margin: 0;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--cp-primary-deep);
    }

    .class-list-subtitle {
        margin: 8px 0 0;
        font-size: 15px;
        color: var(--cp-muted);
    }

    .class-list-card {
        background: var(--cp-card);
        border: 1px solid var(--cp-border);
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        padding: 16px;
    }

    .class-feedback {
        margin-bottom: 12px;
        border-radius: 12px;
        padding: 11px 14px;
        font-weight: 700;
        font-size: 14px;
        border: 1px solid transparent;
    }

    .class-feedback-success {
        background: #ecfdf5;
        color: #166534;
        border-color: #86efac;
    }

    .class-feedback-error {
        background: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .class-feedback-warn {
        background: #fff7ed;
        color: #9a3412;
        border-color: #fed7aa;
    }

    .class-list-head,
    .class-list-row {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 210px;
        align-items: center;
        gap: 12px;
    }

    .class-list-head {
        border: 1px solid var(--cp-border);
        border-radius: 12px;
        background: #f7f9fc;
        padding: 12px 14px;
        font-size: 14px;
        font-weight: 800;
        color: var(--cp-primary);
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 10px;
    }

    .class-list-row {
        border: 1px solid #e0e8f1;
        border-radius: 12px;
        padding: 11px 14px;
        background: #ffffff;
        margin-bottom: 10px;
    }

    .class-list-row:last-child {
        margin-bottom: 0;
    }

    .class-name {
        font-size: 22px;
        font-weight: 600;
        color: var(--cp-primary-deep);
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .class-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        flex-wrap: wrap;
    }

    .class-btn {
        border: 0;
        border-radius: 11px;
        padding: 8px 14px;
        font-size: 14px;
        font-weight: 700;
        color: #fff;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 74px;
    }

    .class-btn-edit {
        background: linear-gradient(135deg, var(--cp-primary-deep), var(--cp-primary));
    }

    .class-btn-delete {
        background: linear-gradient(135deg, #b91c1c, #dc2626);
    }

    .class-empty {
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        padding: 16px;
        text-align: center;
    }

    .class-footer {
        margin-top: 14px;
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .class-add-btn {
        border: 0;
        border-radius: 12px;
        padding: 11px 20px;
        background: linear-gradient(135deg, var(--cp-primary-deep), var(--cp-primary));
        color: #fff;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
    }

    @media (max-width: 768px) {
        .class-list-title {
            font-size: 26px;
        }

        .class-list-head {
            display: none;
        }

        .class-list-row {
            grid-template-columns: 1fr;
        }

        .class-actions {
            justify-content: flex-start;
        }
    }
</style>
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
                <p class="class-list-subtitle">Align, edit, and maintain class records in one place.</p>
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
