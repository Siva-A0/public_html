<?php require_once(__DIR__ . '/../../config.php'); ?>
<?php

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbPlacements = TB_PLACEMENTS;
$tbPlacementStats = TB_PLACEMENT_STATS;

$selectedBatch = trim((string)($_GET['batch'] ?? ''));
if (strcasecmp($selectedBatch, 'all') === 0) {
    $selectedBatch = '';
}
$searchTerm = trim((string)($_GET['search'] ?? ''));

$placementStats = $fcObj->getPlacementOverviewStats($tbPlacementStats, $tbPlacements);
$batchRows = $fcObj->getPlacementBatches($tbPlacements);
$placements = $fcObj->getPlacementRecords($tbPlacements, array());
$placementDocuments = $fcObj->getPlacementDocuments($tbPlacements);

$batchOptions = array();
foreach ($batchRows as $batchRow) {
    $batchLabel = trim((string)($batchRow['batch_label'] ?? ''));
    if ($batchLabel !== '') {
        $batchOptions[] = $batchLabel;
    }
}

$documentCards = array();
foreach ($placementDocuments as $documentRow) {
    $rawDocument = trim((string)($documentRow['placement_desc'] ?? ''));
    if ($rawDocument === '') {
        continue;
    }
    $parts = explode('$$', $rawDocument);
    $docTitle = trim((string)($parts[0] ?? ''));
    $docFile = trim((string)($parts[1] ?? ''));
    if ($docTitle === '' || $docFile === '') {
        continue;
    }
    $documentCards[] = array(
        'id' => (int)$documentRow['id'],
        'title' => $docTitle,
        'file' => $docFile,
        'ext' => strtoupper((string)pathinfo($docFile, PATHINFO_EXTENSION))
    );
}
?>
<link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/assets/css/placements.css">

