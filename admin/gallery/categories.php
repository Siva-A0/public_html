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

$tbGalleryCategory = TB_GALLERY_CATEGORY;
$tbGallery = TB_GALLERY;
$tbEvents = TB_EVENTS;

$status = trim((string)($_GET['status'] ?? ''));
$message = '';
$editCategory = null;

if ($status === 'added') {
    $message = 'Gallery category created successfully.';
} elseif ($status === 'updated') {
    $message = 'Gallery category updated successfully.';
} elseif ($status === 'deleted') {
    $message = 'Gallery category deleted successfully.';
} elseif ($status === 'duplicate') {
    $message = 'That category name already exists.';
} elseif ($status === 'invalid') {
    $message = 'Please enter a valid category name.';
} elseif ($status === 'in_use') {
    $message = 'This category cannot be deleted because images are assigned to it.';
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
    $linkedEventIdValue = trim((string)($_POST['linked_event_id'] ?? ''));
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($categoryName === '') {
        header('Location: categories.php?status=invalid');
        exit;
    }

    $created = $fcObj->addGalleryCategory(
        $tbGalleryCategory,
        $categoryName,
        $linkedEventIdValue === '' ? null : (int)$linkedEventIdValue,
        $sortOrder,
        $isActive
    );

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
    $linkedEventIdValue = trim((string)($_POST['linked_event_id'] ?? ''));
    $sortOrder = (int)($_POST['sort_order'] ?? 0);
    $isActive = isset($_POST['is_active']) ? 1 : 0;

    if ($categoryId <= 0 || $categoryName === '') {
        header('Location: categories.php?status=invalid');
        exit;
    }

    $updated = $fcObj->updateGalleryCategory(
        $tbGalleryCategory,
        $categoryId,
        $categoryName,
        $linkedEventIdValue === '' ? null : (int)$linkedEventIdValue,
        $sortOrder,
        $isActive
    );

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

    if ($fcObj->countGalleryImagesByCategory($tbGallery, $categoryId) > 0) {
        header('Location: categories.php?status=in_use');
        exit;
    }

    $deleted = $fcObj->deleteGalleryCategory($tbGalleryCategory, $categoryId);
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
        $result = $fcObj->getGalleryCategoryById($tbGalleryCategory, $categoryId);
        if (!empty($result)) {
            $editCategory = $result[0];
        } else {
            header('Location: categories.php?status=not_found');
            exit;
        }
    }
}

$categories = $fcObj->getGalleryCategories($tbGalleryCategory);
$events = $fcObj->getEventDetails($tbEvents);
$eventNamesById = array();
foreach ($events as $event) {
    $eventNamesById[(int)$event['id']] = (string)$event['event_name'];
}

include_once('../layout/main_header.php');
?>

