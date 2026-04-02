<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php

require_once(LIB_PATH . '/functions.class.php');
require_once(LIB_PATH . '/security.php');

$fcObj = new DataFunctions();

$tbPlacements = TB_PLACEMENTS;
$tbPlacementStats = TB_PLACEMENT_STATS;

$message = '';
$messageType = 'success';

if (isset($_POST['savePlacementStats'])) {
    if (!app_validate_csrf_token($_POST['csrf_token'] ?? '')) {
        $message = 'Your session expired. Please refresh and try again.';
        $messageType = 'danger';
    } else {
        $saved = $fcObj->savePlacementOverviewStats($tbPlacementStats, array(
            'students_placed' => trim((string)($_POST['students_placed'] ?? '')),
            'companies_visited' => trim((string)($_POST['companies_visited'] ?? '')),
            'highest_package' => trim((string)($_POST['highest_package'] ?? '')),
            'average_package' => trim((string)($_POST['average_package'] ?? ''))
        ));

        if ($saved !== false) {
            $message = 'Placement overview statistics updated successfully.';
        } else {
            $message = 'Unable to update placement statistics right now.';
            $messageType = 'danger';
        }
    }
}

$selectedBatch = trim((string)($_GET['batch'] ?? ''));
$searchTerm = trim((string)($_GET['search'] ?? ''));

$placementStats = $fcObj->getPlacementOverviewStats($tbPlacementStats, $tbPlacements);
$batchRows = $fcObj->getPlacementBatches($tbPlacements);
$placementRecords = $fcObj->getPlacementRecords($tbPlacements, array(
    'batch_label' => $selectedBatch,
    'search' => $searchTerm,
    'include_inactive' => 1
));
$placementDocuments = $fcObj->getPlacementDocuments($tbPlacements);

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_feature_pages.css';

include_once('../layout/main_header.php');
?>


