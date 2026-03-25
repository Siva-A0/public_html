<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();
$tbPlacements = TB_PLACEMENTS;

$placementId = 0;
if (isset($_POST['placement'])) {
    $placementId = (int)$_POST['placement'];
} elseif (isset($_GET['placement'])) {
    $placementId = (int)$_GET['placement'];
}

$validRequest = true;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validRequest = app_validate_csrf_token($_POST['csrf_token'] ?? '');
}

if ($placementId > 0 && $validRequest) {
    $placementRow = $fcObj->getPlacementRecordById($tbPlacements, $placementId);
    $deleted = $fcObj->deletePlacementRecord($tbPlacements, $placementId);

    if ($deleted !== false && !empty($placementRow)) {
        $photoFile = trim((string)($placementRow['profile_photo'] ?? ''));
        if ($photoFile !== '') {
            $photoPath = __DIR__ . '/../../public/uploads/placements/photos/' . basename($photoFile);
            if (is_file($photoPath)) {
                @unlink($photoPath);
            }
        }

        if ((int)($placementRow['category_id'] ?? 0) === (int)DOCUMENT) {
            $parts = explode('$$', (string)($placementRow['placement_desc'] ?? ''));
            $docFile = trim((string)($parts[1] ?? ''));
            if ($docFile !== '') {
                $docPath = __DIR__ . '/../../public/uploads/placements/' . basename($docFile);
                if (is_file($docPath)) {
                    @unlink($docPath);
                }
            }
        }
    }
}

header('Location: placements.php');
exit;
