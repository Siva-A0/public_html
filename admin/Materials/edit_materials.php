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

$materialId = 0;
if (isset($_GET['material']) && $_GET['material'] !== '') {
	$materialId = (int)$_GET['material'];
} elseif (isset($_POST['materialId']) && $_POST['materialId'] !== '') {
	$materialId = (int)$_POST['materialId'];
}
if ($materialId <= 0) {
	header('Location: materials.php');
	exit;
}

$materialDet = $fcObj->getMaterialById($tbMaterial, $materialId);
if (empty($materialDet)) {
	header('Location: materials.php');
	exit;
}

$subjectDet = $fcObj->getSubjectById($tbSubject, (int)$materialDet[0]['subject_id']);
$batchId = !empty($subjectDet) ? (int)($subjectDet[0]['batch_id'] ?? 0) : 0;

if (isset($_POST['editMaterial'])) {
	$batchId = (int)($_POST['batchId'] ?? 0);

	$varArray = array();
	$varArray['class_id'] = (int)($_POST['classId'] ?? 0);
	$varArray['subj_id'] = (int)($_POST['subjId'] ?? 0);
	$varArray['material_name'] = trim((string)($_POST['materialName'] ?? ''));
	$varArray['material_id'] = (int)($_POST['materialId'] ?? 0);

	if ($batchId <= 0 || $varArray['class_id'] <= 0 || $varArray['subj_id'] <= 0 || $varArray['material_name'] === '') {
		$msg = 'Please select Batch, Class, Subject and enter Material Name.';
	} else {
		$uploadDir = ROOT_PATH . '/public/uploads/materials/';
		if (!is_dir($uploadDir)) {
			@mkdir($uploadDir, 0777, true);
		}

		$fileName = (string)($_POST['preMaterialFile'] ?? '');
		if (isset($_FILES['materialFile']['name']) && $_FILES['materialFile']['name'] !== '' && isset($_FILES['materialFile']['tmp_name'])) {
			$newName = (string)$_FILES['materialFile']['name'];
			if (move_uploaded_file($_FILES['materialFile']['tmp_name'], $uploadDir . $newName)) {
				$prevPath = $uploadDir . $fileName;
				if ($fileName !== '' && file_exists($prevPath)) {
					@unlink($prevPath);
				}
				$fileName = $newName;
			}
		}

		$varArray['material_file_name'] = $fileName;

		$editMaterial = $fcObj->editMaterial($tbMaterial, $varArray);
		if ($editMaterial) {
			header('Location: materials.php?batchId=' . $batchId);
			exit;
		}

		$msg = 'Sorry, Please try again';
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
						<div class="edit-material-page">
						<div class="material-edit-hero">
							<h1>Edit Material</h1>
							<p>Update the material mapping, name, or uploaded file inside the same academic flow.</p>
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
							<form id='editMaterial' action='edit_materials.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
										<select name="classId" id="classId" class="classId">
											<?php
												for($i=0;$i<$classesCnt;$i++){
													if( $classes[$i]['id'] == $materialDet[0]['class_id'] ){
													?>
														<option value="<?php echo $classes[$i]['id']; ?>"><?php echo $classes[$i]['class_name']; ?></option>
													<?php
													}
												}
											?>
											<?php
												for($i=0;$i<$classesCnt;$i++){
													if( $classes[$i]['id'] != $materialDet[0]['class_id'] ){
													?>
														<option value="<?php echo $classes[$i]['id']; ?>"><?php echo $classes[$i]['class_name']; ?></option>
													<?php
													}
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
											<option value="<?php echo (int)$materialDet[0]['subject_id']; ?>"><?php echo htmlspecialchars((string)$materialDet[0]['sub_code'], ENT_QUOTES, 'UTF-8'); ?></option>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="material">Material Name:</label>
									</div>
									<div class="form_field">
										<input type="text" name="materialName" id="materialName" value="<?php echo $materialDet[0]['material_name']; ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="material">Material :</label>
									</div>
									<div class="form_field">
										<input type="file" name="materialFile" id="materialFile" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type="hidden" name="preMaterialFile" id="preMaterialFile" value="<?php echo $materialDet[0]['mater_file']; ?>"/>
										<input type="hidden" name="materialId" id="materialId" value="<?php echo $materialId; ?>"/>
										<input type='submit' name='editMaterial' id="editMaterial" class="button" value='Edit Material' />
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
				return;
			}

			var current = document.getElementById('subjId');
			var currentVal = current ? current.value : '';

			var url = 'subject.php?classId=' + encodeURIComponent(classId) + '&batchId=' + encodeURIComponent(batchId);
			try {
				var res = await fetch(url, { credentials: 'same-origin' });
				subjectWrap.innerHTML = await res.text();
				var next = document.getElementById('subjId');
				if (next && currentVal) {
					next.value = currentVal;
				}
			} catch (e) {
				// Keep existing dropdown if reload fails.
			}
		}

		document.addEventListener('DOMContentLoaded', function () {
			var classEl = $('#classId');
			var batchEl = $('#batchId');
			if (classEl) classEl.addEventListener('change', refreshSubjects);
			if (batchEl) batchEl.addEventListener('change', refreshSubjects);
		});
	})();
</script>

<?php 
	include_once('../layout/footer.php');
?>


