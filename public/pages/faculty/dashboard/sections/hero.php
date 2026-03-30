<section class="faculty-hero">
    <div class="faculty-hero-grid">
        <div class="faculty-portrait">
            <?php if ($facultyImageUrl !== '') { ?>
                <img src="<?php echo htmlspecialchars($facultyImageUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($facultyDisplayName, ENT_QUOTES, 'UTF-8'); ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="faculty-portrait-fallback" style="display:none;"><?php echo htmlspecialchars(strtoupper(substr($facultyName, 0, 2)), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } else { ?>
                <div class="faculty-portrait-fallback"><?php echo htmlspecialchars(strtoupper(substr($facultyName, 0, 2)), ENT_QUOTES, 'UTF-8'); ?></div>
            <?php } ?>
        </div>

        <div>
            <div class="faculty-kicker">Faculty Dashboard</div>
            <h1 class="faculty-display-name"><?php echo htmlspecialchars($facultyDisplayName, ENT_QUOTES, 'UTF-8'); ?></h1>
            <div class="faculty-meta-line">
                <div class="faculty-meta-pill"><strong>Designation</strong><span><?php echo htmlspecialchars($facultyDesignation !== '' ? $facultyDesignation : 'Not available', ENT_QUOTES, 'UTF-8'); ?></span></div>
                <div class="faculty-meta-pill"><strong>Email</strong><span><?php echo htmlspecialchars($facultyEmail !== '' ? $facultyEmail : 'Not available', ENT_QUOTES, 'UTF-8'); ?></span></div>
            </div>
            <p class="faculty-hero-copy">This workspace brings your faculty profile, experience snapshot, research notes, and quick navigation into one cleaner dashboard that also adapts better on mobile screens.</p>
        </div>
    </div>
</section>
