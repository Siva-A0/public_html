<section class="faculty-dashboard-grid">
    <section class="faculty-panel">
        <div class="faculty-panel-header">
            <div>
                <h2 class="faculty-panel-title">Profile Snapshot</h2>
                <p class="faculty-panel-subtitle">Core faculty details organized for quick reading on desktop and mobile.</p>
            </div>
            <div class="faculty-tag">Academic Profile</div>
        </div>

        <div class="faculty-detail-list">
            <div class="faculty-detail-row">
                <div class="faculty-detail-label">Faculty Email</div>
                <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyEmail !== '' ? $facultyEmail : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="faculty-detail-row">
                <div class="faculty-detail-label">Designation</div>
                <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyDesignation !== '' ? $facultyDesignation : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="faculty-detail-row">
                <div class="faculty-detail-label">Qualification</div>
                <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyQualification !== '' ? $facultyQualification : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="faculty-detail-row">
                <div class="faculty-detail-label">Industry Experience</div>
                <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyIndustryExp !== '' ? $facultyIndustryExp : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="faculty-detail-row">
                <div class="faculty-detail-label">Teaching Experience</div>
                <div class="faculty-detail-value"><?php echo htmlspecialchars($facultyTeachingExp !== '' ? $facultyTeachingExp : 'Not available', ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        </div>
    </section>

    <section class="faculty-panel">
        <div class="faculty-panel-header">
            <div>
                <h2 class="faculty-panel-title">Quick Actions</h2>
                <p class="faculty-panel-subtitle"></p>
            </div>
            <div class="faculty-tag">Navigation</div>
        </div>

        <div class="faculty-action-stack">
            <a href="<?php echo BASE_URL; ?>/public/pages/department/department.php" class="faculty-action-card">
                <span class="faculty-action-icon"><i class="bi bi-building"></i></span>
                <span>
                    <span class="faculty-action-title">View Department</span>
                    <span class="faculty-action-text"></span>
                </span>
            </a>
            <a href="<?php echo BASE_URL; ?>/public/pages/gallery.php" class="faculty-action-card accent-soft">
                <span class="faculty-action-icon"><i class="bi bi-image"></i></span>
                <span>
                    <span class="faculty-action-title">Open Gallery</span>
                    <span class="faculty-action-text"></span>
                </span>
            </a>
            <a href="<?php echo BASE_URL; ?>/public/pages/Authentication/logout.php" class="faculty-action-card accent-gold">
                <span class="faculty-action-icon"><i class="bi bi-box-arrow-right"></i></span>
                <span>
                    <span class="faculty-action-title">Logout Securely</span>
                    <span class="faculty-action-text"></span>
                </span>
            </a>
        </div>
    </section>
</section>
