<section class="student-stat-grid">
    <article class="student-stat-card">
        <p class="student-stat-label">Academic Year</p>
        <p class="student-stat-value"><?php echo htmlspecialchars($yearDisplay, ENT_QUOTES, 'UTF-8'); ?></p>
        <!-- <p class="student-stat-note">Current year detected from your class and section mapping.</p> -->
    </article>

    <article class="student-stat-card">
        <p class="student-stat-label">Syllabus Status</p>
        <p class="student-stat-value"><?php echo $hasSyllabus ? 'Ready' : 'Pending'; ?></p>
        <!-- <p class="student-stat-note"><?php echo $hasSyllabus ? 'Your class syllabus is available to open and download.' : 'No syllabus file has been uploaded for your class yet.'; ?></p> -->
    </article>

    <article class="student-stat-card">
        <p class="student-stat-label">Previous Papers</p>
        <p class="student-stat-value"><?php echo (int)$totalPaperFiles; ?></p>
        <!-- <p class="student-stat-note"><?php echo count($userPapers); ?> subject group(s) currently include exam paper resources.</p> -->
    </article>

    <article class="student-stat-card">
        <p class="student-stat-label">Study Materials</p>
        <p class="student-stat-value"><?php echo (int)$totalMaterialFiles; ?></p>
        <!-- <p class="student-stat-note"><?php echo count($userMaterials); ?> subject group(s) currently include learning materials.</p> -->
    </article>
</section>
