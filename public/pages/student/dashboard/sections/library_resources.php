<section class="student-panel" id="syllabus-section">
    <div class="student-panel-header">
        <div>
            <h2 class="student-panel-title">Library Resources</h2>
            <!-- <p class="student-panel-subtitle">Class-level documents and syllabus materials prepared for your current academic track.</p> -->
        </div>
        <span class="student-tag"><?php echo htmlspecialchars($userClassName, ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <?php if ($hasSyllabus) { ?>
        <div class="student-link-list">
            <a class="student-resource-link" href="<?php echo BASE_URL; ?>/public/uploads/syllabus/<?php echo rawurlencode($userSyllabusFile); ?>" target="_blank" rel="noopener noreferrer">
                <span>Download Syllabus</span>
                <i class="bi bi-arrow-up-right-circle"></i>
            </a>
        </div>
    <?php } else { ?>
        <!-- <div class="student-empty-note">No syllabus has been uploaded for your class yet. Once the academic team adds it, it will appear here.</div> -->
    <?php } ?>
</section>
