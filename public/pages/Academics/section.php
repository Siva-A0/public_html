<?php
	
   require_once(__DIR__ . '/../../../config.php');

    require_once(LIB_PATH . '/functions.class.php');

   $fcObj	= new DataFunctions();
   
   if(isset($_GET['classId'])){
   		
		$classId	= (int)$_GET['classId'];
		$batchId	= isset($_GET['batchId']) ? (int)$_GET['batchId'] : 0;
		if ($batchId <= 0 && isset($_SESSION['userName'])) {
			$userData = $fcObj->userCheck(TB_USERS, $_SESSION['userName']);
			if (!empty($userData)) {
				$batchId = (int)($userData[0]['batch_id'] ?? 0);
			}
		}
		
		if( $classId == '' || $classId == NULL ){
			$sections	= array();
		}else{
			$tbSection	= TB_SECTION;
			
			$sections	= $fcObj->getSections($tbSection, $classId, $batchId);
		}
		
		?>
				<select name="sectionId" id="sectionId" class="sectionId form-select modern-input" required>
					<option value="">SELECT</option>
					<?php
						$sectionCnt	= sizeof( $sections );

						if ($sectionCnt === 0) {
					?>
							<option value="" disabled>No sections available</option>
					<?php
						}

						for( $i=0; $i< $sectionCnt ; $i++){
							$label = trim((string)$sections[$i]['section_name']);
							if ($label === '') {
								$label = (string)$sections[$i]['section_code'];
							}
					?>
							<option value="<?php echo (int)$sections[$i]['id']; ?>"><?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?></option>
					<?php
						}
					?>
				</select>
		<?php
   }
?>

