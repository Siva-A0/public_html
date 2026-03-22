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

include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.add-material-page {
		--mp-primary: #173d69;
		--mp-primary-deep: #13345a;
		--mp-accent: #f0b323;
		--mp-accent-deep: #d79a12;
		--mp-surface: #eef4fa;
		--mp-border: #d9e3ef;
		--mp-border-strong: #c8d6e6;
		--mp-muted: #6b819c;
		background: linear-gradient(180deg, #f3f7fb 0%, var(--mp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	#content { grid-template-columns: 1fr !important; }
	#content_left { display: none !important; }
	#content_right { max-width: 980px; }

	.material-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--mp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--mp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.material-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--mp-accent), var(--mp-accent-deep));
	}

	.material-hero h1 {
		margin: 0 0 6px;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--mp-primary-deep);
	}

	.material-hero p {
		margin: 0;
		font-size: 15px;
		color: var(--mp-muted);
	}

	.comteeMem {
		border: 1px solid var(--mp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
		background: #fff;
	}

	#addMaterial .form_label label { color: var(--mp-primary); font-weight: 700; }

	#addMaterial .form_field select,
	#addMaterial .form_field input[type="text"],
	#addMaterial .form_field input[type="file"] {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--mp-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
		font-size: 16px;
		outline: none;
	}

	#addMaterial .form_field select:focus,
	#addMaterial .form_field input[type="text"]:focus,
	#addMaterial .form_field input[type="file"]:focus {
		border-color: #87a6cb;
		background: #fff;
		box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
	}

	#addMaterial .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--mp-primary-deep), var(--mp-primary));
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(19, 52, 90, 0.24);
		color: #fff;
	}
</style>
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
