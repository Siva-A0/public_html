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

$tbStaffCateg = TB_STAFF_CATEGORY;
$tbStaff = TB_STAFF;

$status = '';
$message = '';
$editCategory = null;

if (isset($_GET['status'])) {
    $status = trim((string)$_GET['status']);
}

if ($status === 'added') {
    $message = 'Faculty category created successfully.';
} elseif ($status === 'updated') {
    $message = 'Faculty category updated successfully.';
} elseif ($status === 'deleted') {
    $message = 'Faculty category deleted successfully.';
} elseif ($status === 'duplicate') {
    $message = 'That category name already exists.';
} elseif ($status === 'invalid') {
    $message = 'Please enter a valid category name.';
} elseif ($status === 'in_use') {
    $message = 'This category cannot be deleted because faculty members are assigned to it.';
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

    $created = $fcObj->addStaffCategory($tbStaffCateg, $categoryName);
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

    $updated = $fcObj->updateStaffCategory($tbStaffCateg, $categoryId, $categoryName);
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

    $assignedCount = $fcObj->countStaffByCategory($tbStaff, $categoryId);
    if ($assignedCount > 0) {
        header('Location: categories.php?status=in_use');
        exit;
    }

    $deleted = $fcObj->deleteStaffCategory($tbStaffCateg, $categoryId);
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
        $result = $fcObj->getStaffCategoryById($tbStaffCateg, $categoryId);
        if (!empty($result)) {
            $editCategory = $result[0];
        } else {
            header('Location: categories.php?status=not_found');
            exit;
        }
    }
}

$categories = $fcObj->getStaffCategories($tbStaffCateg);
include_once('../layout/main_header.php');
?>

<style type="text/css">
    .staff-categories-page {
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
    }

    .staff-categories-page .page-shell {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--staff-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .staff-categories-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--staff-border);
        border-radius: 22px;
        padding: 24px 26px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--staff-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 18px;
    }

    .staff-categories-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--staff-accent), var(--staff-accent-deep));
    }

    .staff-categories-page .page-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--staff-primary-deep);
        margin: 0;
    }

    .staff-categories-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--staff-muted);
        font-size: 15px;
    }

    .staff-categories-page .page-pill-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .staff-categories-page .page-pill {
        border: 1px solid #ead290;
        border-radius: 999px;
        padding: 7px 12px;
        background: var(--staff-accent-soft);
        color: #8b6510;
        font-size: 13px;
        font-weight: 700;
    }

    .staff-categories-page .form-card,
    .staff-categories-page .list-card {
        border: 1px solid var(--staff-border);
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        background: var(--staff-card);
    }

    .staff-categories-page .card-body {
        padding: 22px;
    }

    .staff-categories-page .section-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--staff-primary-deep);
        margin-bottom: 14px;
    }

    .staff-categories-page .form-label {
        font-weight: 700;
        color: var(--staff-text);
    }

    .staff-categories-page .form-control {
        border: 1px solid var(--staff-border-strong);
        border-radius: 12px;
        min-height: 50px;
        background: #f7f9fc;
        font-size: 16px;
    }

    .staff-categories-page .form-control:focus {
        border-color: #87a6cb;
        box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
        background: #ffffff;
    }

    .staff-categories-page .btn-primary {
        border: 0;
        border-radius: 12px;
        padding: 11px 20px;
        background: linear-gradient(135deg, var(--staff-primary-deep), var(--staff-primary));
        font-weight: 700;
    }

    .staff-categories-page .btn-outline-secondary,
    .staff-categories-page .btn-outline-primary,
    .staff-categories-page .btn-outline-danger {
        border-radius: 12px;
        font-weight: 700;
    }

    .staff-categories-page .category-row {
        border: 1px solid #e0e8f1;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        background: #fff;
    }

    .staff-categories-page .category-row + .category-row {
        margin-top: 12px;
    }

    .staff-categories-page .category-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--staff-primary-deep);
        margin: 0;
    }

    .staff-categories-page .category-meta {
        margin: 4px 0 0;
        color: var(--staff-muted);
        font-size: 13px;
    }

    .staff-categories-page .empty-state {
        border: 1px dashed #cfd8e3;
        border-radius: 14px;
        padding: 18px;
        text-align: center;
        color: var(--staff-muted);
        background: #f8fafc;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .staff-categories-page .page-title {
            font-size: 26px;
        }

        .staff-categories-page .category-row {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="container-fluid staff-categories-page">
    <div class="page-shell">
        <div class="page-hero">
            <h3 class="page-title">Faculty Category Management</h3>
            <p class="page-subtitle">Create, rename, and clean up the categories used in department faculty profiles.</p>
            <div class="page-pill-row">
                <span class="page-pill"><i class="bi bi-collection me-1"></i><?php echo (int)count($categories); ?> Categories</span>
                <span class="page-pill"><i class="bi bi-shield-check me-1"></i>Admin Controlled</span>
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
                            <a href="department.php" class="btn btn-outline-secondary">Back to Department</a>
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
                        <div class="empty-state">No faculty categories available yet.</div>
                    <?php } else { ?>
                        <?php foreach ($categories as $category) { ?>
                            <?php $assignedCount = $fcObj->countStaffByCategory($tbStaff, (int)$category['id']); ?>
                            <div class="category-row">
                                <div>
                                    <p class="category-name"><?php echo htmlspecialchars((string)$category['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="category-meta"><?php echo (int)$assignedCount; ?> faculty member<?php echo $assignedCount === 1 ? '' : 's'; ?> assigned</p>
                                </div>

                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="categories.php?edit=<?php echo (int)$category['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                                    <form method="POST" action="categories.php" onsubmit="return confirm('Delete this category? This works only when no faculty members are assigned.');">
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
