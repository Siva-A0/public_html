<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$classId = isset($_GET['classId']) ? (int)$_GET['classId'] : 0;
$batchId = isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;

$tbSubject = TB_SUBJECTS;
$subjects = ($classId > 0 && $batchId > 0) ? $fcObj->getSubjectsForClass($tbSubject, $classId, $batchId) : array();
?>

<div class="form_field">
    <select name="subjId" id="subjId" class="subjId" required>
        <option value="">SELECT</option>
        <?php foreach ($subjects as $subj) { ?>
            <option value="<?php echo (int)$subj['id']; ?>">
                <?php
                $code = trim((string)($subj['sub_code'] ?? ''));
                $name = trim((string)($subj['sub_name'] ?? ''));
                $label = $code;
                if ($name !== '') {
                    $label = ($code !== '') ? ($code . ' - ' . $name) : $name;
                }
                echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
                ?>
            </option>
        <?php } ?>
    </select>
</div>