<style type="text/css">
    .gallery-categories-page {
        --gallery-primary: #173d69;
        --gallery-primary-deep: #13345a;
        --gallery-accent: #f0b323;
        --gallery-accent-deep: #d79a12;
        --gallery-accent-soft: #fff5da;
        --gallery-surface: #eef4fa;
        --gallery-card: #ffffff;
        --gallery-border: #d9e3ef;
        --gallery-border-strong: #c8d6e6;
        --gallery-text: #163a61;
        --gallery-muted: #6b819c;
    }

    .gallery-categories-page .page-shell {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--gallery-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .gallery-categories-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--gallery-border);
        border-radius: 22px;
        padding: 24px 26px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--gallery-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 18px;
    }

    .gallery-categories-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--gallery-accent), var(--gallery-accent-deep));
    }

    .gallery-categories-page .page-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--gallery-primary-deep);
        margin: 0;
    }

    .gallery-categories-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--gallery-muted);
        font-size: 15px;
    }

    .gallery-categories-page .page-pill-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .gallery-categories-page .page-pill {
        border: 1px solid #ead290;
        border-radius: 999px;
        padding: 7px 12px;
        background: var(--gallery-accent-soft);
        color: #8b6510;
        font-size: 13px;
        font-weight: 700;
    }

    .gallery-categories-page .form-card,
    .gallery-categories-page .list-card {
        border: 1px solid var(--gallery-border);
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        background: var(--gallery-card);
    }

    .gallery-categories-page .card-body {
        padding: 22px;
    }

    .gallery-categories-page .section-title {
        font-size: 19px;
        font-weight: 800;
        color: var(--gallery-primary-deep);
        margin-bottom: 14px;
    }

    .gallery-categories-page .form-label {
        font-weight: 700;
        color: var(--gallery-text);
    }

    .gallery-categories-page .form-control,
    .gallery-categories-page .form-select {
        border: 1px solid var(--gallery-border-strong);
        border-radius: 12px;
        min-height: 50px;
        background: #f7f9fc;
        font-size: 16px;
    }

    .gallery-categories-page .form-control:focus,
    .gallery-categories-page .form-select:focus {
        border-color: #87a6cb;
        box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
        background: #ffffff;
    }

    .gallery-categories-page .btn-primary {
        border: 0;
        border-radius: 12px;
        padding: 11px 20px;
        background: linear-gradient(135deg, var(--gallery-primary-deep), var(--gallery-primary));
        font-weight: 700;
    }

    .gallery-categories-page .btn-outline-secondary,
    .gallery-categories-page .btn-outline-primary,
    .gallery-categories-page .btn-outline-danger {
        border-radius: 12px;
        font-weight: 700;
    }

    .gallery-categories-page .category-row {
        border: 1px solid #e0e8f1;
        border-radius: 14px;
        padding: 14px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        background: #fff;
    }

    .gallery-categories-page .category-row + .category-row {
        margin-top: 12px;
    }

    .gallery-categories-page .category-name {
        font-size: 18px;
        font-weight: 700;
        color: var(--gallery-primary-deep);
        margin: 0;
    }

    .gallery-categories-page .category-meta {
        margin: 4px 0 0;
        color: var(--gallery-muted);
        font-size: 13px;
    }

    .gallery-categories-page .empty-state {
        border: 1px dashed #cfd8e3;
        border-radius: 14px;
        padding: 18px;
        text-align: center;
        color: var(--gallery-muted);
        background: #f8fafc;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .gallery-categories-page .page-title {
            font-size: 26px;
        }

        .gallery-categories-page .category-row {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>

<div class="container-fluid gallery-categories-page">
    <div class="page-shell">
        <div class="page-hero">
            <h3 class="page-title">Gallery Category Management</h3>
            <p class="page-subtitle">Create and organize the sections that appear in the gallery without touching code.</p>
            <div class="page-pill-row">
                <span class="page-pill"><i class="bi bi-collection me-1"></i><?php echo (int)count($categories); ?> Categories</span>
                <span class="page-pill"><i class="bi bi-palette me-1"></i>Admin Controlled</span>
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

                        <div class="mb-3">
                            <label class="form-label">Link to Event (Optional)</label>
                            <select name="linked_event_id" class="form-select">
                                <option value="">Independent Gallery Category</option>
                                <?php foreach ($events as $event) { ?>
                                    <?php $eventId = (int)$event['id']; ?>
                                    <option value="<?php echo $eventId; ?>" <?php echo ($editCategory && (string)$editCategory['linked_event_id'] === (string)$eventId) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars((string)$event['event_name'], ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Display Order</label>
                            <input
                                type="number"
                                name="sort_order"
                                class="form-control"
                                value="<?php echo htmlspecialchars((string)($editCategory['sort_order'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?>"
                            >
                        </div>

                        <div class="form-check mb-4">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="is_active"
                                id="is_active"
                                <?php echo (!$editCategory || (int)$editCategory['is_active'] === 1) ? 'checked' : ''; ?>
                            >
                            <label class="form-check-label" for="is_active">
                                Show this category in gallery lists
                            </label>
                        </div>

                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" name="<?php echo $editCategory ? 'update_category' : 'add_category'; ?>" class="btn btn-primary">
                                <?php echo $editCategory ? 'Update Category' : 'Create Category'; ?>
                            </button>
                            <?php if ($editCategory) { ?>
                                <a href="categories.php" class="btn btn-outline-secondary">Cancel</a>
                            <?php } ?>
                            <a href="gallery.php" class="btn btn-outline-secondary">Back to Gallery</a>
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
                        <div class="empty-state">No gallery categories available yet.</div>
                    <?php } else { ?>
                        <?php foreach ($categories as $category) { ?>
                            <?php $imageCount = $fcObj->countGalleryImagesByCategory($tbGallery, (int)$category['id']); ?>
                            <div class="category-row">
                                <div>
                                    <p class="category-name"><?php echo htmlspecialchars((string)$category['category_name'], ENT_QUOTES, 'UTF-8'); ?></p>
                                    <p class="category-meta">
                                        <?php
                                            if ($category['linked_event_id'] === null) {
                                                echo 'Independent category';
                                            } else {
                                                $linkedEventId = (int)$category['linked_event_id'];
                                                $linkedEventName = $eventNamesById[$linkedEventId] ?? ('Event ID: ' . $linkedEventId);
                                                echo 'Linked to: ' . htmlspecialchars($linkedEventName, ENT_QUOTES, 'UTF-8');
                                            }
                                        ?>
                                        |
                                        <?php echo (int)$imageCount; ?> image<?php echo $imageCount === 1 ? '' : 's'; ?>
                                        |
                                        <?php echo (int)$category['is_active'] === 1 ? 'Visible' : 'Hidden'; ?>
                                    </p>
                                </div>

                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="categories.php?edit=<?php echo (int)$category['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>

                                    <form method="POST" action="categories.php" onsubmit="return confirm('Delete this category? This works only when no images are assigned.');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="category_id" value="<?php echo (int)$category['id']; ?>">
                                        <button type="submit" name="delete_category" class="btn btn-sm btn-outline-danger" <?php echo $imageCount > 0 ? 'disabled' : ''; ?>>
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
