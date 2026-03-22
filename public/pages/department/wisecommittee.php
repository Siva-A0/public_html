<?php 
require_once(__DIR__ . '/../../../config.php');

include_once(INCLUDES_PATH . '/header.php');

?>
	<div class="box1">
        <div class="wrapper">
          <article class="col1">
				<div id="index_cont">
				<div class="post">
						<span class="alignCenter">
							<h4>WISE Association </h4>
						</span>
						<p>
							
						</p>
					</div>
					<div id='content_left' class='content_left'>
						<?php 
							include_once('wise_leftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						
					</div>
					<br class="clearfix" />
				</div>
					</article>
					<article class="col2 pad_left2">
					<?php 
						include_once('sidebar.php');
					?>
					</article>
</div>
</div>

<?php include_once(INCLUDES_PATH . '/footer.php'); ?>

