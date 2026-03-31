<section class="student-hero">
    <div class="student-hero-grid">
        <div class="student-photo-shell">
            <?php if ($profileImageUrl !== '') { ?>
                <img src="<?php echo htmlspecialchars($profileImageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="student-photo-fallback" style="display:none;"><?php echo htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } else { ?>
                <div class="student-photo-fallback"><?php echo htmlspecialchars($initials, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>
        </div>

        <div>
            <div class="student-kicker">Student Dashboard</div>
            <h1 class="student-display-name"><?php echo htmlspecialchars($displayName, ENT_QUOTES, 'UTF-8'); ?></h1>

            <div class="student-meta-line">
                <span class="student-meta-pill"><strong>Roll</strong> <?php echo htmlspecialchars((string)$user['admission_id'], ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="student-meta-pill"><strong>Class</strong> <?php echo htmlspecialchars($userClassName, ENT_QUOTES, 'UTF-8'); ?></span>
                <span class="student-meta-pill"><strong>Section</strong> <?php echo htmlspecialchars($userSectionName, ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

        
        </div>
    </div>
</section>
