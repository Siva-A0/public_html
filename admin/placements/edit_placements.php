<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php
$placementId = isset($_GET['placement']) ? (int)$_GET['placement'] : 0;
$mode = trim((string)($_GET['mode'] ?? ''));

$query = array();
if ($placementId > 0) {
    $query['placement'] = $placementId;
}
if ($mode !== '') {
    $query['mode'] = $mode;
}

$redirectUrl = BASE_URL . '/admin/placements/add_placements.php';
if (!empty($query)) {
    $redirectUrl .= '?' . http_build_query($query);
}

header('Location: ' . $redirectUrl);
exit;
