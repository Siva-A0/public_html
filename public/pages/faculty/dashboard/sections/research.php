<section class="faculty-panel">
    <div class="faculty-panel-header">
        <div>
            <h2 class="faculty-panel-title">Research and Expertise</h2>
            <p class="faculty-panel-subtitle"></p>
        </div>
        <div class="faculty-tag">Research</div>
    </div>

    <?php if ($facultyResearch !== '') { ?>
        <div class="faculty-research-block"><?php echo nl2br(htmlspecialchars($facultyResearch, ENT_QUOTES, 'UTF-8')); ?></div>
    <?php } else { ?>
        <div class="faculty-empty-note"></div>
    <?php } ?>
</section>
