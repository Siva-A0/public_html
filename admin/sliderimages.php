<?php require_once(__DIR__ . '/../config.php'); ?>
<?php
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

<style type="text/css">
    .slider-page .slider-header {
        position: relative;
        overflow: hidden;
        border: 1px solid #d9e3ef;
        border-radius: 18px;
        padding: 18px 22px;
        background:
            linear-gradient(135deg, #f9fbfe 0%, #eef4fa 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 18px;
    }

    .slider-page .slider-header::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, #f0b323, #d79a12);
    }

    .slider-page .slider-title {
        font-size: 24px;
        font-weight: 800;
        letter-spacing: -0.3px;
        color: #13345a;
        margin: 0;
    }

    .slider-page .slider-subtitle {
        margin: 8px 0 0;
        color: #6b819c;
        font-size: 14px;
    }

    .slider-page .slider-card {
        border: 1px solid #d9e3ef;
        border-radius: 16px;
        box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
        background: #ffffff;
    }

    .slider-page .form-label {
        font-size: 16px;
        font-weight: 700;
        color: #173d69;
        margin-bottom: 8px;
    }

    .slider-page .form-select,
    .slider-page .form-control {
        border: 1px solid #c8d6e6;
        border-radius: 12px;
        min-height: 48px;
        background: #f6faff;
        font-size: 15px;
    }

    .slider-page .form-select:focus,
    .slider-page .form-control:focus {
        border-color: #87a6cb;
        box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
        background: #ffffff;
    }

    .slider-page .file-picker {
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    .slider-page .file-btn {
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        min-height: 48px;
        padding: 0 16px;
        white-space: nowrap;
    }

    .slider-page .file-name {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: 0;
        min-height: 48px;
        display: flex;
        align-items: center;
    }

    .slider-page .size-hint {
        color: #6b819c;
        font-size: 14px;
        line-height: 1.6;
        background: linear-gradient(90deg, #f9fbfe, #eef4fa);
        border: 1px dashed #d9e3ef;
        border-radius: 14px;
        padding: 12px 14px;
    }

    .slider-page .btn-primary {
        border: 0;
        border-radius: 12px;
        padding: 10px 18px;
        background: linear-gradient(135deg, #13345a, #173d69);
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
    }

    .slider-page .btn-outline-secondary {
        border-radius: 12px;
        padding: 10px 18px;
        font-weight: 600;
        font-size: 16px;
    }

    .slider-page .btn-primary:hover {
        filter: brightness(1.06);
    }

    .slider-page .action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 4px;
    }

    @media (max-width: 768px) {
        .slider-page .slider-title {
            font-size: 22px;
        }

        .slider-page .form-label {
            font-size: 15px;
        }

        .slider-page .form-select,
        .slider-page .form-control,
        .slider-page .size-hint,
        .slider-page .btn-primary,
        .slider-page .btn-outline-secondary {
            font-size: 14px;
        }
    }
</style>

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
