<?php require_once(__DIR__ . '/../../config.php');

require_once(LIB_PATH . '/functions.class.php');

	
   $fcObj	= new DataFunctions();
   
   $tbClass		= TB_CLASS;

   if ( isset ( $_POST['addNewClass'] ) ){
   				
		$varArray['class_code']		= $_POST['classCode'];
		$varArray['class_name']		= $_POST['className'];
		
		$addClass	= $fcObj->addClass ( $tbClass, $varArray );
		
		if( $addClass ){
			
			header('Location: classes.php');
			exit;
		}else{
			$msg	= 'Sorry, Please try again';
		}
   }

	include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');
?>
<style type="text/css">
	.class-add-page {
		--cp-primary: #173d69;
		--cp-primary-deep: #13345a;
		--cp-accent: #f0b323;
		--cp-accent-deep: #d79a12;
		--cp-surface: #eef4fa;
		--cp-border: #d9e3ef;
		--cp-border-strong: #c8d6e6;
		--cp-muted: #6b819c;
	}

	#content_left {
		display: none;
	}

	#content {
		grid-template-columns: minmax(320px, 840px);
		justify-content: center;
		gap: 0;
	}

	.class-add-page {
		background: linear-gradient(180deg, #f3f7fb 0%, var(--cp-surface) 100%);
		border-radius: 24px;
		padding: 24px;
	}

	.class-add-hero {
		position: relative;
		overflow: hidden;
		border: 1px solid var(--cp-border);
		border-radius: 22px;
		padding: 22px 24px;
		background: linear-gradient(135deg, #f9fbfe 0%, var(--cp-surface) 100%);
		box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
		margin-bottom: 16px;
	}

	.class-add-hero::before {
		content: "";
		position: absolute;
		inset: 0 auto 0 0;
		width: 6px;
		background: linear-gradient(180deg, var(--cp-accent), var(--cp-accent-deep));
	}

	.class-add-title {
		margin: 0;
		font-size: 32px;
		font-weight: 800;
		letter-spacing: -0.6px;
		color: var(--cp-primary-deep);
	}

	.class-add-subtitle {
		margin: 8px 0 0;
		font-size: 15px;
		color: var(--cp-muted);
	}

	#content_right .comteeMem {
		max-width: 840px;
		border: 1px solid var(--cp-border);
		border-radius: 18px;
		box-shadow: 0 10px 22px rgba(15, 23, 42, 0.06);
		padding: 24px;
	}

	#addClass .form_label label {
		font-size: 16px;
		font-weight: 700;
		color: #173d69;
	}

	#addClass .form_field input[type="text"] {
		width: 100%;
		min-height: 52px;
		border: 1px solid var(--cp-border-strong);
		border-radius: 12px;
		padding: 11px 14px;
		background: #f7f9fc;
		font-size: 16px;
		outline: none;
	}

	#addClass .form_field input[type="text"]:focus {
		border-color: #87a6cb;
		background: #ffffff;
		box-shadow: 0 0 0 4px rgba(23, 61, 105, 0.12);
	}

	#addClass .button {
		border: 0;
		border-radius: 12px;
		padding: 11px 20px;
		background: linear-gradient(135deg, var(--cp-primary-deep), var(--cp-primary));
		font-weight: 700;
		box-shadow: 0 10px 20px rgba(16, 42, 72, 0.24);
	}

	#addClass .button:hover {
		filter: brightness(1.06);
	}
</style>
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter"></span>
						<p></p>
					</div>


					<div id='content_left' class='content_left'></div>

                    
					<div id='content_right' class='content_right'>
						<div class="class-add-page">
						<div class="class-add-hero">
							<h3 class="class-add-title">Add New Class</h3>
							<p class="class-add-subtitle">Create class records with a unique class code and name.</p>
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
							<form id='addClass' action='add_class.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for="class">Class Code :</label>
									</div>
									<div class="form_field">
										<input type="text" name="classCode" id="classCode" value="<?php echo htmlspecialchars($_POST['classCode'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="class">Class Name :</label>
									</div>
									<div class="form_field">
										<input type="text" name="className" id="className" value="<?php echo htmlspecialchars($_POST['className'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewClass' id="addNewClass" class="button" value='Add Class' />
									</div>
								</div>
							</form>
						</div>
					</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=classes" class="btn btn-outline-secondary">Back</a>
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
	$('.document').ready(function(){
		$('#delete').click(function(){
			var conf	= confirm('Do You Want To Continue To Delete');
			if( conf ){
				
			}else{
				return false;
			}
		});
	});
</script>
