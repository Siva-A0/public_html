<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
  
require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbGallery = TB_GALLERY;
$tbGalleryCategory = TB_GALLERY_CATEGORY;

$msg = "";
$categoryId = "";
$imgName = "";
$imgDesc = "";

/* ---------- ADD GALLERY ---------- */
if (isset($_POST['addNewGallery'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $msg = "Your session expired. Please try again.";
    } else {

    $categoryName = trim((string)($_POST['categoryName'] ?? ''));
    $categoryId = $fcObj->getOrCreateGalleryCategoryId($tbGalleryCategory, $categoryName);
    $imgName  = trim($_POST['imageName']);
    $imgDesc  = trim($_POST['imgDesc']);

    if ($categoryId <= 0 || $imgName == "" || $_FILES['galleryImage']['error'] != 0) {
        $msg = "All fields are required.";
    } else {
        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower(str_replace(' ', '_', $imgName)));
        if ($baseName === '' || $baseName === null) {
            $baseName = 'gallery_image';
        }
        $uploadError = '';
        $fileName = app_store_uploaded_image($_FILES['galleryImage'], __DIR__, $baseName, $uploadError, 4 * 1024 * 1024);
        $uploadPath = $fileName !== '' ? (__DIR__ . '/' . $fileName) : '';

        if ($fileName !== '') {

            $varArray = [
                'category_id' => $categoryId,
                'image_name'  => $imgName,
                'image_desc'  => $imgDesc,
                'image'       => $fileName
            ];

            $addGallery = $fcObj->addGallery($tbGallery, $varArray);

            if ($addGallery) {
                header("Location: gallery.php");
                exit;
            } else {
                if (file_exists($uploadPath)) {
                    @unlink($uploadPath);
                }
                $msg = "Database error. Please try again.";
            }

        } else {
            $msg = $uploadError;
        }
    }
    }
}

include_once('../layout/main_header.php');

$categories = $fcObj->getGalleryCategories($tbGalleryCategory, true);
?>

<style type="text/css">
    .add-gallery-page {
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

    .add-gallery-page .page-shell {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--gallery-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .add-gallery-page .page-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--gallery-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--gallery-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 18px;
    }

    .add-gallery-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--gallery-accent), var(--gallery-accent-deep));
    }

    .add-gallery-page .page-title {
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.6px;
        color: var(--gallery-primary-deep);
        margin: 0;
    }

    .add-gallery-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--gallery-muted);
        font-size: 15px;
    }

    .add-gallery-page .gallery-form-card {
        border: 1px solid var(--gallery-border);
        border-radius: 18px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        background: var(--gallery-card);
    }

    .add-gallery-page .gallery-form-card .card-body {
        padding: 22px;
    }

    .add-gallery-page .form-label {
        font-size: 16px;
        font-weight: 700;
        color: var(--gallery-text);
        margin-bottom: 8px;
    }

    .add-gallery-page .form-control,
    .add-gallery-page .form-select {
        border: 1px solid var(--gallery-border-strong);
        border-radius: 12px;
        min-height: 52px;
        background: #f7f9fc;
        font-size: 16px;
    }

    .add-gallery-page textarea.form-control {
        min-height: 110px;
        resize: vertical;
    }

    .add-gallery-page input[type="file"].form-control {
        padding: 0;
        min-height: 52px;
        line-height: 1.2;
        cursor: pointer;
    }

    .add-gallery-page input[type="file"].form-control::file-selector-button {
        height: 52px;
        margin: 0;
        border: 0;
        border-right: 1px solid var(--gallery-border-strong);
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        padding: 0 16px;
        background: #ffffff;
        color: var(--gallery-text);
        font-weight: 600;
        cursor: pointer;
    }

    .add-gallery-page input[type="file"].form-control::-webkit-file-upload-button {
        height: 52px;
        margin: 0;
        border: 0;
        border-right: 1px solid var(--gallery-border-strong);
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        padding: 0 16px;
        background: #ffffff;
        color: var(--gallery-text);
        font-weight: 600;
        cursor: pointer;
    }

    .add-gallery-page .form-control:focus,
    .add-gallery-page .form-select:focus {
        border-color: #87a6cb;
        box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
        background: #ffffff;
    }

    .add-gallery-page .btn-primary {
        border: 0;
        border-radius: 12px;
        padding: 11px 20px;
        background: linear-gradient(135deg, var(--gallery-primary-deep), var(--gallery-primary));
        font-weight: 700;
        box-shadow: 0 10px 20px rgba(19, 52, 90, 0.24);
    }

    .add-gallery-page .btn-secondary {
        border-radius: 12px;
        padding: 11px 20px;
        font-weight: 600;
    }

    .add-gallery-page .upload-hint {
        margin-top: 8px;
        color: var(--gallery-muted);
        font-size: 13px;
    }

    .add-gallery-page .action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    @media (max-width: 768px) {
        .add-gallery-page .page-title {
            font-size: 26px;
        }
    }
</style>

<div class="container-fluid add-gallery-page">
    <div class="page-shell">
    <div class="page-hero">
        <h3 class="page-title">Add New Gallery Image</h3>
        <p class="page-subtitle">Attach photos to any admin-managed gallery category.</p>
    </div>

    <div class="card gallery-form-card border-0">
        <div class="card-body">

            <?php if ($msg != "") { ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($msg, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>

            <?php if (empty($categories)) { ?>
                <div class="alert alert-warning">
                    Create a gallery category first in <a href="categories.php">Manage Categories</a> before uploading images.
                </div>
            <?php } ?>

            <form action="add_gallery.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">

                <div class="mb-3">
                    <label class="form-label">Select Category</label>
                    <input
                        type="text"
                        name="categoryName"
                        class="form-control"
                        list="galleryCategorySuggestions"
                        placeholder="Type category name"
                        value="<?php echo isset($categoryName) ? htmlspecialchars($categoryName, ENT_QUOTES, 'UTF-8') : ''; ?>"
                        required
                    >
                    <datalist id="galleryCategorySuggestions">
                        <?php foreach ($categories as $category) { ?>
                            <option value="<?php echo htmlspecialchars((string)$category['category_name'], ENT_QUOTES, 'UTF-8'); ?>"></option>
                        <?php } ?>
                    </datalist>
                    <div class="upload-hint">Need a new section first? Create it in <a href="categories.php">Manage Categories</a>.</div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image Name</label>
                    <input type="text" name="imageName" class="form-control" value="<?php echo htmlspecialchars($imgName, ENT_QUOTES, 'UTF-8'); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Image Description</label>
                    <textarea name="imgDesc" class="form-control"><?php echo htmlspecialchars($imgDesc, ENT_QUOTES, 'UTF-8'); ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="galleryImage" class="form-control" accept=".jpg,.jpeg,.png,.webp" required>
                    <div class="upload-hint">Allowed: JPG, PNG, WEBP</div>
                </div>

                <div class="action-row">
                    <button type="submit" name="addNewGallery" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add Gallery
                    </button>

                    <a href="gallery.php" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>

            </form>

        </div>
    </div>
    </div>

</div>

<?php include_once('../layout/footer.php'); ?>
