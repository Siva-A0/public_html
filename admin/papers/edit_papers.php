<?php
require_once(__DIR__ . '/../../config.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbBatch     = TB_BATCH;
$tbClass     = TB_CLASS;
$tbSubject   = TB_SUBJECTS;
$tbPrevPapers = TB_PREV_PAPERS;

$batches = $fcObj->getBatches($tbBatch);
$batchesCnt = sizeof($batches);

$classes = $fcObj->getClassesWOPO($tbClass);
$classesCnt = sizeof($classes);

$paperId = 0;
if (isset($_GET['paper']) && $_GET['paper'] !== '') {
	$paperId = (int)$_GET['paper'];
} elseif (isset($_POST['paperId']) && $_POST['paperId'] !== '') {
	$paperId = (int)$_POST['paperId'];
}
if ($paperId <= 0) {
	header('Location: previouspapers.php');
	exit;
}

$paperDet = $fcObj->getPaperById($tbPrevPapers, $paperId);
if (empty($paperDet)) {
	header('Location: previouspapers.php');
	exit;
}

$subjectDet = $fcObj->getSubjectById($tbSubject, (int)$paperDet[0]['subject_id']);
$batchId = !empty($subjectDet) ? (int)($subjectDet[0]['batch_id'] ?? 0) : 0;

if (isset($_POST['editPaper'])) {
	$batchId = (int)($_POST['batchId'] ?? 0);

	$varArray = array();
	$varArray['class_id'] = (int)($_POST['classId'] ?? 0);
	$varArray['subj_id'] = (int)($_POST['subjId'] ?? 0);
	$varArray['paper_name'] = trim((string)($_POST['paperName'] ?? ''));
	$varArray['paper_id'] = (int)($_POST['paperId'] ?? 0);

	if ($batchId <= 0 || $varArray['class_id'] <= 0 || $varArray['subj_id'] <= 0 || $varArray['paper_name'] === '') {
		$msg = 'Please select Batch, Class, Subject and enter Paper Name.';
	} else {
		$uploadDir = ROOT_PATH . '/public/uploads/previous_papers/';
		if (!is_dir($uploadDir)) {
			@mkdir($uploadDir, 0777, true);
		}

		$fileName = (string)($_POST['prePaperFile'] ?? '');
		if (isset($_FILES['paperFile']['name']) && $_FILES['paperFile']['name'] !== '' && isset($_FILES['paperFile']['tmp_name'])) {
			$newName = (string)$_FILES['paperFile']['name'];
			if (move_uploaded_file($_FILES['paperFile']['tmp_name'], $uploadDir . $newName)) {
				$prevPath = $uploadDir . $fileName;
				if ($fileName !== '' && file_exists($prevPath)) {
					@unlink($prevPath);
				}
				$fileName = $newName;
			}
		}

		$varArray['paper_file_name'] = $fileName;

		$editPaper = $fcObj->editPaper($tbPrevPapers, $varArray);
		if ($editPaper) {
			header('Location: previouspapers.php?batchId=' . $batchId);
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
						<div class="edit-papers-page">
						<div class="edit-papers-hero">
							<h3 class="edit-papers-title">Edit Previous Paper</h3>
							<p class="edit-papers-subtitle">Update paper details and file attachments without leaving the branded admin flow.</p>
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
							<form id='editMaterial' action='edit_papers.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
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
													if( $classes[$i]['id'] == $paperDet[0]['class_id'] ){
													?>
														<option value="<?php echo $classes[$i]['id']; ?>"><?php echo $classes[$i]['class_name']; ?></option>
													<?php
													}
												}
											?>
											<?php
												for($i=0;$i<$classesCnt;$i++){
													if( $classes[$i]['id'] != $paperDet[0]['class_id'] ){
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
											<option value="<?php echo (int)$paperDet[0]['subject_id']; ?>"><?php echo htmlspecialchars((string)$paperDet[0]['sub_code'], ENT_QUOTES, 'UTF-8'); ?></option>
										</select>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="paper">Paper Name:</label>
									</div>
									<div class="form_field">
										<input type="text" name="paperName" id="paperName" value="<?php echo $paperDet[0]['paper_name']; ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="material">Paper :</label>
									</div>
									<div class="form_field">
										<input type="file" name="paperFile" id="paperFile" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type="hidden" name="prePaperFile" id="prePaperFile" value="<?php echo $paperDet[0]['paper_file']; ?>"/>
										<input type="hidden" name="paperId" id="paperId" value="<?php echo $paperId; ?>"/>
										<input type='submit' name='editPaper' id="editPaper" class="button" value='Edit Paper' />
									</div>
								</div>
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=previous_papers" class="btn btn-outline-secondary">Back</a>
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


