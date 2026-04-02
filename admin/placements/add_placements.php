<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

if (!function_exists('placement_store_uploaded_document')) {
    function placement_store_uploaded_document($file, $targetDir, $baseName, &$errorMessage = '', $maxSize = 6291456)
    {
        $errorMessage = '';

        if (!is_array($file) || !isset($file['error']) || (int)$file['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = 'File upload failed. Please try again.';
            return '';
        }

        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errorMessage = 'Invalid upload source.';
            return '';
        }

        if (isset($file['size']) && (int)$file['size'] > $maxSize) {
            $errorMessage = 'Uploaded document is too large.';
            return '';
        }

        $allowedExtensions = array('pdf', 'doc', 'docx');
        $originalName = (string)($file['name'] ?? '');
        $extension = strtolower((string)pathinfo($originalName, PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedExtensions, true)) {
            $errorMessage = 'Only PDF, DOC, and DOCX files are allowed.';
            return '';
        }

        $baseName = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$baseName);
        if ($baseName === '') {
            $baseName = 'placement_report';
        }

        if (!is_dir($targetDir) && !@mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            $errorMessage = 'Unable to prepare the upload directory.';
            return '';
        }

        $fileName = $baseName . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destination = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $errorMessage = 'Unable to save the uploaded document.';
            return '';
        }

        return $fileName;
    }
}

$fcObj = new DataFunctions();

$tbPlacements = TB_PLACEMENTS;

$placementId = isset($_GET['placement']) ? (int)$_GET['placement'] : 0;
$placementRow = $placementId > 0 ? $fcObj->getPlacementRecordById($tbPlacements, $placementId) : array();

$mode = trim((string)($_GET['mode'] ?? ''));
if ($mode === '' && !empty($placementRow) && (int)($placementRow['category_id'] ?? 0) === (int)DOCUMENT) {
    $mode = 'document';
}
if ($mode !== 'document') {
    $mode = 'student';
}

$message = '';

$formData = array(
    'academic_year' => trim((string)($placementRow['academic_year'] ?? '')),
    'batch_label' => trim((string)($placementRow['batch_label'] ?? '')),
    'student_name' => trim((string)($placementRow['student_name'] ?? '')),
    'company_name' => trim((string)($placementRow['company_name'] ?? '')),
    'package_label' => trim((string)($placementRow['package_label'] ?? '')),
    'profile_photo' => trim((string)($placementRow['profile_photo'] ?? '')),
    'is_featured' => !empty($placementRow['is_featured']) ? 1 : 0
);

$documentParts = explode('$$', (string)($placementRow['placement_desc'] ?? ''));
$documentTitle = trim((string)($documentParts[0] ?? ''));
$documentFile = trim((string)($documentParts[1] ?? ''));

