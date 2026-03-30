<section class="faculty-panel">
    <div class="faculty-panel-header">
        <div>
            <h2 class="faculty-panel-title">Research and Expertise</h2>
            <p class="faculty-panel-subtitle">Your research, interests, and expertise summary shown in a cleaner reading layout.</p>
        </div>
        <div class="faculty-tag">Research</div>
    </div>

    <?php if ($facultyResearch !== '') { ?>
        <div class="faculty-research-block"><?php echo nl2br(htmlspecialchars($facultyResearch, ENT_QUOTES, 'UTF-8')); ?></div>
    <?php } else { ?>
        <div class="faculty-empty-note">No research details have been added yet. This section is ready for future profile enrichment.</div>
    <?php } ?>
</section>
