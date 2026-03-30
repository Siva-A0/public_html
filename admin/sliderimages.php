<?php require_once(__DIR__ . '/../config.php'); ?>
<?php
if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('layout/main_header.php');
require_once(LIB_PATH . '/functions.class.php');

$message = "";
$type = "";
$selectedPos = "";

if (isset($_POST['changeImage'])) {
    $imagePos = $_POST['imagePos'];
    $selectedPos = $imagePos;
    $fileName = $_FILES['scollImage']['name'];
    $tmpName = $_FILES['scollImage']['tmp_name'];

    if ($imagePos === "" || $fileName == "") {
        $message = "Please select image position and choose an image.";
        $type = "danger";
    } else {
        if ($imagePos == 0) {
            $targetPath = "../public/assets/images/wise.png";
        } else {
            $targetPath = "../public/assets/images/sliderimages/image_" . $imagePos . ".png";
        }

        if (move_uploaded_file($tmpName, $targetPath)) {
            $message = "Image updated successfully.";
            $type = "success";
        } else {
            $message = "Image upload failed. Please try again.";
            $type = "danger";
        }
    }
}
?>

<div class="slider-page">
    <div class="slider-header">
        <h3 class="slider-title">Change Slider Images</h3>
        <p class="slider-subtitle">Update logo and homepage slider assets from one place.</p>
    </div>

    <div class="card slider-card border-0">
        <div class="card-body">

            <?php if ($message != "") { ?>
                <div class="alert alert-<?php echo $type; ?>">
                    <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Select Image Position</label>
                    <select name="imagePos" class="form-select" required>
                        <option value="">-- Select Position --</option>
                        <option value="0" <?php if ($selectedPos === "0") echo 'selected'; ?>>Logo</option>
                        <option value="1" <?php if ($selectedPos === "1") echo 'selected'; ?>>1st Position</option>
                        <option value="2" <?php if ($selectedPos === "2") echo 'selected'; ?>>2nd Position</option>
                        <option value="3" <?php if ($selectedPos === "3") echo 'selected'; ?>>3rd Position</option>
                        <option value="4" <?php if ($selectedPos === "4") echo 'selected'; ?>>4th Position</option>
                        <option value="5" <?php if ($selectedPos === "5") echo 'selected'; ?>>5th Position</option>
                        <option value="6" <?php if ($selectedPos === "6") echo 'selected'; ?>>6th Position</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload Image</label>
                    <div class="file-picker">
                        <input type="file" name="scollImage" id="sliderImageFile" class="d-none" accept=".png,.jpg,.jpeg,.webp" required>
                        <button type="button" class="btn btn-outline-secondary file-btn" id="sliderFileBtn">Choose File</button>
                        <input type="text" class="form-control file-name" id="sliderFileName" value="No file chosen" readonly>
                    </div>
                </div>

                <div class="mb-3 size-hint">
                    <div>Logo Size: <strong>1024px x 113px</strong></div>
                    <div>Slider Image Size: <strong>1004px x 300px</strong></div>
                </div>

                <div class="action-row">
                    <button type="submit" name="changeImage" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Update Image
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('sliderImageFile');
    var fileButton = document.getElementById('sliderFileBtn');
    var fileName = document.getElementById('sliderFileName');
    var form = fileInput ? fileInput.closest('form') : null;

    if (!fileInput || !fileButton || !fileName) {
        return;
    }

    fileButton.addEventListener('click', function () {
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        fileName.value = (fileInput.files && fileInput.files.length > 0)
            ? fileInput.files[0].name
            : 'No file chosen';
    });

    if (form) {
        form.addEventListener('reset', function () {
            setTimeout(function () {
                fileName.value = 'No file chosen';
            }, 0);
        });
    }
});
</script>

<?php include_once('layout/footer.php'); ?>

