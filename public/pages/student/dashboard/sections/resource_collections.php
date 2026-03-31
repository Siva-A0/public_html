<section class="student-dashboard-grid">
    <div class="student-panel">
        <div class="student-panel-header">
            <div>
                <h2 class="student-panel-title">Previous Year Papers</h2>
                <!-- <p class="student-panel-subtitle">Browse subject-wise question papers available for revision and exam preparation.</p> -->
            </div>
            <span class="student-tag">Papers</span>
        </div>

        <?php if (!empty($userPapers)) { ?>
            <div class="student-resource-groups">
                <?php foreach ($userPapers as $paperGroup) { ?>
                    <?php
                        $code = trim((string)($paperGroup['subject_code'] ?? ''));
                        $name = trim((string)($paperGroup['subject_name'] ?? ''));
                        $label = $code;
                        if ($name !== '') {
                            $label = ($code !== '') ? ($code . ' - ' . $name) : $name;
                        }
                    ?>
                    <div class="student-resource-group">
                        <div class="student-resource-head">
                            <h3 class="student-resource-title"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <span class="student-resource-chip"><?php echo count($paperGroup['papers']); ?> file(s)</span>
                        </div>
                        <div class="student-link-list">
                            <?php foreach ($paperGroup['papers'] as $paper) { ?>
                                <?php
                                    $paperFile = trim((string)$paper['paper_file']);
                                    $paperPath = ROOT_PATH . '/public/uploads/previous_papers/' . $paperFile;
                                    $isValidPaper = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $paperFile) === 1;
                                    $paperName = (string)($paper['paper_name'] ?? 'Question Paper');
                                ?>
                                <?php if ($paperFile !== '' && $isValidPaper && file_exists($paperPath)) { ?>
                                    <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/previous_papers/<?php echo rawurlencode($paperFile); ?>" target="_blank" rel="noopener noreferrer">
                                        <span><?php echo htmlspecialchars($paperName, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                    </a>
                                <?php } else { ?>
                                    <div class="student-empty-note"><?php echo htmlspecialchars($paperName, ENT_QUOTES, 'UTF-8'); ?> is listed, but the file is currently unavailable.</div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- <div class="student-empty-note">No previous year papers have been uploaded for your class yet.</div> -->
        <?php } ?>
    </div>

    <div class="student-panel">
        <div class="student-panel-header">
            <div>
                <h2 class="student-panel-title">Notes and Materials</h2>
                <!-- <p class="student-panel-subtitle">Subject-wise notes, files, and study references shared for your classroom learning.</p> -->
            </div>
            <span class="student-tag">Materials</span>
        </div>

        <?php if (!empty($userMaterials)) { ?>
            <div class="student-resource-groups">
                <?php foreach ($userMaterials as $group) { ?>
                    <?php
                        $code = trim((string)($group['subject_code'] ?? ''));
                        $name = trim((string)($group['subject_name'] ?? ''));
                        $label = $code;
                        if ($name !== '') {
                            $label = ($code !== '') ? ($code . ' - ' . $name) : $name;
                        }
                    ?>
                    <div class="student-resource-group">
                        <div class="student-resource-head">
                            <h3 class="student-resource-title"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></h3>
                            <span class="student-resource-chip"><?php echo count($group['materials']); ?> file(s)</span>
                        </div>
                        <div class="student-link-list">
                            <?php foreach ($group['materials'] as $material) { ?>
                                <?php
                                    $materialFile = trim((string)($material['mater_file'] ?? ''));
                                    $materialPath = ROOT_PATH . '/public/uploads/materials/' . $materialFile;
                                    $isValidMaterial = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $materialFile) === 1;
                                    $materialName = (string)($material['material_name'] ?? 'Material');
                                ?>
                                <?php if ($materialFile !== '' && $isValidMaterial && file_exists($materialPath)) { ?>
                                    <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/materials/<?php echo rawurlencode($materialFile); ?>" target="_blank" rel="noopener noreferrer">
                                        <span><?php echo htmlspecialchars($materialName, ENT_QUOTES, 'UTF-8'); ?></span>
                                        <i class="bi bi-file-earmark-arrow-down"></i>
                                    </a>
                                <?php } else { ?>
                                    <div class="student-empty-note"><?php echo htmlspecialchars($materialName, ENT_QUOTES, 'UTF-8'); ?> is listed, but the file is currently unavailable.</div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- <div class="student-empty-note">No materials have been uploaded for your class yet.</div> -->
        <?php } ?>
    </div>
</section>
