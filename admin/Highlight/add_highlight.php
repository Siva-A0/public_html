
<?php require_once(__DIR__ . '/../../config.php');?>
<?php 
 

// require_once("libraries/functions.class.php");
require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();

   
   $tbHighLights		= TB_HIGHLIGHTS;

   if ( isset ( $_POST['addNewHighLight'] ) ){

		$typedType = strtoupper(trim((string)($_POST['type'] ?? '')));
		if ($typedType === 'AIML') {
			$varArray['typeId'] = AIML;
		} elseif ($typedType === 'DEPARTMENT') {
			$varArray['typeId'] = DEPARTMENT;
		} else {
			$msg = 'Please enter Type as AIML or DEPARTMENT.';
		}

		$varArray['highLight']	= $_POST['highLightName'];

		if (!isset($msg)) {
			$addHightLight	= $fcObj->addHighLight ( $tbHighLights, $varArray );

			if( $addHightLight ){

				header('Location: highlights.php');
				exit;
			}else{
				$msg	= 'Sorry, Please try again';
			}
		}
   }

	if (!isset($adminExtraStyles) || !is_array($adminExtraStyles)) {
    $adminExtraStyles = array();
}
$adminExtraStyles[] = BASE_URL . '/public/assets/css/admin/admin_misc_pages.css';

include_once('../layout/main_header.php');
	include_once('../layout/core_forms_style.php');

?>

<div id="page">
				<div id="content" class="single-panel-layout">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Department </h4>
						</span>
						<p>
							
						</p>
					</div>
					<!-- <div id='content_left' class='content_left'>
						<?php 
							include_once('../layout/other_leftnav.php');
						?>						
					</div> -->
					<div id='content_right' class='content_right'>
						<div class="add-highlight-page">
						<div class="highlight-add-hero">
							<h3 class="highlight-add-title">Add New Highlight</h3>
							<p class="highlight-add-subtitle">Create and publish department highlight messages.</p>
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
							<form id='addHighLight' class="core-form" action='add_highlight.php' method='POST' accept-charset='UTF-8' enctype="multipart/form-data">
								<div class="form_row">
									<div class="form_label">
										<label for='type' >Type:</label>
									</div>
									<div class="form_field">
										<input type="text" name="type" id="type" class="typeId" list="highlightTypeSuggestions" placeholder="Type AIML or DEPARTMENT" required />
										<datalist id="highlightTypeSuggestions">
											<option value="AIML"></option>
											<option value="DEPARTMENT"></option>
										</datalist>
									</div>
								</div>
								<div class="form_row">
									<div class="form_label">
										<label for='highLight' >High Light :</label>
									</div>
									<div class="form_field" id="highLight">
										<textarea name="highLightName" id="highLightName" class="highLightName" ></textarea>
									</div>
								</div>
								<div class="form_row form_actions">
									<div class="form_label">
										
									</div>
									<div class="form_field">
										<input type='submit' name='addNewHighLight' id="addNewHighLight" class="button" value='Add High Light' />
									</div>
								</div>
							</form>
						</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				                <div class="mt-3">
                    <a href="../settings/department_option.php?option=highlights" class="btn btn-outline-secondary">Back</a>
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
		
	});
</script>

