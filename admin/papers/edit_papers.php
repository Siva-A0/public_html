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

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.edit-papers-page {
		--pp-primary: #173d69;
		--pp-primary-deep: #13345a;
		--pp-accent: #f0b323;
		--pp-accent-deep: #d79a12;
		--pp-surface: #eef4fa;
		--pp-border: #d9e3ef;
		--pp-border-strong: #c8d6e6;
		--pp-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--pp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	#content_left {
		display: none;
	}

	#content {
		grid-template-columns: minmax(320px, 920px);
		justify-content: center;
		gap: 0;
	}

	.edit-papers-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--pp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--pp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.edit-papers-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--pp-accent), var(--pp-accent-deep));
	}

	.edit-papers-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--pp-primary-deep);
	}

	.edit-papers-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--pp-muted);
	}

	#content_right .comteeMem {
		max-width: 920px;
		border: 1px solid var(--pp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}

	#editMaterial .form_label label {
		font-size: 16px;
		font-weight: 700;
		color: var(--pp-primary);
	}

	#editMaterial .form_field input[type="text"],
	#editMaterial .form_field input[type="file"],
	#editMaterial .form_field select {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--pp-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
		font-size: 16px;
		outline: none;
	}

	#editMaterial .form_field input[type="file"] {
		padding: 0;
		line-height: 1.2;
	}

	#editMaterial .form_field input[type="file"]::file-selector-button,
	#editMaterial .form_field input[type="file"]::-webkit-file-upload-button {
		height: 50px;
		margin: 0;
		border: 0;
		border-right: 1px solid var(--pp-border-strong);
		padding: 0 16px;
		background: #ffffff;
		color: var(--pp-primary);
		font-weight: 600;
	}

	#editMaterial .form_field input[type="text"]:focus,
	#editMaterial .form_field input[type="file"]:focus,
	#editMaterial .form_field select:focus {
		border-color: #87a6cb;
		background: #ffffff;
		box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
	}

	#editMaterial .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--pp-primary-deep), var(--pp-primary));
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}

	@media (max-width: 768px) {
		.edit-papers-title {
			font-size: 26px;
		}
	}
</style>
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

