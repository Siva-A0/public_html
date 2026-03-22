
<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
include_once('../layout/main_header.php');
include_once('../layout/core_forms_style.php');

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');
   $fcObj	= new DataFunctions();

?>
<style type="text/css">
    .committee-form-shell,
    .committee-page-wrap {
        --committee-primary: #173d69;
        --committee-primary-deep: #13345a;
        --committee-accent: #f0b323;
        --committee-accent-deep: #d79a12;
        --committee-accent-soft: #fff5da;
        --committee-surface: #eef4fa;
        --committee-card: #ffffff;
        --committee-border: #d9e3ef;
        --committee-border-strong: #c8d6e6;
        --committee-text: #163a61;
        --committee-muted: #6b819c;
    }

    .committee-form-shell {
        max-width: none;
        margin: 0;
        width: 100%;
        background: linear-gradient(180deg, #f3f7fb 0%, var(--committee-surface) 100%);
        border-radius: 24px;
        padding: 24px;
    }

    .committee-add-hero {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--committee-border);
        border-radius: 22px;
        padding: 22px 24px;
        background: linear-gradient(135deg, #f9fbfe 0%, var(--committee-surface) 100%);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
        margin-bottom: 16px;
    }

    .committee-add-hero::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 6px;
        background: linear-gradient(180deg, var(--committee-accent), var(--committee-accent-deep));
    }

    .committee-add-title {
        margin: 0;
        font-size: 30px;
        font-weight: 800;
        color: var(--committee-primary-deep);
        letter-spacing: -0.5px;
    }

    .committee-add-subtitle {
        margin: 8px 0 0;
        color: var(--committee-muted);
        font-size: 15px;
    }

    /* Match Core Settings form/card style on committee assignment page */
    #content_right .login,
    #content_right .comteeMem {
        background: var(--committee-card);
        padding: 24px;
        border: 1px solid var(--committee-border);
        border-radius: 18px;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.07);
    }

    #content_left {
        display: none;
    }

    #content {
        grid-template-columns: 1fr;
        justify-content: stretch;
        gap: 0;
    }

    #page {
        max-width: none;
    }

    #content_right {
        width: 100%;
    }

    #addcommitteemem .form_row {
        margin-bottom: 16px;
    }

    #addcommitteemem .form_label {
        margin-bottom: 6px;
    }

    #addcommitteemem .form_label label {
        font-size: 17px;
        font-weight: 700;
        color: var(--committee-text);
    }

    #addcommitteemem .form_field select,
    #addcommitteemem .form_field input[type="text"],
    #addcommitteemem .form_field textarea {
        width: 100%;
        min-height: 52px;
        border: 1px solid var(--committee-border-strong);
        border-radius: 12px;
        padding: 11px 14px;
        background: #f7f9fc;
        font-size: 16px;
        outline: none;
    }

    #addcommitteemem .form_field textarea {
        min-height: 150px;
        resize: vertical;
    }

    #addcommitteemem .form_field select:focus,
    #addcommitteemem .form_field input[type="text"]:focus,
    #addcommitteemem .form_field textarea:focus {
        border-color: #87a6cb;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
    }

    #addcommitteemem .button,
    .comteeMem .button {
        border: 0;
        border-radius: 12px;
        padding: 10px 20px;
        background: linear-gradient(135deg, var(--committee-primary-deep), var(--committee-primary));
        color: #fff;
        font-weight: 700;
        box-shadow: 0 8px 16px rgba(19, 52, 90, 0.22);
    }

    #addcommitteemem .button:hover,
    .comteeMem .button:hover {
        filter: brightness(1.06);
    }

    #addcommitteemem .member-photo-preview {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        border: 1px solid var(--committee-border);
        background: #f8fafc;
        object-fit: cover;
    }

    .member-upload-wrap {
        border: 1px dashed var(--committee-border-strong);
        border-radius: 12px;
        background: #f8fbff;
        padding: 12px;
    }

    .upload-hint {
        margin-top: 8px;
        color: var(--committee-muted);
        font-size: 13px;
    }

    .committee-action-bar {
        margin-top: 6px;
        border: 1px solid var(--committee-border);
        border-radius: 14px;
        background: #f8fbff;
        padding: 12px;
    }
