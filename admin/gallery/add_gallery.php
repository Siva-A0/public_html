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

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');

$categories = $fcObj->getGalleryCategories($tbGalleryCategory, true);
?>

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

