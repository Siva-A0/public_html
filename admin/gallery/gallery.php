<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');

 
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbGallery = TB_GALLERY;
$tbGalleryCategory = TB_GALLERY_CATEGORY;

/* ---------- CATEGORY FILTER ---------- */

$selectedCategory = trim((string)($_GET['category'] ?? ''));

$categoriesList = $fcObj->getGalleryCategories($tbGalleryCategory);
$categoriesWithImages = $fcObj->getEventGallery($tbGallery);

if ($selectedCategory !== '') {
    $categories = $fcObj->getGalleryCategoryById($tbGalleryCategory, (int)$selectedCategory);
} else {
    $categories = $categoriesWithImages;
}

$noOfCategories = sizeof($categories);

for ($i=0; $i<$noOfCategories; $i++) {
    $galleryImages[$i] = $fcObj->getImagesForEvents($tbGallery, $categories[$i]['id']);
}

function getAdminGalleryImageUrl($fileName) {
    $fileName = trim((string)$fileName);
    if ($fileName === '') {
        return '';
    }

    $encoded = rawurlencode($fileName);
    $adminPath = __DIR__ . '/' . $fileName;
    if (file_exists($adminPath)) {
        return $encoded;
    }

    $legacyPath = dirname(__DIR__) . '/../gallery/' . $fileName;
    if (file_exists($legacyPath)) {
        return '../../gallery/' . $encoded;
    }

    return $encoded;
}
?>

<div class="container-fluid gallery-page">
    <div class="gallery-shell">

    <!-- Header -->
    <div class="gallery-header mb-4 d-flex justify-content-between align-items-start flex-wrap gap-3">

        <div>
            <h3 class="gallery-title">Gallery Management</h3>
            <p class="gallery-subtitle">Filter and manage gallery images category-wise.</p>
        </div>

        <div class="d-flex gap-2 flex-wrap">

            <!-- Filter Dropdown -->
            <form method="GET">
                <select name="category" class="form-select form-select-sm toolbar-select"
                        onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach($categoriesList as $categoryItem){ ?>
                        <option value="<?php echo (int)$categoryItem['id']; ?>"
                            <?php if($selectedCategory==(string)$categoryItem['id']) echo 'selected'; ?>>
                            <?php echo htmlspecialchars((string)$categoryItem['category_name'], ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php } ?>
                </select>
            </form>

            <a href="categories.php" class="btn btn-sm manage-categories-btn">
                <i class="bi bi-tags me-1"></i> Manage Categories
            </a>

            <a href="add_gallery.php" class="btn add-image-btn btn-sm">
                <i class="bi bi-plus-circle me-1"></i> Add Image
            </a>

        </div>
    </div>

    <!-- Category Sections -->
    <?php if ($noOfCategories === 0) { ?>
        <div class="card event-gallery-card border-0 mb-4">
            <div class="card-body">
                <div class="text-center text-muted py-4 empty-state">
                    No gallery categories with images are available yet.
                </div>
            </div>
        </div>
    <?php } ?>

    <?php for($i=0; $i<$noOfCategories; $i++) { ?>

        <div class="card event-gallery-card border-0 mb-4">

            <div class="card-header event-gallery-header d-flex justify-content-between align-items-center">

                <span class="fw-semibold">
                    <?php echo htmlspecialchars((string)$categories[$i]['event_name'], ENT_QUOTES, 'UTF-8'); ?>
                </span>

                <span class="badge bg-secondary event-count">
                    <?php echo sizeof($galleryImages[$i]); ?> Images
                </span>

            </div>

            <div class="card-body">

                <?php if (empty($galleryImages[$i])) { ?>

                    <div class="text-center text-muted py-4 empty-state">
                        No images available for this category.
                    </div>

                <?php } else { ?>

                    <div class="gallery-grid">

                        <?php foreach($galleryImages[$i] as $image) { ?>
                            <?php
                                $imageName = htmlspecialchars((string)$image['name'], ENT_QUOTES, 'UTF-8');
                            ?>

                            <div>

                                <div class="card image-card border-0 shadow-sm h-100">

                                    <img src="<?php echo htmlspecialchars((string)getAdminGalleryImageUrl($image['image_name']), ENT_QUOTES, 'UTF-8'); ?>"
                                         class="card-img-top"
                                         alt="Gallery Image">

                                    <div class="card-body text-center p-2">

                                        <small class="d-block mb-2 image-name">
                                            <?php echo $imageName; ?>
                                        </small>

                                        <a href="delete_gallery.php?image=<?php echo $image['id']; ?>"
                                           class="btn btn-sm btn-outline-danger delete-image-btn"
                                           onclick="return confirm('Are you sure you want to delete this image?')">
                                            Delete
                                        </a>

                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                <?php } ?>

            </div>

        </div>

    <?php } ?>

    </div>
</div>

<?php include_once('../layout/footer.php'); ?>