<div class="container-fluid placements-admin-page">
    <div class="page-shell">
        <div class="page-hero">
            <h1 class="page-title">Placements Control Panel</h1>
            <!-- <p class="page-subtitle">Manage placement records, recruiter-facing success metrics, and downloadable reports from one place.</p> -->
        </div>

        <?php if ($message !== '') { ?>
            <div class="alert alert-<?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <section class="panel-card">
            <h2 class="section-title">Hero Statistics</h2>
            <!-- <p class="section-copy">These values appear on the public placements hero banner.</p> -->

            <form method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                <div class="stats-grid">
                    <div>
                        <label class="form-label">Students Placed</label>
                        <input type="text" class="form-control" name="students_placed" value="<?php echo htmlspecialchars((string)($placementStats['students_placed'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div>
                        <label class="form-label">Companies Visited</label>
                        <input type="text" class="form-control" name="companies_visited" value="<?php echo htmlspecialchars((string)($placementStats['companies_visited'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div>
                        <label class="form-label">Highest Package</label>
                        <input type="text" class="form-control" name="highest_package" value="<?php echo htmlspecialchars((string)($placementStats['highest_package'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 12 LPA">
                    </div>
                    <div>
                        <label class="form-label">Average Package</label>
                        <input type="text" class="form-control" name="average_package" value="<?php echo htmlspecialchars((string)($placementStats['average_package'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" placeholder="e.g. 5.8 LPA">
                    </div>
                </div>

                <div class="action-row mt-3">
                    <button type="submit" name="savePlacementStats" class="btn btn-primary">
                        <i class="bi bi-bar-chart-line me-1"></i> Save Statistics
                    </button>
                </div>
            </form>
        </section>

        <section class="panel-card">
            <div class="toolbar">
                <div>
                    <h2 class="section-title">Placement Records</h2>
                    <!-- <p class="section-copy">Manage student placements, batch tagging, company details, and profile photos.</p> -->
                </div>
                <div class="action-row">
                    <a href="add_placements.php" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add Placement
                    </a>
                    <a href="add_placements.php?mode=document" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-arrow-up me-1"></i> Upload Report
                    </a>
                </div>
            </div>

            <form method="GET" class="filter-form mb-3">
                <div class="flex-grow-1">
                                                <input type="search" class="form-control" name="search" value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search by student, company, batch, or year">
                </div>
                <div style="min-width: 220px;">
                    <select class="form-select" name="batch">
                        <option value="">All Batches</option>
                        <?php foreach ($batchRows as $batchRow) { ?>
                            <?php $batchLabel = trim((string)($batchRow['batch_label'] ?? '')); ?>
                            <?php if ($batchLabel !== '') { ?>
                                <option value="<?php echo htmlspecialchars($batchLabel, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $selectedBatch === $batchLabel ? ' selected' : ''; ?>>
                                    <?php echo htmlspecialchars($batchLabel, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="action-row">
                    <button type="submit" class="btn btn-outline-secondary">Filter</button>
                    <a href="placements.php" class="btn btn-outline-secondary">Reset</a>
                </div>
            </form>

            <div class="table-wrap">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Profile</th>
                            <th>Student</th>
                            <th>Batch / Year</th>
                            <th>Company</th>
                            <th>Package</th>
                            <th>Featured</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($placementRecords)) { ?>
                            <?php foreach ($placementRecords as $placement) { ?>
                                <?php
                                    $studentName = trim((string)($placement['student_name'] ?? ''));
                                    $photoFile = trim((string)($placement['profile_photo'] ?? ''));
                                    $photoUrl = $photoFile !== '' ? BASE_URL . '/public/uploads/placements/photos/' . rawurlencode($photoFile) : '';
                                    $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $studentName), 0, 2));
                                    if ($initials === '') {
                                        $initials = 'AI';
                                    }
                                ?>
                                <tr>
                                    <td>
                                        <?php if ($photoUrl !== '') { ?>
                                            <img src="<?php echo htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?>" class="placement-avatar">
                                        <?php } else { ?>
                                            <span class="placement-avatar"><?php echo htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold"><?php echo htmlspecialchars((string)($placement['batch_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars((string)($placement['academic_year'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></div>
                                    </td>
                                    <td><?php echo htmlspecialchars((string)($placement['company_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?php echo htmlspecialchars(trim((string)($placement['package_label'] ?? '')) !== '' ? (string)$placement['package_label'] : '-', ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <span class="badge-soft"><?php echo (int)($placement['is_featured'] ?? 0) === 1 ? 'Yes' : 'No'; ?></span>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-row justify-content-end">
                                            <a href="add_placements.php?placement=<?php echo (int)$placement['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form method="POST" action="delete_placements.php" onsubmit="return confirm('Delete this placement record?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="placement" value="<?php echo (int)$placement['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No placement records found for the current filters.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section class="panel-card">
            <div class="toolbar">
                <div>
                    <h2 class="section-title">Placement Reports</h2>
                    <!-- <p class="section-copy">Keep placement reports and downloadable placement documents available on the public page.</p> -->
                </div>
            </div>

            <div class="table-wrap">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>File</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($placementDocuments)) { ?>
                            <?php foreach ($placementDocuments as $documentRow) { ?>
                                <?php
                                    $docParts = explode('$$', (string)($documentRow['placement_desc'] ?? ''));
                                    $docTitle = trim((string)($docParts[0] ?? ''));
                                    $docFile = trim((string)($docParts[1] ?? ''));
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($docTitle, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td>
                                        <?php if ($docFile !== '') { ?>
                                            <a href="<?php echo BASE_URL; ?>/public/uploads/placements/<?php echo rawurlencode($docFile); ?>" target="_blank" rel="noopener noreferrer">
                                                <?php echo htmlspecialchars($docFile, ENT_QUOTES, 'UTF-8'); ?>
                                            </a>
                                        <?php } ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="action-row justify-content-end">
                                            <a href="add_placements.php?mode=document&placement=<?php echo (int)$documentRow['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                            <form method="POST" action="delete_placements.php" onsubmit="return confirm('Delete this placement report?');">
                                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(app_get_csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
                                                <input type="hidden" name="placement" value="<?php echo (int)$documentRow['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } else { ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">No placement reports uploaded yet.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<?php include_once('../layout/footer.php'); ?>