</style>
<?php
   
   if( isset( $_POST['addCmtMember'] ) ) {
		
		$tbComtCtg = TB_COMT_CATEG;
		$typedCategory = isset($_POST['cmtCatName']) ? trim((string)$_POST['cmtCatName']) : '';
		$categoryId = $fcObj->getOrCreateCommitteeCategoryId($tbComtCtg, $typedCategory);
		if ($categoryId <= 0 && isset($_POST['cmtCat'])) {
			$categoryId = (int)$_POST['cmtCat'];
		}

		$varArray['committee_cat_id']	= $categoryId;
		$varArray['user_id']			= isset($_POST['userId']) ? intval($_POST['userId']) : 0;
		$varArray['member_name']		= isset($_POST['member_name']) ? trim((string)$_POST['member_name']) : '';
		$varArray['member_about']		= isset($_POST['member_about']) ? trim((string)$_POST['member_about']) : '';
		$varArray['member_image']		= isset($_POST['member_image']) ? trim((string)$_POST['member_image']) : '';

		if ($varArray['committee_cat_id'] <= 0) {
			$addCmtMem = 'Please enter a valid committee category.';
		} else {

		if (isset($_FILES['member_photo']) && $_FILES['member_photo']['error'] === 0) {
			$uploadName = basename((string)$_FILES['member_photo']['name']);
			$uploadExt = strtolower(pathinfo($uploadName, PATHINFO_EXTENSION));
			$allowedExt = array('jpg', 'jpeg', 'png', 'gif', 'webp');

			if (in_array($uploadExt, $allowedExt)) {
				$newFileName = 'committee_' . time() . '_' . mt_rand(1000, 9999) . '.' . $uploadExt;
				$uploadPath = '../../public/assets/images/users/' . $newFileName;
				if (move_uploaded_file($_FILES['member_photo']['tmp_name'], $uploadPath)) {
					$varArray['member_image'] = $newFileName;
				}
			}
		}
		
		$tbCmt	= TB_COMMITTEE;
	   
		$addCmtMem  = $fcObj->addCommitteeMember($tbCmt,$varArray);
		}
		
	   $tbComtCtg = TB_COMT_CATEG;
	   $tbComt	  = TB_COMMITTEE;
	   
	   $ComtCateg	= $fcObj->getComiteCatg($tbComtCtg);
	   $categoryCnt		= sizeof($ComtCateg);
	   
	   for($i=0; $i<$categoryCnt;$i++){
			
			$categoryId			= $ComtCateg[$i]['id'];
			
			$CmtMemDet[$i]	= $fcObj->getCmtMembers($tbComt,$categoryId);
		}

		?>
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="comteeMemRow">
							<div class="usersDetHeader">
		<?
								echo $addCmtMem;
		?>
							</div>
						</div>
						<div class="comteeMem">
							<div class="comteeMemRow">
							<?php
								
								for($j=0; $j< $categoryCnt; $j++){
								
									if(!empty($CmtMemDet[$j])){
										$summaryName = trim((string)($CmtMemDet[$j][0]['member_name'] ?? ''));
										$summaryAbout = (string)($CmtMemDet[$j][0]['member_about'] ?? '');
										$summaryImage = trim((string)($CmtMemDet[$j][0]['member_image'] ?? ''));
										$summaryImage = $summaryImage !== '' ? $summaryImage : 'default.png';
							?>
										<div class="comteeMemDetails">
											<div class="wiseCmtMemImage"><img src="<?php echo BASE_URL; ?>/public/assets/images/users/<?php echo rawurlencode($summaryImage);?>" alt="<?php echo htmlspecialchars($summaryName);?>" title="<?php echo htmlspecialchars($summaryName);?>" width="100px" height="100px" /></div>
											<div class="comiteMemName"><?php echo htmlspecialchars($summaryName !== '' ? $summaryName : 'Member');?></div>
											<div class="comiteMemCls"><?php echo htmlspecialchars($summaryAbout);?></div>
											<div class="comiteCategory"><?php echo $ComtCateg[$j]['category_name'];?></div>
											<br class="clearfix" />
										</div>
							<?php 
									}
								} 
							?>
							</div>
							<div class="comteeMemRow">
								<div class="comteeMemDetails">
									<a href="addmem.php">
										<input type="submit" class="button" name="addmember" value="Add Committee Member" />
									</a>
								</div>
							</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				<?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		<?php
			include_once('../layout/footer.php');
   }else{
	?>
			
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'></div>
					<div id='content_right' class='content_right'>
						<div class="committee-form-shell">
						<div class="committee-add-hero">
							<h3 class="committee-add-title">Add Committee Member</h3>
							<p class="committee-add-subtitle">Enter a category manually and create a member profile with optional photo.</p>
						</div>
						<div class="login">
							<form id='addcommitteemem' action='addmem.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for='cmtCatName' >Committee Category:</label>
									</div>
									<div class="form_field">
										<input type="text" name="cmtCatName" id="cmtCatName" value="" placeholder="Type category name (e.g., President, Secretary)" required />
										<input type="hidden" name="cmtCat" id="cmtCat" value="" />
									</div>
								</div>
								<div class="form_row">
									<input type="hidden" name="userId" id="userId" value="0" />
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='memberName'>Member Name:</label>
									</div>
									<div class="form_field">
										<input type="text" id="memberName" name="member_name" value="" placeholder="Member name" required />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='memberAbout' >About:</label>
									</div>
									<div class="form_field">
										<textarea id="memberAbout" name="member_about" rows="3" placeholder="Member details will appear here."></textarea>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label>Profile Photo:</label>
									</div>
									<div class="form_field">
										<div class="member-upload-wrap">
											<input type="hidden" id="memberImage" name="member_image" value="" />
											<input type="file" id="memberPhotoUpload" name="member_photo" accept=".jpg,.jpeg,.png,.gif,.webp" />
											<img id="memberPhoto" class="member-photo-preview" src="" alt="Profile preview" style="display:none;" />
											<div id="memberPhotoPlaceholder">No profile photo selected.</div>
											<div class="upload-hint">Allowed: JPG, PNG, GIF, WEBP</div>
										</div>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<div class="committee-action-bar">
											<input type='submit' name='addCmtMember' class="button" value='Assign' />
										</div>
									</div>
								</div>
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				<?php 
					include_once('../layout/sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>


<script type="text/javascript" language="javascript">
	
	$(document).ready(function() {
		$('#memberPhotoUpload').on('change', function(){
			if (this.files && this.files[0]) {
				var fileUrl = URL.createObjectURL(this.files[0]);
				$('#memberPhoto').attr('src', fileUrl).show();
				$('#memberPhotoPlaceholder').hide();
			} else {
				$('#memberPhoto').hide().attr('src', '');
				$('#memberPhotoPlaceholder').show();
			}
		});
	});
</script>

<?php 
	include_once('../layout/footer.php');
   }
?>
