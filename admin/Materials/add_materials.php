<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbBatch    = TB_BATCH;
$tbClass    = TB_CLASS;
$tbSubject  = TB_SUBJECTS;
$tbMaterial = TB_MATERAILS;

$batches = $fcObj->getBatches($tbBatch);
$batchesCnt = sizeof($batches);

$classes = $fcObj->getClassesWOPO($tbClass);
$classesCnt = sizeof($classes);

$batchId = isset($_POST['batchId']) ? (int)$_POST['batchId'] : (int)($_GET['batchId'] ?? 0);
$classId = isset($_POST['classId']) ? (int)$_POST['classId'] : 0;

if (isset($_POST['addNewMaterial'])) {
    $batchId = (int)($_POST['batchId'] ?? 0);
    $classId = (int)($_POST['classId'] ?? 0);

    $varArray = array();
    $varArray['class_id'] = $classId;
    $varArray['subj_id'] = (int)($_POST['subjId'] ?? 0);
    $varArray['material_name'] = trim((string)($_POST['materialName'] ?? ''));

    if ($batchId <= 0 || $classId <= 0 || $varArray['subj_id'] <= 0 || $varArray['material_name'] === '') {
        $msg = 'Please select Batch, Class, Subject and enter Material Name.';
    } else {
        $fileName = isset($_FILES['materialFile']['name']) ? (string)$_FILES['materialFile']['name'] : '';
        $uploadDir = ROOT_PATH . '/public/uploads/materials/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        if ($fileName !== '' && isset($_FILES['materialFile']['tmp_name']) && move_uploaded_file($_FILES['materialFile']['tmp_name'], $uploadDir . $fileName)) {
            $varArray['material_file_name'] = $fileName;
        } else {
            $varArray['material_file_name'] = '';
            $msg = 'File upload failed. Please try again.';
        }

        if (!isset($msg)) {
            $addMaterial = $fcObj->addMaterial($tbMaterial, $varArray);
            if ($addMaterial) {
                header('Location: materials.php?batchId=' . $batchId);
                exit;
            }

            $msg = 'Sorry, Please try again';
        }
    }
}

if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_academic_pages.css';

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>

<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter"></span>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="add-material-page">
						<div class="material-hero">
							<h1>Add Material</h1>
							<p>Upload a material file and map it to the correct batch, class, and subject.</p>
						</div>
						<div class="comteeMem">
							<?php
								if( isset ( $msg ) ){
							?>
								<div class="comteeMemRow">
									<div class="usersDetHeader">
										<?php echo $msg;?>
									</div>
								</div>
							<?php
								}
							?>
							<form id='addMaterial' action='add_materials.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for="batchId">Batch:</label>
									</div>
									<div class="form_field">
										<select name="batchId" id="batchId" class="batchId" required>
											<option value="">SELECT</option>
											<?php for($i=0;$i<$batchesCnt;$i++){ ?>
												<option value="<?php echo (int)$batches[$i]['id']; ?>" <?php echo ($batchId === (int)$batches[$i]['id']) ? 'selected="selected"' : ''; ?>>
													<?php echo htmlspecialchars((string)$batches[$i]['batch'], ENT_QUOTES, 'UTF-8'); ?>
												</option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='classes' >Class:</label>
									</div>
									<div class="form_field">
										<select name="classId" id="classId" class="classId" required>
											<option value="">SELECT</option>
											<?php
												for($i=0;$i<$classesCnt;$i++){
													?>
														<option value="<?php echo (int)$classes[$i]['id']; ?>" <?php echo ($classId === (int)$classes[$i]['id']) ? 'selected="selected"' : ''; ?>>
															<?php echo htmlspecialchars((string)$classes[$i]['class_name'], ENT_QUOTES, 'UTF-8'); ?>
														</option>
													<?php
												}
											?>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='subject' >Subject:</label>
									</div>
									<div class="form_field" id="subject">
										<select name="subjId" id="subjId" class="subjId" required>
											<option value="">SELECT</option>
											
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="material">Material Name:</label>
									</div>
									<div class="form_field">
										<input type="text" name="materialName" id="materialName" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="material">Material :</label>
									</div>
									<div class="form_field">
										<input type="file" name="materialFile" id="materialFile" required />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewMaterial' id="addNewMaterial" class="button" value='Add Material' />
									</div>
								</div>
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=materials" class="btn btn-outline-secondary">Back</a>
                </div><?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('../layout/footer.php');
?>

<script type="text/javascript">
	(function () {
		function $(sel) { return document.querySelector(sel); }

		async function refreshSubjects() {
			var classEl = $('#classId');
			var batchEl = $('#batchId');
			var subjectWrap = document.getElementById('subject');
			if (!classEl || !batchEl || !subjectWrap) return;

			var classId = parseInt(classEl.value || '0', 10);
			var batchId = parseInt(batchEl.value || '0', 10);

			if (!classId || !batchId) {
				subjectWrap.innerHTML = '<div class="form_field"><select name="subjId" id="subjId" class="subjId" required disabled><option value="">SELECT</option></select></div>';
				return;
			}

			var url = 'subject.php?classId=' + encodeURIComponent(classId) + '&batchId=' + encodeURIComponent(batchId);
			try {
				var res = await fetch(url, { credentials: 'same-origin' });
				subjectWrap.innerHTML = await res.text();
			} catch (e) {
				subjectWrap.innerHTML = '<div class="form_field"><select name="subjId" id="subjId" class="subjId" required disabled><option value="">Failed to load</option></select></div>';
			}
		}

		document.addEventListener('DOMContentLoaded', function () {
			var classEl = $('#classId');
			var batchEl = $('#batchId');
			if (classEl) classEl.addEventListener('change', refreshSubjects);
			if (batchEl) batchEl.addEventListener('change', refreshSubjects);
			refreshSubjects();
		});
	})();
</script>

