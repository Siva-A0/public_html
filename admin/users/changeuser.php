<?php 
	
	include_once('header.php');

   require_once("../libraries/functions.class.php") ;

   $fcObj	= new DataFunctions();

   $tbUsers		= TB_USERS;

   if ( isset ( $_POST['changePassWord'] ) ){
   				
		
   }

?>
			<div id="page">
				<div id="content">
					<div class="post">
						<span class="alignCenter">
							<h4>AIML Department </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('user_leftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
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
								<div class="form_row" id="doc">
									<div class="form_label">
										<label for='username' >User Name:</label>
									</div>
									<div class="form_field">
										<input type="text" name="usrName" id="usrName" class="usrName" />
									</div>
								</div>
								<div class="form_row" id="userDet">
									
								</div>
						</div>
					</div>
					<br class="clearfix" />
				</div>
				<?php 
					include_once('sidebar.php');
				?>
				<br class="clearfix" />
			</div>
		</div>

<?php 
	include_once('footer.php');
?>

<script type="text/javascript">
	$('.document').ready(function(){
		
		$('#usrName').blur(function(){
			var usrName		= $('#usrName').val();
			$('#userDet').load('userDetails.php?userName='+usrName);
		});
	});
</script>