<div class="placement-page-shell">
    <section class="placements-hero">
        <div class="placements-hero-copy">
            <span class="placements-kicker">Placement Success Stories</span>
            <h1 class="placements-hero-title">Launching AIML talent into promising careers with confidence and clarity.</h1>
            <p class="placements-hero-text">
                Explore AIML student placement outcomes, recruiter presence, and career milestones across recent batches through a polished campus placement showcase.
            </p>
        </div>

        <div class="placements-stats-grid">
            <article class="placements-stat-card">
                <div class="placements-stat-icon placements-stat-icon-blue">
                    <i class="bi bi-mortarboard" aria-hidden="true"></i>
                </div>
                <span class="placements-stat-label">Students Placed</span>
                <strong><?php echo htmlspecialchars((string)($placementStats['students_placed'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article class="placements-stat-card">
                <div class="placements-stat-icon placements-stat-icon-green">
                    <i class="bi bi-buildings" aria-hidden="true"></i>
                </div>
                <span class="placements-stat-label">Companies Visited</span>
                <strong><?php echo htmlspecialchars((string)($placementStats['companies_visited'] ?? '0'), ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article class="placements-stat-card">
                <div class="placements-stat-icon placements-stat-icon-gold">
                    <i class="bi bi-rocket-takeoff" aria-hidden="true"></i>
                </div>
                <span class="placements-stat-label">Highest Package</span>
                <strong><?php echo htmlspecialchars((string)($placementStats['highest_package'] !== '' ? $placementStats['highest_package'] : 'To Be Updated'), ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
            <article class="placements-stat-card">
                <div class="placements-stat-icon placements-stat-icon-purple">
                    <i class="bi bi-graph-up-arrow" aria-hidden="true"></i>
                </div>
                <span class="placements-stat-label">Average Package</span>
                <strong><?php echo htmlspecialchars((string)($placementStats['average_package'] !== '' ? $placementStats['average_package'] : 'To Be Updated'), ENT_QUOTES, 'UTF-8'); ?></strong>
            </article>
        </div>
    </section>

    <?php if (!empty($documentCards)) { ?>
        <section class="placement-documents-panel" id="placement-reports">
            <div class="placements-results-meta">
                <div>
                    <h2 class="placements-section-title">Placement Reports</h2>
                    <p class="placements-section-text">Download official placement reports and related documents managed by the department.</p>
                </div>
            </div>

            <div class="placement-documents-grid">
                <?php foreach ($documentCards as $documentCard) { ?>
                    <article class="placement-document-card">
                        <div class="placement-document-ext"><?php echo htmlspecialchars((string)($documentCard['ext'] !== '' ? $documentCard['ext'] : 'FILE'), ENT_QUOTES, 'UTF-8'); ?></div>
                        <div class="placement-document-copy">
                            <h3><?php echo htmlspecialchars((string)$documentCard['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                            <a href="<?php echo BASE_URL; ?>/public/uploads/placements/<?php echo rawurlencode((string)$documentCard['file']); ?>" target="_blank" rel="noopener noreferrer">Open document</a>
                        </div>
                    </article>
                <?php } ?>
            </div>
        </section>
    <?php } ?>

    <section class="placements-toolbar">
        <div class="placements-filter-row">
            <button type="button" class="placements-filter-pill<?php echo $selectedBatch === '' ? ' is-active' : ''; ?>" data-placement-batch="">
                All Batches
            </button>
            <?php foreach ($batchOptions as $batchLabel) { ?>
                <button type="button" class="placements-filter-pill<?php echo $selectedBatch === $batchLabel ? ' is-active' : ''; ?>" data-placement-batch="<?php echo htmlspecialchars((string)$batchLabel, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars((string)$batchLabel, ENT_QUOTES, 'UTF-8'); ?>
                </button>
            <?php } ?>
            <?php if (!empty($documentCards)) { ?>
                <a class="placements-filter-pill placements-filter-pill-alt" href="#placement-reports">
                    View Reports
                </a>
            <?php } ?>
        </div>

        <form class="placements-search" onsubmit="return false;">
            <span class="placements-search-icon">
                <i class="bi bi-search" aria-hidden="true"></i>
            </span>
            <input
                type="search"
                name="search"
                id="placementsSearchInput"
                value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>"
                placeholder="Search placements"
                aria-label="Search placements"
                autocomplete="off"
            >
        </form>
    </section>

    <div class="placements-live-meta" id="placementsLiveMeta">
        Showing <strong id="placementsVisibleCount"><?php echo (int)count($placements); ?></strong> placement record<?php echo count($placements) === 1 ? '' : 's'; ?>
    </div>

    <section class="placements-grid" id="placementsGrid">
        <?php if (!empty($placements)) { ?>
            <?php foreach ($placements as $placement) { ?>
                <?php
                    $studentName = trim((string)($placement['student_name'] ?? ''));
                    $batchLabelValue = trim((string)($placement['batch_label'] ?? ''));
                    $academicYearValue = trim((string)($placement['academic_year'] ?? ''));
                    $companyNameValue = trim((string)($placement['company_name'] ?? ''));
                    $nameInitials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $studentName), 0, 2));
                    if ($nameInitials === '') {
                        $nameInitials = 'AI';
                    }
                    $photoFile = trim((string)($placement['profile_photo'] ?? ''));
                    $photoUrl = $photoFile !== '' ? BASE_URL . '/public/uploads/placements/photos/' . rawurlencode($photoFile) : '';
                    $searchBlob = strtolower(trim($studentName . ' ' . $companyNameValue . ' ' . $batchLabelValue . ' ' . $academicYearValue . ' aiml department ' . (string)($placement['package_label'] ?? '')));
                ?>
                <article
                    class="placement-student-card"
                    data-placement-batch="<?php echo htmlspecialchars($batchLabelValue, ENT_QUOTES, 'UTF-8'); ?>"
                    data-placement-search="<?php echo htmlspecialchars($searchBlob, ENT_QUOTES, 'UTF-8'); ?>"
                >
                    <div class="placement-student-top">
                        <?php if ($photoUrl !== '') { ?>
                            <img
                                src="<?php echo htmlspecialchars($photoUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?>"
                                class="placement-student-photo"
                            >
                        <?php } else { ?>
                            <div class="placement-student-photo placement-student-photo-fallback">
                                <?php echo htmlspecialchars($nameInitials, ENT_QUOTES, 'UTF-8'); ?>
                            </div>
                        <?php } ?>

                        <div class="placement-student-meta">
                            <div class="placement-badges">
                                <?php if (!empty($placement['batch_label'])) { ?>
                                    <span class="placement-badge"><?php echo htmlspecialchars((string)$placement['batch_label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php } ?>
                                <?php if (!empty($placement['academic_year'])) { ?>
                                    <span class="placement-badge placement-badge-soft"><?php echo htmlspecialchars((string)$placement['academic_year'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php } ?>
                                <?php if ((int)($placement['is_featured'] ?? 0) === 1) { ?>
                                    <span class="placement-badge placement-badge-gold">Featured</span>
                                <?php } ?>
                            </div>
                            <h3><?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <p>AIML Department</p>
                        </div>
                    </div>

                    <div class="placement-student-company">
                        <span class="placement-company-label">Placed At</span>
                        <strong><?php echo htmlspecialchars((string)($placement['company_name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                    </div>

                    <div class="placement-student-details placement-student-details-single">
                        <div>
                            <span>Package</span>
                            <strong><?php echo htmlspecialchars((string)($placement['package_label'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                        </div>
                    </div>
                </article>
            <?php } ?>
        <?php } else { ?>
            <div class="placements-empty-state">
                Placement records will appear here once the selected batch and search filters return results.
            </div>
        <?php } ?>
    </section>

    <div class="placements-empty-state" id="placementsFilteredEmpty" <?php echo !empty($placements) ? 'hidden' : ''; ?>>
        No placements match the selected batch or search term.
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var grid = document.getElementById('placementsGrid');
    var searchInput = document.getElementById('placementsSearchInput');
    var filterButtons = Array.prototype.slice.call(document.querySelectorAll('.placements-filter-pill[data-placement-batch]'));
    var cards = Array.prototype.slice.call(document.querySelectorAll('.placement-student-card'));
    var emptyState = document.getElementById('placementsFilteredEmpty');
    var visibleCount = document.getElementById('placementsVisibleCount');
    var selectedBatch = <?php echo json_encode($selectedBatch, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?> || '';

    if (!grid || !searchInput || cards.length === 0) {
        return;
    }

    function updateButtons() {
        filterButtons.forEach(function (button) {
            var batch = button.getAttribute('data-placement-batch') || '';
            button.classList.toggle('is-active', batch === selectedBatch);
        });
    }

    function applyFilters() {
        var term = (searchInput.value || '').trim().toLowerCase();
        var shown = 0;

        cards.forEach(function (card) {
            var batch = (card.getAttribute('data-placement-batch') || '').trim();
            var haystack = (card.getAttribute('data-placement-search') || '').toLowerCase();
            var batchMatch = selectedBatch === '' || batch === selectedBatch;
            var searchMatch = term === '' || haystack.indexOf(term) !== -1;
            var shouldShow = batchMatch && searchMatch;

            card.hidden = !shouldShow;
            if (shouldShow) {
                shown += 1;
            }
        });

        if (visibleCount) {
            visibleCount.textContent = String(shown);
        }

        if (emptyState) {
            emptyState.hidden = shown !== 0;
        }
    }

    filterButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var batch = button.getAttribute('data-placement-batch') || '';
            if (button.tagName === 'A') {
                return;
            }
            selectedBatch = batch;
            updateButtons();
            applyFilters();
        });
    });

    searchInput.addEventListener('input', applyFilters);

    updateButtons();
    applyFilters();
});
</script>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>
