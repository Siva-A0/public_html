<?php
if (session_id() === '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbClass = TB_CLASS;
$tbSyllabus = TB_SYLLABUS;

$batchId = 0;
$onlyClassId = 0;
$onlyClassName = '';

if (isset($_SESSION['role']) && $_SESSION['role'] === 'user' && isset($_SESSION['userName'])) {
    $userData = $fcObj->userCheck(TB_USERS, $_SESSION['userName']);
    if (!empty($userData)) {
        $user = $userData[0];
        $batchId = (int)($user['batch_id'] ?? 0);
        $cls = $fcObj->getClsBySec(TB_SECTION, (string)($user['section'] ?? ''));
        if (!empty($cls)) {
            $onlyClassId = (int)($cls[0]['class_id'] ?? 0);
            $onlyClassName = (string)($cls[0]['class_name'] ?? '');
        }
    }
}

if ($onlyClassId > 0) {
    $classes = array(array('id' => $onlyClassId, 'class_name' => $onlyClassName));
} else {
    $classes = $fcObj->getClassesWOPO($tbClass);
}

$classesCnt = sizeof($classes);
$syllabus = array();

for ($i = 0; $i < $classesCnt; $i++) {
    $classId = (int)$classes[$i]['id'];
    $syllabus[$i] = $fcObj->getSyllabusForClass($tbSyllabus, $classId, $batchId);
}
?>
		 <div class="box1">
        <div class="wrapper">
          <article class="col1">
				<div id="index_cont">
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
							include_once('departleftnav.php');
						?>						
					</div>
					<div id='content_right' class='content_right'>
						<div class="comteeMem">
							<div class="committeeTitle">
								<div class='eventCandName'>
									Class Name
								</div>
								<div  class="eventCandClass">
									Syllabus
								</div>
							</div>
							<?php
								
								for($j=0; $j< $classesCnt; $j++){
									
									if( !empty( $syllabus[$j] ) ){
							?>
									<div class="usersDetHeader">
										<div class='eventCandName'>
										<?php 
											echo $classes[$j]['class_name'];
										?>
										</div>
										<div  class="eventCandClass">
											<?php
												$sylFile = trim((string)$syllabus[$j][0]['syllabus_name']);
												$sylPath = ROOT_PATH . '/public/uploads/syllabus/' . $sylFile;
												$isSafeSyllabus = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $sylFile) === 1;
											?>
											<?php if ($sylFile !== '' && $isSafeSyllabus && file_exists($sylPath)) { ?>
												<a href="<?php echo BASE_URL; ?>/public/uploads/syllabus/<?php echo rawurlencode($sylFile); ?>" target="_blank">
													Download Syllabus
												</a>
											<?php } else { ?>
												<span class="text-muted">Syllabus file unavailable</span>
											<?php } ?>
										</div>
									</div>
									
									<br class="clearfix" />
							<?php 
									}
								} 
							?>
							
						</div>
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

