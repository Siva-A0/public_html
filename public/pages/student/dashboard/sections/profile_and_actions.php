<section class="student-dashboard-grid">
    <div class="student-panel">
        <div class="student-panel-header">
            <div>
                <h2 class="student-panel-title">Student Profile Snapshot</h2>
                <!-- <p class="student-panel-subtitle">Core academic details connected to your account and class allocation.</p> -->
            </div>
            <span class="student-tag">Profile</span>
        </div>

        <div class="student-detail-list">
            <div class="student-detail-row">
                <div class="student-detail-label">Student Name</div>
                <div class="student-detail-value"><?php echo htmlspecialchars($fullName !== '' ? $fullName : (string)$user['username'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="student-detail-row">
                <div class="student-detail-label">Admission / Roll Number</div>
                <div class="student-detail-value"><?php echo htmlspecialchars((string)$user['admission_id'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="student-detail-row">
                <div class="student-detail-label">Class and Section</div>
                <div class="student-detail-value"><?php echo htmlspecialchars($userClassName . ' - ' . $userSectionName, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
            <div class="student-detail-row">
                <div class="student-detail-label">Mobile Number</div>
                <div class="student-detail-value"><?php echo htmlspecialchars((string)$user['mobile_no'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        </div>
    </div>

    <aside class="student-panel">
        <div class="student-panel-header">
            <div>
                <h2 class="student-panel-title">Quick Access</h2>
                <!-- <p class="student-panel-subtitle">Jump straight to the student tools you use most often.</p> -->
            </div>
            <span class="student-tag">Actions</span>
        </div>

        <div class="student-action-grid">
            <a class="student-action-card" href="<?php echo BASE_URL; ?>/public/pages/student/academics.php">
                <span class="student-action-icon"><i class="bi bi-journal-bookmark"></i></span>
                <span>
                    <span class="student-action-title">Open Academics</span>
                    <!-- <span class="student-action-copy">Review classroom information, academic links, and subject-facing resources.</span> -->
                </span>
            </a>

            <a class="student-action-card accent-soft" href="<?php echo BASE_URL; ?>/public/pages/student/downloads.php">
                <span class="student-action-icon"><i class="bi bi-cloud-arrow-down"></i></span>
                <span>
                    <span class="student-action-title">Downloads</span>
                    <!-- <span class="student-action-copy">Access files, shared resources, and downloadable academic content in one place.</span> -->
                </span>
            </a>

            <a class="student-action-card accent-gold" href="<?php echo BASE_URL; ?>/public/pages/student/studentsupport.php">
                <span class="student-action-icon"><i class="bi bi-headset"></i></span>
                <span>
                    <span class="student-action-title">Student Support</span>
                    <!-- <span class="student-action-copy">Reach support channels quickly whenever you need help from the portal team.</span> -->
                </span>
            </a>
        </div>
    </aside>
</section>