if (isset($_POST['savePlacement'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please refresh and try again.';
    } else {
        $mode = trim((string)($_POST['mode'] ?? 'student')) === 'document' ? 'document' : 'student';

        if ($mode === 'student') {
            $formData['academic_year'] = trim((string)($_POST['academic_year'] ?? ''));
            $formData['batch_label'] = trim((string)($_POST['batch_label'] ?? ''));
            $formData['student_name'] = trim((string)($_POST['student_name'] ?? ''));
            $formData['company_name'] = trim((string)($_POST['company_name'] ?? ''));
            $formData['package_label'] = trim((string)($_POST['package_label'] ?? ''));
            $formData['is_featured'] = isset($_POST['is_featured']) ? 1 : 0;

            if ($formData['academic_year'] === '' || $formData['batch_label'] === '' || $formData['student_name'] === '' || $formData['company_name'] === '') {
                $message = 'Please fill in all required placement fields.';
            } else {
                $uploadDir = __DIR__ . '/../../public/uploads/placements/photos';
                $existingPhoto = trim((string)($placementRow['profile_photo'] ?? ''));
                $profilePhoto = $existingPhoto;

                if (
                    !empty($_POST['profile_photo_cropped'])
                    || (isset($_FILES['profile_photo']) && (int)($_FILES['profile_photo']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK)
                ) {
                    $uploadError = '';
                    $photoBase = 'placement_' . preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower(str_replace(' ', '_', $formData['student_name'])));
                    $uploadedPhoto = app_store_processed_image($_FILES['profile_photo'], $_POST['profile_photo_cropped'] ?? '', $uploadDir, $photoBase, $uploadError, 3 * 1024 * 1024);
                    if ($uploadedPhoto === '') {
                        $message = $uploadError;
                    } else {
                        $profilePhoto = $uploadedPhoto;
                    }
                }

                if ($message === '') {
                    $saved = $fcObj->savePlacementRecord($tbPlacements, array(
                        'academic_year' => $formData['academic_year'],
                        'batch_label' => $formData['batch_label'],
                        'student_name' => $formData['student_name'],
                        'course_branch' => 'AIML',
                        'company_name' => $formData['company_name'],
                        'role_title' => '',
                        'package_label' => $formData['package_label'],
                        'package_sort' => '',
                        'profile_photo' => $profilePhoto,
                        'is_featured' => $formData['is_featured'],
                        'is_active' => 1,
                        'sort_order' => 0,
                        'placement_desc' => $formData['student_name'] . ' placed at ' . $formData['company_name']
                    ), $placementId);

                    if ($saved !== false) {
                        if ($profilePhoto !== $existingPhoto && $existingPhoto !== '') {
                            $oldPath = $uploadDir . DIRECTORY_SEPARATOR . basename($existingPhoto);
                            if (is_file($oldPath)) {
                                @unlink($oldPath);
                            }
                        }
                        header('Location: placements.php');
                        exit;
                    }

                    if ($profilePhoto !== $existingPhoto && $profilePhoto !== '') {
                        $uploadedPath = $uploadDir . DIRECTORY_SEPARATOR . basename($profilePhoto);
                        if (is_file($uploadedPath)) {
                            @unlink($uploadedPath);
                        }
                    }
                    $dbError = trim((string)$fcObj->dbObj->getLastError());
                    $message = $dbError !== '' ? ('Unable to save this placement record. ' . $dbError) : 'Unable to save this placement record.';
                }
            }
        } else {
            $documentTitle = trim((string)($_POST['document_title'] ?? ''));
            if ($documentTitle === '') {
                $message = 'Please enter a document title.';
            } else {
                $uploadDir = __DIR__ . '/../../public/uploads/placements';
                $savedFile = $documentFile;
                if (isset($_FILES['document_file']) && (int)($_FILES['document_file']['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_OK) {
                    $uploadError = '';
                    $docBase = 'placement_report_' . preg_replace('/[^a-zA-Z0-9_-]/', '', strtolower(str_replace(' ', '_', $documentTitle)));
                    $uploadedDoc = placement_store_uploaded_document($_FILES['document_file'], $uploadDir, $docBase, $uploadError);
                    if ($uploadedDoc === '') {
                        $message = $uploadError;
                    } else {
                        $savedFile = $uploadedDoc;
                    }
                }

                if ($message === '' && $savedFile === '') {
                    $message = 'Please upload a document file.';
                }

                if ($message === '') {
                    $saved = $fcObj->savePlacementDocument($tbPlacements, $documentTitle, $savedFile, $placementId);
                    if ($saved !== false) {
                        if ($savedFile !== $documentFile && $documentFile !== '') {
                            $oldDocPath = $uploadDir . DIRECTORY_SEPARATOR . basename($documentFile);
                            if (is_file($oldDocPath)) {
                                @unlink($oldDocPath);
                            }
                        }
                        header('Location: placements.php');
                        exit;
                    }

                    if ($savedFile !== $documentFile && $savedFile !== '') {
                        $uploadedDocPath = $uploadDir . DIRECTORY_SEPARATOR . basename($savedFile);
                        if (is_file($uploadedDocPath)) {
                            @unlink($uploadedDocPath);
                        }
                    }
                    $dbError = trim((string)$fcObj->dbObj->getLastError());
                    $message = $dbError !== '' ? ('Unable to save this placement report. ' . $dbError) : 'Unable to save this placement report.';
                }
            }
        }
    }
}

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_feature_pages.css';

include_once('../layout/main_header.php');
?>


<div class="container-fluid placement-form-page">
    <div class="page-shell">
        <div class="page-hero">
            <h1 class="page-title"><?php echo $placementId > 0 ? 'Edit Placement Entry' : ($mode === 'document' ? 'Upload Placement Report' : 'Add Placement Record'); ?></h1>
            <p class="page-subtitle">
                <?php echo $mode === 'document' ? 'Manage placement reports that appear on the public placements page.' : 'Add or update a student placement with photo, company, optional package, and batch details.'; ?>
            </p>
        </div>

        <?php if ($message !== '') { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></div>
        <?php } ?>

        <div class="form-card">
            <form method="POST" enctype="multipart/form-data" id="placementForm">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="mode" value="<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="profile_photo_cropped" id="profile_photo_cropped" value="">

                <?php if ($mode === 'student') { ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Academic Year</label>
                            <input type="text" class="form-control" name="academic_year" value="<?php echo htmlspecialchars($formData['academic_year'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 2023-2024" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Batch</label>
                            <input type="text" class="form-control" name="batch_label" value="<?php echo htmlspecialchars($formData['batch_label'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. Batch 2024" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Student Name</label>
                            <input type="text" class="form-control" name="student_name" value="<?php echo htmlspecialchars($formData['student_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Company Name</label>
                            <input type="text" class="form-control" name="company_name" value="<?php echo htmlspecialchars($formData['company_name'], ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Package Label <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" name="package_label" value="<?php echo htmlspecialchars($formData['package_label'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 8.5 LPA">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Photo</label>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-secondary text-start" id="placement_photo_btn">Choose New Photo</button>
                                <input type="text" class="form-control" id="placement_photo_name" value="<?php echo $formData['profile_photo'] !== '' ? 'Current photo loaded' : 'No photo selected'; ?>" readonly>
                            </div>
                            <input type="file" class="d-none" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png,.webp">
                            <div class="square-cropper mt-3" id="placement_cropper" hidden>
                                <div class="square-cropper-stage" id="placement_cropper_stage">
                                    <img id="placement_cropper_image" alt="Crop preview" draggable="false" style="display:none;">
                                    <div class="square-cropper-frame"></div>
                                </div>
                                <div class="square-cropper-controls">
                                    <label class="square-cropper-slider-row" for="placement_cropper_zoom">
                                        <span>Zoom</span>
                                        <input type="range" id="placement_cropper_zoom" class="square-cropper-slider" min="1" max="3" step="0.01" value="1">
                                        <span>3x</span>
                                    </label>
                                    <div class="square-cropper-actions">
                                        <button type="button" class="square-cropper-btn" id="placement_cropper_reset">Reset</button>
                                    </div>
                                </div>
                                <div class="square-cropper-help">Drag the photo inside the square to set how the placement profile image should appear.</div>
                            </div>
                            <div class="form-text">Allowed formats: JPG, PNG, WEBP.</div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"<?php echo (int)$formData['is_featured'] === 1 ? ' checked' : ''; ?>>
                                <label class="form-check-label" for="is_featured">Mark as featured placement</label>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="form-label">Document Title</label>
                            <input type="text" class="form-control" name="document_title" value="<?php echo htmlspecialchars($documentTitle, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Upload File</label>
                            <input type="file" class="form-control" name="document_file" accept=".pdf,.doc,.docx"<?php echo $documentFile === '' ? ' required' : ''; ?>>
                            <div class="form-text">Allowed formats: PDF, DOC, DOCX.</div>
                        </div>
                        <?php if ($documentFile !== '') { ?>
                            <div class="col-12">
                                <div class="alert alert-info mb-0">
                                    Current file:
                                    <a href="<?php echo BASE_URL; ?>/public/uploads/placements/<?php echo rawurlencode($documentFile); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo htmlspecialchars($documentFile, ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>

                <div class="d-flex gap-2 flex-wrap mt-4">
                    <button type="submit" name="savePlacement" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i> Save
                    </button>
                    <a href="placements.php" class="btn btn-outline-secondary">Cancel</a>
                    <?php if ($mode === 'student') { ?>
                        <a href="add_placements.php?mode=document" class="btn btn-outline-primary">Switch to report upload</a>
                    <?php } else { ?>
                        <a href="add_placements.php" class="btn btn-outline-primary">Switch to placement form</a>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>/public/assets/js/square-image-cropper.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var fileInput = document.getElementById('profile_photo');
    var fileButton = document.getElementById('placement_photo_btn');
    var fileName = document.getElementById('placement_photo_name');

    if (fileInput && fileButton && fileName) {
        fileButton.addEventListener('click', function () {
            fileInput.click();
        });

        fileInput.addEventListener('change', function () {
            fileName.value = (fileInput.files && fileInput.files.length > 0) ? fileInput.files[0].name : 'No new file selected';
        });
    }

    window.initSquareImageCropper({
        formId: 'placementForm',
        fileInputId: 'profile_photo',
        hiddenInputId: 'profile_photo_cropped',
        wrapperId: 'placement_cropper',
        stageId: 'placement_cropper_stage',
        imageId: 'placement_cropper_image',
        sliderId: 'placement_cropper_zoom',
        resetButtonId: 'placement_cropper_reset',
        initialImageUrl: <?php echo json_encode($formData['profile_photo'] !== '' ? (BASE_URL . '/public/uploads/placements/photos/' . rawurlencode($formData['profile_photo'])) : '', JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
    });
});
</script>

<?php include_once('../layout/footer.php'); ?>

