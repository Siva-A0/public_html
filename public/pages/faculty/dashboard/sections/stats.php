<section class="faculty-stat-grid">
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Qualification</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyQualification !== '' ? $facultyQualification : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note">Academic credentials currently attached to your faculty profile.</p>
    </article>
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Industry Experience</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyIndustryExp !== '' ? $facultyIndustryExp : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note">Professional exposure recorded in your department profile.</p>
    </article>
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Teaching Experience</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyTeachingExp !== '' ? $facultyTeachingExp : 'NA', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note">Teaching background available to students and the department.</p>
    </article>
    <article class="faculty-stat-card">
        <p class="faculty-stat-label">Profile Status</p>
        <p class="faculty-stat-value"><?php echo htmlspecialchars($facultyResearch !== '' ? 'Updated' : 'Basic', ENT_QUOTES, 'UTF-8'); ?></p>
        <p class="faculty-stat-note">Add or refine research details to make your faculty profile more complete.</p>
    </article>
</section>
