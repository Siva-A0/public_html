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

include_once('../layout/main_header.php');
?>
<style>
    .placements-admin-page {
        --pad-primary: #173d69;
        --pad-primary-deep: #13345a;
        --pad-accent: #f0b323;
        --pad-surface: #eef4fa;
        --pad-card: #ffffff;
        --pad-border: #d9e3ef;
        --pad-text: #163a61;
        --pad-muted: #6b819c;
    }

    .placements-admin-page .page-shell {
        background: linear-gradient(180deg, #f3f7fb 0%, var(--pad-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .placements-admin-page .page-hero,
    .placements-admin-page .panel-card {
        border: 1px solid var(--pad-border);
        border-radius: 22px;
        background: var(--pad-card);
        box-shadow: 0 14px 28px rgba(15, 23, 42, 0.06);
    }

    .placements-admin-page .page-hero {
        position: relative;
        overflow: hidden;
        padding: 24px 26px;
        margin-bottom: 18px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--pad-surface) 100%);
    }

    .placements-admin-page .page-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--pad-accent), #d79a12);
    }

    .placements-admin-page .page-title {
        margin: 0;
        color: var(--pad-primary-deep);
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.04em;
    }

    .placements-admin-page .page-subtitle {
        margin: 8px 0 0;
        color: var(--pad-muted);
        font-size: 0.98rem;
    }

    .placements-admin-page .panel-card {
        padding: 22px;
        margin-bottom: 18px;
    }

    .placements-admin-page .section-title {
        margin: 0 0 6px;
        color: var(--pad-primary-deep);
        font-size: 1.35rem;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .placements-admin-page .section-copy {
        margin: 0 0 18px;
        color: var(--pad-muted);
    }

    .placements-admin-page .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .placements-admin-page .stats-grid .form-control {
        min-height: 52px;
        border-radius: 12px;
        background: #f7f9fc;
        border: 1px solid #cfdceb;
    }

    .placements-admin-page .toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 16px;
    }

    .placements-admin-page .filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        width: 100%;
    }

    .placements-admin-page .filter-form .form-control,
    .placements-admin-page .filter-form .form-select {
        min-height: 50px;
        border-radius: 12px;
        border: 1px solid #cfdceb;
        background: #f7f9fc;
    }

    .placements-admin-page .action-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .placements-admin-page .btn-primary {
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, var(--pad-primary-deep), var(--pad-primary));
        box-shadow: 0 12px 20px rgba(19, 52, 90, 0.18);
        font-weight: 700;
    }

    .placements-admin-page .btn-outline-secondary,
    .placements-admin-page .btn-outline-danger,
    .placements-admin-page .btn-outline-primary {
        border-radius: 12px;
        font-weight: 600;
    }

    .placements-admin-page .table-wrap {
        overflow-x: auto;
    }

    .placements-admin-page table {
        margin-bottom: 0;
        vertical-align: middle;
    }

    .placements-admin-page thead th {
        background: #f7faff;
        color: var(--pad-primary);
        border-bottom-width: 1px;
        font-size: 0.83rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .placements-admin-page .placement-avatar {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        object-fit: cover;
        background: linear-gradient(135deg, #231c63, #3b3f82);
        color: #f0d98c;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
    }

    .placements-admin-page .badge-soft {
        background: rgba(59, 63, 130, 0.08);
        color: #3b3f82;
        border-radius: 999px;
        padding: 6px 10px;
        font-weight: 700;
    }

    @media (max-width: 991px) {
        .placements-admin-page .stats-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 767px) {
        .placements-admin-page .page-shell {
            padding: 16px;
        }

        .placements-admin-page .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-fluid placements-admin-page">
    <div class="page-shell">
        <div class="page-hero">
            <h1 class="page-title">Placements Control Panel</h1>
            <p class="page-subtitle">Manage placement records, recruiter-facing success metrics, and downloadable reports from one place.</p>
        </div>

        <?php if ($message !== '') { ?>
            <div class="alert alert-<?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php } ?>

        <section class="panel-card">
            <h2 class="section-title">Hero Statistics</h2>
            <p class="section-copy">These values appear on the public placements hero banner.</p>

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
                    <p class="section-copy">Manage student placements, batch tagging, company details, and profile photos.</p>
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
                                    <td><?php echo htmlspecialchars((string)($placement['package_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
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
                    <p class="section-copy">Keep placement reports and downloadable placement documents available on the public page.</p>
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
