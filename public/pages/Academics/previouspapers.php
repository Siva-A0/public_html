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
$tbPrevPapers = TB_PREV_PAPERS;

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
$subjects = array();
$prevPapers = array();

for ($i = 0; $i < $classesCnt; $i++) {
    $classId = (int)$classes[$i]['id'];
    $subjects[$i] = $fcObj->getSubjectsForClass($tbSubjects, $classId, $batchId);

    $subjCnt = sizeof($subjects[$i]);
    for ($j = 0; $j < $subjCnt; $j++) {
        $subjId = (int)$subjects[$i][$j]['id'];
        $prevPapers[$i][$j] = $fcObj->getPrePapersForSubj($tbPrevPapers, $subjId);
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
													$papersCnt	= sizeof($prevPapers[$i][$j]);

													if ($papersCnt <= 0) {
														echo '<div class="materailNames"><span class="text-muted">No previous papers uploaded.</span></div>';
													}

													for( $k=0;$k<$papersCnt;$k++){
														?>
															<div class="materailNames">
																<?php
																	$paperFile = trim((string)$prevPapers[$i][$j][$k]['paper_file']);
																	$paperPath = ROOT_PATH . '/public/uploads/previous_papers/' . $paperFile;
																	$isValidPaper = preg_match('/^[A-Za-z0-9 ._()\\-]+$/', $paperFile) === 1;
																?>
																<?php if ($paperFile !== '' && $isValidPaper && file_exists($paperPath)) { ?>
																	<a href="<?php echo BASE_URL; ?>/public/uploads/previous_papers/<?php echo rawurlencode($paperFile); ?>" target="_blank">
																	<?php
																		echo htmlspecialchars((string)$prevPapers[$i][$j][$k]['paper_name'], ENT_QUOTES, 'UTF-8');
																	?>
																	</a>
																<?php } else { ?>
																	<span class="text-muted"><?php echo htmlspecialchars((string)$prevPapers[$i][$j][$k]['paper_name'], ENT_QUOTES, 'UTF-8'); ?> (file unavailable)</span>
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

