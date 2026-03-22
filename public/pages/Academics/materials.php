<?php
if (session_id() === '') {
    session_start();
}

require_once(__DIR__ . '/../../../config.php');

include_once(INCLUDES_PATH . '/header.php');
require_once(LIB_PATH . '/functions.class.php');

$fcObj = new DataFunctions();

$tbClass = TB_CLASS;
$tbSubjects = TB_SUBJECTS;
$tbMaterails = TB_MATERAILS;

$batchId = 0;
$onlyClassId = 0;
$onlyClassName = '';

// If the user is logged-in, show only their batch + class materials (same across sections).
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
$subjects = array();
$materials = array();

for ($i = 0; $i < $classesCnt; $i++) {
    $classId = (int)$classes[$i]['id'];
    $subjects[$i] = $fcObj->getSubjectsForClass($tbSubjects, $classId, $batchId);

    $subjCnt = sizeof($subjects[$i]);
    for ($j = 0; $j < $subjCnt; $j++) {
        $subjId = (int)$subjects[$i][$j]['id'];
        $materials[$i][$j] = $fcObj->getMaterialsForSubj($tbMaterails, $subjId);
    }
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
							<?php
								
								for($i=0; $i< $classesCnt; $i++){
								
							?>
								<div class="materialDet">
									<div class="classHeader">
										<div class='className'>
										<?php 
											echo $classes[$i]['class_name'];
										?>
										</div>
									</div>
									<?php
								
										$subjCnt	= sizeof( $subjects[$i] );
							
										for($j=0; $j< $subjCnt; $j++){
									?>
										<div  class="subjHeader">
											<div class='subjName'>
												<?php 
													$code = trim((string)($subjects[$i][$j]['sub_code'] ?? ''));
													$name = trim((string)($subjects[$i][$j]['sub_name'] ?? ''));
													echo htmlspecialchars($name !== '' ? ($code . ' - ' . $name) : $code, ENT_QUOTES, 'UTF-8');
												?>
											</div>
											<div class='subjMaterials'>
												<?php 
													$materCnt	= sizeof($materials[$i][$j]);

													if ($materCnt <= 0) {
														echo '<div class="materailNames"><span class="text-muted">No materials uploaded.</span></div>';
													}

													for( $k=0;$k<$materCnt;$k++){
														?>
															<div class="materailNames">
																<?php
																	$materialFile = trim((string)$materials[$i][$j][$k]['mater_file']);
																	$materialPath = ROOT_PATH . '/public/uploads/materials/' . $materialFile;
																	$isValidMaterial = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $materialFile) === 1;
																?>
																<?php if ($materialFile !== '' && $isValidMaterial && file_exists($materialPath)) { ?>
																	<a href="<?php echo BASE_URL; ?>/public/uploads/materials/<?php echo rawurlencode($materialFile); ?>" target="_blank">
																	<?php
																		echo $materials[$i][$j][$k]['material_name'];
																	?>
																	</a>
																<?php } else { ?>
																	<span class="text-muted"><?php echo $materials[$i][$j][$k]['material_name']; ?> (file unavailable)</span>
																<?php } ?>
															</div>
														<?php
													}
												?>
											</div>
										</div>
										<br class="clearfix" />
									<?php
										}
									?>
									
									<br class="clearfix" />
									</div>
							<?php 
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

