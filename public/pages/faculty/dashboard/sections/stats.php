<section class="faculty-stat-grid">
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Qualification</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyQualification !== '' ? $facultyQualification : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note"></p>
    </article>
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Industry Experience</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyIndustryExp !== '' ? $facultyIndustryExp : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note"></p>
    </article>
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Teaching Experience</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyTeachingExp !== '' ? $facultyTeachingExp : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note"></p>
    </article>
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Profile Status</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyResearch !== '' ? 'Updated' : 'Basic', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note"></p>
    </article>
</section>
