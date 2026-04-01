<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php
session_start();

if (!isset($_SESSION['adminId'])) {
    header('Location: ../index.php');
    exit;
}

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbComtCtg = TB_COMT_CATEG;
$tbComt = TB_COMMITTEE;

$status = trim((string)($_GET['status'] ?? ''));
$message = '';
$editCategory = null;

if ($status === 'added') {
    $message = 'Association category created successfully.';
} elseif ($status === 'updated') {
    $message = 'Association category updated successfully.';
} elseif ($status === 'deleted') {
    $message = 'Association category deleted successfully.';
} elseif ($status === 'duplicate') {
    $message = 'That category name already exists.';
} elseif ($status === 'invalid') {
    $message = 'Please enter a valid category name.';
} elseif ($status === 'in_use') {
    $message = 'This category cannot be deleted because members are assigned to it.';
} elseif ($status === 'not_found') {
    $message = 'The selected category was not found.';
} elseif ($status === 'csrf') {
    $message = 'Your session expired. Please try again.';
} elseif ($status === 'error') {
    $message = 'The action could not be completed. Please try again.';
}

if (isset($_POST['add_category'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: categories.php?status=csrf');
        exit;
    }

    $categoryName = trim((string)($_POST['category_name'] ?? ''));
    if ($categoryName === '') {
        header('Location: categories.php?status=invalid');
        exit;
    }

    $created = $fcObj->addCommitteeCategory($tbComtCtg, $categoryName);
    if ($created === false) {
        header('Location: categories.php?status=error');
    } elseif ((int)$created === 0) {
        header('Location: categories.php?status=duplicate');
    } else {
        header('Location: categories.php?status=added');
    }
    exit;
}

if (isset($_POST['update_category'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: categories.php?status=csrf');
        exit;
    }

    $categoryId = (int)($_POST['category_id'] ?? 0);
    $categoryName = trim((string)($_POST['category_name'] ?? ''));

    if ($categoryId <= 0 || $categoryName === '') {
        header('Location: categories.php?status=invalid');
        exit;
    }

    $updated = $fcObj->updateCommitteeCategory($tbComtCtg, $categoryId, $categoryName);
    if ($updated === false) {
        header('Location: categories.php?status=error');
    } elseif ((int)$updated === 0) {
        header('Location: categories.php?status=duplicate');
    } else {
        header('Location: categories.php?status=updated');
    }
    exit;
}

if (isset($_POST['delete_category'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        header('Location: categories.php?status=csrf');
        exit;
    }

    $categoryId = (int)($_POST['category_id'] ?? 0);
    if ($categoryId <= 0) {
        header('Location: categories.php?status=not_found');
        exit;
    }

    $assignedCount = $fcObj->countCommitteeMembersByCategory($tbComt, $categoryId);
    if ($assignedCount > 0) {
        header('Location: categories.php?status=in_use');
        exit;
    }

    $deleted = $fcObj->deleteCommitteeCategory($tbComtCtg, $categoryId);
    if ($deleted === false) {
        header('Location: categories.php?status=error');
    } else {
        header('Location: categories.php?status=deleted');
    }
    exit;
}

if (isset($_GET['edit'])) {
    $categoryId = (int)$_GET['edit'];
    if ($categoryId > 0) {
        $result = $fcObj->getCommitteeCategoryById($tbComtCtg, $categoryId);
        if (!empty($result)) {
            $editCategory = $result[0];
        } else {
            header('Location: categories.php?status=not_found');
            exit;
        }
    }
}

$categories = $fcObj->getComiteCatg($tbComtCtg);
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
?>

<div class="container-fluid staff-categories-page">
    <div class="page-shell">
        <div class="page-hero">
            <h3 class="page-title">Pragya AI Category Management</h3>
            <p class="page-subtitle"></p>
            <div class="page-pill-row">
                <span class="page-pill"><i class="bi bi-collection me-1"></i><?php echo (int)count($categories); ?> Categories</span>
                <span class="page-pill"><i class="bi bi-people me-1"></i>Association Members</span>
            </div>
        </div>

        <?php if ($message !== '') { ?>
            <div class="alert <?php echo in_array($status, array('added', 'updated', 'deleted'), true) ? 'alert-success' : 'alert-warning'; ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card form-card border-0">
                    <div class="card-body">
                        <h4 class="section-title"><?php echo $editCategory ? 'Edit Category' : 'Add Category'; ?></h4>

                        <form method="POST" action="categories.php<?php echo $editCategory ? '?edit=' . (int)$editCategory['id'] : ''; ?>">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                            <?php if ($editCategory) { ?>
                                <input type="hidden" name="category_id" value="<?php echo (int)$editCategory['id']; ?>">
                            <?php } ?>

                            <div class="mb-3">
                                <label class="form-label">Category Name</label>
                                <input
                                    type="text"
                                    name="category_name"
                                    class="form-control"
                                    maxlength="500"
                                    value="<?php echo htmlspecialchars($editCategory ? (string)$editCategory['category_name'] : '', ENT_QUOTES, 'UTF-8'); ?>"
                                    placeholder="Enter category name"
                                    required
                                >
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <button type="submit" name="<?php echo $editCategory ? 'update_category' : 'add_category'; ?>" class="btn btn-primary">
                                    <?php echo $editCategory ? 'Update Category' : 'Create Category'; ?>
                                </button>
                                <?php if ($editCategory) { ?>
                                    <a href="categories.php" class="btn btn-outline-secondary">Cancel</a>
                                <?php } ?>
                                <a href="assoc.php" class="btn btn-outline-secondary">Back to Pragya AI</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card list-card border-0">
                    <div class="card-body">
                        <h4 class="section-title">Existing Categories</h4>

                        <?php if (empty($categories)) { ?>
                            <div class="empty-state"></div>
                        <?php } else { ?>
                            <?php foreach ($categories as $category) { ?>
                                <?php $assignedCount = $fcObj->countCommitteeMembersByCategory($tbComt, (int)$category['id']); ?>
                                <div class="category-row">
                                    <div>
                                        <p class="category-name"><?php echo htmlspecialchars((string)$category['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                        <p class="category-meta"><?php echo (int)$assignedCount; ?> member<?php echo $assignedCount === 1 ? '' : 's'; ?> assigned</p>
                                    </div>

                                    <div class="d-flex gap-2 flex-wrap">
                                        <a href="categories.php?edit=<?php echo (int)$category['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                                        <form method="POST" action="categories.php" onsubmit="return confirm('Delete this category? This works only when no members are assigned.');">
                                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                            <input type="hidden" name="category_id" value="<?php echo (int)$category['id']; ?>">
                                            <button type="submit" name="delete_category" class="btn btn-sm btn-outline-danger" <?php echo $assignedCount > 0 ? 'disabled' : ''; ?>>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../layout/footer.php'); ?>
