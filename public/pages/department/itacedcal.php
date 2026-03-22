<?php 
	require_once(__DIR__ . '/../../../config.php');

    include_once(INCLUDES_PATH . '/header.php');
    require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();


?>
			<div class="box1">
        <div class="wrapper">
          <article class="col1">
			<div id="index_cont">
					<div class="post">
						<span class="alignCenter">
							<h4> IT Department Acedamic Calendar </h4>
						</span>
						<p>
							
						</p>
					</div>
					
					<div id='content_right' class='content_right'>
					 	<iframe src="http://docs.google.com/gview?url=http://nirulawise.com/doc/acedamiccalender.doc&embedded=true" style="width:600px; height:500px;" frameborder="0"></iframe>
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

