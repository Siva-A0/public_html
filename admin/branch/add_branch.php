<?php 

   require_once(__DIR__ . '/../../config.php');
    
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj		= new DataFunctions();
   
   $tbStream	= TB_STREAM;

   if ( isset ( $_POST['addNewBranch'] ) ){
   				
		$varArray['branch_code']		= $_POST['branchCode'];
		$varArray['branch_name']		= $_POST['branchName'];
		
		$addBranch	= $fcObj->addBranch ( $tbStream, $varArray );
		
		if( $addBranch ){
			
			header('Location: branch.php');
			exit;
		}else{
			$msg	= 'Sorry, Please try again';
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
						<p></p>
					</div>

					<div id='content_left' class='content_left'></div>
                    
					<div id='content_right' class='content_right'>
						<div class="add-branch-page">
						<div class="branch-add-hero">
							<h3 class="branch-add-title">Add New Branch</h3>
							<p class="branch-add-subtitle">Create branch records using unique branch code and name.</p>
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
							<form id='addBatch' action='add_branch.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for="branchcode">Branch Code :</label>
									</div>
									<div class="form_field">
										<input type="text" name="branchCode" id="branchCode" value="<?php echo htmlspecialchars($_POST['branchCode'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for="brnachname">Branch Name :</label>
									</div>
									<div class="form_field">
										<input type="text" name="branchName" id="branchName" value="<?php echo htmlspecialchars($_POST['branchName'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewBranch' id="addNewBranch" class="button" value='Add Branch' />
									</div>
								</div>
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=streams" class="btn btn-outline-secondary">Back</a>
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

