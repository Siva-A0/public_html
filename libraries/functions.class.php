<?php
	
	include_once("mysql.class.php");
	require_once("security.php");

 	class DataFunctions {

 		public $dbObj;
		private $sectionBatchSupport = null;
		private $lastError = '';
 		public function __construct(){
 			$this->dbObj = new DataBasePDO();
			try { $this->ensureModernAuthSchema(); } catch (Exception $e) {}
			try { $this->ensureStaffAuthSchema(); } catch (Exception $e) {}
			try { $this->ensureGallerySchema(); } catch (Exception $e) {}
			try { $this->ensureSectionBatchSchema(); } catch (Exception $e) {}
			try { $this->ensureAcademicBatchSchema(); } catch (Exception $e) {}
			try { $this->ensureUsersAlumniFallbackSchema(); } catch (Exception $e) {}
			try { $this->ensureUsersAcademicLifecycleSchema(); } catch (Exception $e) {}
			try { $this->ensureUsersRoleSchema(); } catch (Exception $e) {}
 		}

	private function assertSafeIdentifier($identifier){
		$identifier = trim((string)$identifier);
		if ($identifier === '' || !preg_match('/^[A-Za-z0-9_]+$/', $identifier)) {
			throw new InvalidArgumentException('Unsafe identifier supplied.');
		}
		return $identifier;
	}

	public function verifyPassword($plainTextPassword, $storedHash){
		return app_verify_password($plainTextPassword, $storedHash);
	}

	public function hashPassword($plainTextPassword){
		return app_hash_password($plainTextPassword);
	}

	public function passwordNeedsRehash($storedHash){
		return app_password_needs_rehash($storedHash);
	}

	private function setLastError($message){
		$this->lastError = trim((string)$message);
	}

	public function getLastError(){
		return (string)$this->lastError;
	}

	private function ensureUsersAlumniFallbackSchema(){
		$usersTable = $this->assertSafeIdentifier(TB_USERS);

		if (!$this->tableHasColumn($usersTable, 'is_alumni')) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$usersTable.'` ADD COLUMN `is_alumni` tinyint(1) NOT NULL DEFAULT 0');
		}
		if (!$this->tableHasColumn($usersTable, 'alumni_original_section_id')) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$usersTable.'` ADD COLUMN `alumni_original_section_id` int(11) NULL DEFAULT NULL');
		}
		if (!$this->tableHasColumn($usersTable, 'alumni_original_section_label')) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$usersTable.'` ADD COLUMN `alumni_original_section_label` varchar(20) NULL DEFAULT NULL');
		}
		if (!$this->tableHasColumn($usersTable, 'alumni_graduated_on')) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$usersTable.'` ADD COLUMN `alumni_graduated_on` date NULL DEFAULT NULL');
		}

		$alumniIdx = $this->dbObj->getAllResults('SHOW INDEX FROM `'.$usersTable.'` WHERE Key_name = "idx_users_is_alumni"');
		if (empty($alumniIdx)) {
			$this->dbObj->executeQuery('CREATE INDEX `idx_users_is_alumni` ON `'.$usersTable.'` (`is_alumni`)');
		}
	}

	private function ensureUsersAcademicLifecycleSchema(){
		$usersTable = $this->assertSafeIdentifier(TB_USERS);

		if (!$this->tableHasColumn($usersTable, 'user_type')) {
			$this->dbObj->executeQuery("ALTER TABLE `".$usersTable."` ADD COLUMN `user_type` ENUM('student','alumni') NOT NULL DEFAULT 'student'");
		}
		if (!$this->tableHasColumn($usersTable, 'passout_year')) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$usersTable.'` ADD COLUMN `passout_year` YEAR NULL DEFAULT NULL');
		}

		try {
			$sectionColumn = $this->dbObj->getOnePrepared(
				"SELECT IS_NULLABLE, COLUMN_TYPE
				 FROM information_schema.COLUMNS
				 WHERE TABLE_SCHEMA = DATABASE()
				   AND TABLE_NAME = :table_name
				   AND COLUMN_NAME = 'section'
				 LIMIT 1",
				array(':table_name' => $usersTable)
			);

			if (!empty($sectionColumn)) {
				$columnType = trim((string)($sectionColumn['COLUMN_TYPE'] ?? 'varchar(10)'));
				$isNullable = strtoupper((string)($sectionColumn['IS_NULLABLE'] ?? 'NO'));
				if ($isNullable !== 'YES') {
					$this->dbObj->executeQuery("ALTER TABLE `".$usersTable."` MODIFY `section` ".$columnType." NULL DEFAULT NULL COMMENT 'section'");
				}
			}
		} catch (Exception $e) {}

		$userTypeIdx = $this->dbObj->getAllResults('SHOW INDEX FROM `'.$usersTable.'` WHERE Key_name = "idx_users_user_type"');
		if (empty($userTypeIdx)) {
			$this->dbObj->executeQuery('CREATE INDEX `idx_users_user_type` ON `'.$usersTable.'` (`user_type`)');
		}
		$passoutIdx = $this->dbObj->getAllResults('SHOW INDEX FROM `'.$usersTable.'` WHERE Key_name = "idx_users_passout_year"');
		if (empty($passoutIdx)) {
			$this->dbObj->executeQuery('CREATE INDEX `idx_users_passout_year` ON `'.$usersTable.'` (`passout_year`)');
		}
	}

	private function ensureUsersRoleSchema(){
		$usersTable = $this->assertSafeIdentifier(TB_USERS);
		if ($this->tableHasColumn($usersTable, 'role')) {
			$roleIdx = $this->dbObj->getAllResults('SHOW INDEX FROM `'.$usersTable.'` WHERE Key_name = "idx_users_role"');
			if (empty($roleIdx)) {
				$this->dbObj->executeQuery('CREATE INDEX `idx_users_role` ON `'.$usersTable.'` (`role`)');
			}
			return;
		}

		$this->dbObj->executeQuery("ALTER TABLE `".$usersTable."` ADD COLUMN `role` ENUM('student','alumni','admin') NOT NULL DEFAULT 'student'");
		$this->dbObj->executeQuery('CREATE INDEX `idx_users_role` ON `'.$usersTable.'` (`role`)');
	}

	private function ensureModernAuthSchema(){
		// Some older SQL dumps use an invalid zero timestamp default that breaks ALTER TABLE on MySQL 8+.
		$this->ensureColumnDefinition(ADMIN_TABLE, 'last_access', "timestamp NULL DEFAULT NULL COMMENT 'customer login time and date is stored'");
		$this->ensureColumnDefinition(TB_USERS, 'password', "varchar(255) NOT NULL COMMENT 'user password is stored'");
		$this->ensureColumnDefinition(ADMIN_TABLE, 'password', "varchar(255) NOT NULL COMMENT 'customer password is stored'");
		$this->ensureColumnDefinition(TB_USERS, 'address', "varchar(255) NOT NULL COMMENT 'user address is stored'");
		$this->ensureColumnDefinition(ADMIN_TABLE, 'address', "varchar(255) NOT NULL COMMENT 'customer address is stored'");
	}

	private function ensureStaffAuthSchema(){
		$table = $this->assertSafeIdentifier(TB_STAFF);
		$columnCheck = $this->dbObj->getAllResults('SHOW COLUMNS FROM `'.$table.'` LIKE "password"');
		if (empty($columnCheck)) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$table.'` ADD COLUMN `password` varchar(255) NOT NULL DEFAULT "" AFTER `e_mail`');
		} else {
			$this->ensureColumnDefinition($table, 'password', "varchar(255) NOT NULL DEFAULT ''");
		}
	}

	private function ensureGallerySchema(){
		$galleryTable = $this->assertSafeIdentifier(TB_GALLERY);
		$categoryTable = $this->assertSafeIdentifier(TB_GALLERY_CATEGORY);

		$createSql = "CREATE TABLE IF NOT EXISTS `".$categoryTable."` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`category_name` varchar(500) NOT NULL,
			`linked_event_id` int(11) DEFAULT NULL,
			`sort_order` int(11) NOT NULL DEFAULT 0,
			`is_active` tinyint(4) NOT NULL DEFAULT 1,
			PRIMARY KEY (`id`)
		) ENGINE=InnoDB DEFAULT CHARSET=latin1";
		$this->dbObj->executeQuery($createSql);

		$this->ensureGalleryColumn($galleryTable, 'category_id', "int(11) NOT NULL DEFAULT 0");
		$this->ensureGalleryCategoryColumn($categoryTable, 'linked_event_id', "int(11) DEFAULT NULL");
		$this->ensureGalleryCategoryColumn($categoryTable, 'sort_order', "int(11) NOT NULL DEFAULT 0");
		$this->ensureGalleryCategoryColumn($categoryTable, 'is_active', "tinyint(4) NOT NULL DEFAULT 1");

		$this->syncLegacyGalleryCategories($galleryTable, $categoryTable);
	}

	private function sectionSupportsBatchId(){
		if ($this->sectionBatchSupport !== null) {
			return (bool)$this->sectionBatchSupport;
		}

		$result = $this->dbObj->getAllResults('SHOW COLUMNS FROM `'.TB_SECTION.'` LIKE "batch_id"');
		$this->sectionBatchSupport = !empty($result);
		return (bool)$this->sectionBatchSupport;
	}

	private function ensureSectionBatchSchema(){
		if ($this->sectionSupportsBatchId()) {
			return;
		}

		// Allow sections to vary per academic batch.
		$altered = $this->dbObj->executeQuery('ALTER TABLE `'.TB_SECTION.'` ADD COLUMN `batch_id` int(11) NOT NULL DEFAULT 0');
		if ($altered) {
			$this->sectionBatchSupport = true;
			// Helpful index for lookups in registration and admin lists.
			$this->dbObj->executeQuery('CREATE INDEX `idx_section_batch_class` ON `'.TB_SECTION.'` (`batch_id`, `class_id`)');
		}
	}

	private function tableHasColumn($table, $columnName){
		$table = $this->assertSafeIdentifier($table);
		$columnName = $this->assertSafeIdentifier($columnName);
		$result = $this->dbObj->getAllResults('SHOW COLUMNS FROM `'.$table.'` LIKE "'.$columnName.'"');
		return !empty($result);
	}

	private function ensureBatchIdColumn($table){
		$table = $this->assertSafeIdentifier($table);
		if (!$this->tableHasColumn($table, 'batch_id')) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$table.'` ADD COLUMN `batch_id` int(11) NOT NULL DEFAULT 0');
		}
	}

	private function ensureAcademicBatchSchema(){
		// Batch-scoped academics: subjects/syllabus can differ by batch.
		$this->ensureBatchIdColumn(TB_SUBJECTS);
		$this->ensureBatchIdColumn(TB_SYLLABUS);

		// Helpful indexes for batch + class lookups.
		$subIdx = $this->dbObj->getAllResults('SHOW INDEX FROM `'.TB_SUBJECTS.'` WHERE Key_name = "idx_subject_batch_class"');
		if (empty($subIdx)) {
			$this->dbObj->executeQuery('CREATE INDEX `idx_subject_batch_class` ON `'.TB_SUBJECTS.'` (`batch_id`, `class_id`)');
		}
		$sylIdx = $this->dbObj->getAllResults('SHOW INDEX FROM `'.TB_SYLLABUS.'` WHERE Key_name = "idx_syllabus_batch_class"');
		if (empty($sylIdx)) {
			$this->dbObj->executeQuery('CREATE INDEX `idx_syllabus_batch_class` ON `'.TB_SYLLABUS.'` (`batch_id`, `class_id`)');
		}
	}
	private function ensureGalleryColumn($table, $columnName, $definition){
		$table = $this->assertSafeIdentifier($table);
		$columnName = $this->assertSafeIdentifier($columnName);
		$columnCheck = $this->dbObj->getAllResults('SHOW COLUMNS FROM `'.$table.'` LIKE "'.$columnName.'"');
		if (empty($columnCheck)) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$table.'` ADD COLUMN `'.$columnName.'` '.$definition);
		}
	}

	private function ensureGalleryCategoryColumn($table, $columnName, $definition){
		$table = $this->assertSafeIdentifier($table);
		$columnName = $this->assertSafeIdentifier($columnName);
		$columnCheck = $this->dbObj->getAllResults('SHOW COLUMNS FROM `'.$table.'` LIKE "'.$columnName.'"');
		if (empty($columnCheck)) {
			$this->dbObj->executeQuery('ALTER TABLE `'.$table.'` ADD COLUMN `'.$columnName.'` '.$definition);
		}
	}

	private function syncLegacyGalleryCategories($galleryTable, $categoryTable){
		$legacyRows = $this->dbObj->getAllResults(
			'SELECT DISTINCT event_id FROM `'.$galleryTable.'` WHERE category_id = 0 ORDER BY event_id ASC'
		);

		if (empty($legacyRows)) {
			return;
		}

		foreach ($legacyRows as $legacyRow) {
			$linkedEventId = (int)$legacyRow['event_id'];
			$categoryId = $this->ensureGalleryCategoryForEvent($categoryTable, $linkedEventId);
			if ($categoryId > 0) {
				$this->dbObj->executePrepared(
					'UPDATE `'.$galleryTable.'` SET category_id = :category_id WHERE category_id = 0 AND event_id = :event_id',
					array(
						':category_id' => $categoryId,
						':event_id' => $linkedEventId
					)
				);
			}
		}
	}

	private function ensureGalleryCategoryForEvent($categoryTable, $linkedEventId){
		$linkedEventId = (int)$linkedEventId;
		$existing = $this->dbObj->getOnePrepared(
			'SELECT id FROM `'.$categoryTable.'` WHERE linked_event_id = :linked_event_id LIMIT 1',
			array(':linked_event_id' => $linkedEventId)
		);
		if (!empty($existing)) {
			return (int)$existing['id'];
		}

		$categoryName = '';
		if ($linkedEventId === 0) {
			$categoryName = 'Others';
		} elseif ($linkedEventId === -1) {
			$categoryName = 'Press News';
		} else {
			$event = $this->dbObj->getOnePrepared(
				'SELECT event_name FROM `'.TB_EVENTS.'` WHERE id = :event_id LIMIT 1',
				array(':event_id' => $linkedEventId)
			);
			if (!empty($event)) {
				$categoryName = trim((string)$event['event_name']);
			}
		}

		if ($categoryName === '') {
			$categoryName = 'Gallery Category '.$linkedEventId;
		}

		$duplicate = $this->dbObj->getOnePrepared(
			'SELECT id FROM `'.$categoryTable.'` WHERE LOWER(category_name) = LOWER(:category_name) LIMIT 1',
			array(':category_name' => $categoryName)
		);
		if (!empty($duplicate)) {
			$this->dbObj->executePrepared(
				'UPDATE `'.$categoryTable.'` SET linked_event_id = :linked_event_id WHERE id = :id',
				array(
					':linked_event_id' => $linkedEventId,
					':id' => (int)$duplicate['id']
				)
			);
			return (int)$duplicate['id'];
		}

		$this->dbObj->executePrepared(
			'INSERT INTO `'.$categoryTable.'` (category_name, linked_event_id, sort_order, is_active) VALUES (:category_name, :linked_event_id, 0, 1)',
			array(
				':category_name' => $categoryName,
				':linked_event_id' => $linkedEventId
			)
		);

		return (int)$this->dbObj->getLastInsertId();
	}

	private function ensureColumnDefinition($table, $columnName, $definition){
		$table = $this->assertSafeIdentifier($table);
		$columnName = $this->assertSafeIdentifier($columnName);
		$definition = trim((string)$definition);

		$checkSql = "SELECT COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT
					 FROM information_schema.COLUMNS
					 WHERE TABLE_SCHEMA = DATABASE()
					   AND TABLE_NAME = :table_name
					   AND COLUMN_NAME = :column_name
					 LIMIT 1";

		$result = $this->dbObj->getOnePrepared($checkSql, array(
			':table_name' => $table,
			':column_name' => $columnName
		));

		if (empty($result)) {
			return;
		}

		$currentType = strtolower((string)($result['COLUMN_TYPE'] ?? ''));
		if ($columnName === 'password' && strpos($currentType, 'varchar(255)') !== false) {
			return;
		}

		if ($columnName === 'address' && preg_match('/varchar\((\d+)\)/', $currentType, $matches) && (int)$matches[1] >= 255) {
			return;
		}

		$alterSql = "ALTER TABLE `".$table."` MODIFY `".$columnName."` ".$definition;
		$this->dbObj->executeQuery($alterSql);
	}


	 /*
	  *  For admin functions
	  */
	 
	 public function adminLogin($table,$adminName){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "SELECT id, adminname, password, firstname, mail_id, image FROM `".$table."` WHERE adminname = :adminname";
			$result = $this->dbObj->getAllPrepared($sqlQuery, array(':adminname' => (string)$adminName));
			
			return $result;
	 }


	/*
	 *  CHANGE ADMIN PASSWORD
	 */
	 public function changeAdminPassWord($table, $varArray){
			$table = $this->assertSafeIdentifier($table);
			$aName = (string)$varArray['admin_name'];
			$pass = (string)$varArray['pass_word'];
			$sqlQuery = "UPDATE `".$table."` SET password = :password WHERE adminname = :adminname";
			$result = $this->dbObj->executePrepared($sqlQuery, array(
				':password' => $pass,
				':adminname' => $aName
			));
			
			return $result;
	 }

	/*
	 *  GET ADMIN PROFILE BY ID
	 */
	 public function getAdminById($table, $adminId){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "SELECT id, adminname, password, mail_id, firstname, lastname, gender, address, mobile_no, qualification, image
						 FROM `".$table."`
						 WHERE id = :id";
			return $this->dbObj->getAllPrepared($sqlQuery, array(':id' => (int)$adminId));
	 }

	/*
	 *  CHECK ADMINNAME FOR ANOTHER ACCOUNT
	 */
	 public function adminNameExistsForOther($table, $adminName, $adminId){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "SELECT id
						 FROM `".$table."`
						 WHERE adminname = :adminname AND id <> :id";
			return $this->dbObj->getAllPrepared($sqlQuery, array(
				':adminname' => (string)$adminName,
				':id' => (int)$adminId
			));
	 }

	/*
	 *  UPDATE ADMIN PROFILE
	 */
	 public function updateAdminProfile($table, $adminId, $varArray){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "UPDATE `".$table."`
						SET adminname = :adminname,
							password = :password,
							mail_id = :mail_id,
							firstname = :firstname,
							lastname = :lastname,
							gender = :gender,
							address = :address,
							mobile_no = :mobile_no,
							qualification = :qualification,
							image = :image
						WHERE id = :id";
			return $this->dbObj->executePrepared($sqlQuery, array(
				':adminname' => (string)$varArray['adminname'],
				':password' => (string)$varArray['password'],
				':mail_id' => (string)$varArray['mail_id'],
				':firstname' => (string)$varArray['firstname'],
				':lastname' => (string)$varArray['lastname'],
				':gender' => (string)$varArray['gender'],
				':address' => (string)$varArray['address'],
				':mobile_no' => (string)$varArray['mobile_no'],
				':qualification' => (string)$varArray['qualification'],
				':image' => (string)$varArray['image'],
				':id' => (int)$adminId
			));
	 }

	/*
	 *  User Registration
	 */
	 public function regUser($table,$varArray){
			$table = $this->assertSafeIdentifier($table);
			$uName		= $varArray['username'];			
			$admId		= $varArray['admission_id'];
			
			$tbUser		= TB_USERS;

			$checkUser	= $this->userCheck($tbUser,$uName);
			
			$admIdCheck	= $this->admsnIdCheck($tbUser,$admId);
			
			if( empty($checkUser) && empty($admIdCheck) ){

				$status = isset($varArray['status']) ? (int)$varArray['status'] : 0;
				$sql = "INSERT INTO `".$table."`
						(username, password, mail_id, firstname, lastname, gender, address, mobile_no, batch_id, stream_id, section, admission_id, image, status)
						VALUES
						(:username, :password, :mail_id, :firstname, :lastname, :gender, :address, :mobile_no, :batch_id, :stream_id, :section, :admission_id, :image, :status)";

				$result = $this->dbObj->executePrepared($sql, array(
					':username' => (string)$varArray['username'],
					':password' => (string)$varArray['password'],
					':mail_id' => (string)$varArray['mail_id'],
					':firstname' => (string)$varArray['firstname'],
					':lastname' => (string)$varArray['lastname'],
					':gender' => (string)$varArray['gender'],
					':address' => (string)$varArray['address'],
					':mobile_no' => (string)$varArray['mobile_no'],
					':batch_id' => (int)$varArray['batch_id'],
					':stream_id' => (int)$varArray['stream_id'],
					':section' => (string)$varArray['section'],
					':admission_id' => (string)$varArray['admission_id'],
					':image' => (string)$varArray['image'],
					':status' => $status
				));

			}else if ( empty($admIdCheck) ) {
				
				$result		= 'username already taken';
			}else if ( empty($checkUser) ) {
				
				$result		= 'With This Roll No, User Already Registered';
			}else{
			
				$result		= 'username AND Roll No Are Already Taken';
			}
			return $result;
	 }

	/*
	 *  Checking User
	 */
	 public function userCheck($table,$uName){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "SELECT id, username, password, firstname, lastname, mail_id, admission_id, batch_id, stream_id, section, gender, address, mobile_no, image, status FROM `".$table."` WHERE username = :username";
			$result = $this->dbObj->getAllPrepared($sqlQuery, array(':username' => (string)$uName));
			
			return $result;
	 }
	 
	/*
	 *  CHANGE USER PROFILE
	 */
	 public function changeUserProfile($table, $varArray, $uName){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "UPDATE `".$table."`
						SET username = :new_username,
							password = :password,
							firstname = :firstname,
							lastname = :lastname,
							gender = :gender,
							address = :address,
							mobile_no = :mobile_no,
							batch_id = :batch_id,
							stream_id = :stream_id,
							section = :section,
							admission_id = :admission_id,
							image = :image,
							mail_id = :mail_id
						WHERE username = :current_username";

			$result = $this->dbObj->executePrepared($sqlQuery, array(
				':new_username' => (string)$varArray['username'],
				':password' => (string)$varArray['password'],
				':firstname' => (string)$varArray['firstname'],
				':lastname' => (string)$varArray['lastname'],
				':gender' => (string)$varArray['gender'],
				':address' => (string)$varArray['address'],
				':mobile_no' => (string)$varArray['mobile_no'],
				':batch_id' => (int)$varArray['batch_id'],
				':stream_id' => (int)$varArray['stream_id'],
				':section' => (string)$varArray['section'],
				':admission_id' => (string)$varArray['admission_id'],
				':image' => (string)$varArray['image'],
				':mail_id' => (string)$varArray['mail_id'],
				':current_username' => (string)$uName
			));
			
			return $result;
	 }

	/*
	 *  Checking Roll No
	 */
	 public function admsnIdCheck($table,$admsnId){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "SELECT id, username, password, firstname, mail_id, admission_id, image, status FROM `".$table."` WHERE admission_id = :admission_id";
			$result = $this->dbObj->getAllPrepared($sqlQuery, array(':admission_id' => (string)$admsnId));
			
			return $result;
	 }
	 
	/*
	 *  GET TEMPORARY USERS
	 */
	 public function getTempUsers($table){
			
			$sqlQuery	= 'SELECT id, username, password, firstname, lastname, mail_id, admission_id, image, status FROM '.$table.' WHERE status = 0';

			$result		= $this->dbObj->getAllResults($sqlQuery);
			
			return $result;
	 }
	 
	/*
	 *  APPROVE USER
	 */
	 public function approveUser($table, $userId){
			
			$sqlQuery	= 'UPDATE '.$table.' SET status = 1 WHERE id = '.$userId;

			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	 }

	/*
	 *  CHANGE PASSWORD
	 */
	 public function changeUserPassWord($table, $varArray){
			$table = $this->assertSafeIdentifier($table);
			$uName = (string)$varArray['user_name'];
			$pass = (string)$varArray['pass_word'];
			$sqlQuery = "UPDATE `".$table."` SET password = :password WHERE username = :username";
			$result = $this->dbObj->executePrepared($sqlQuery, array(
				':password' => $pass,
				':username' => $uName
			));
			
			return $result;
	 }

	/*
	 *  DELETE USERS
	 */
	 public function deleteUser($table, $userId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$userId;

			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	 }

	 /*
	 *  Random Quotations
	 */
	 public function getQuotes($table){
			
			$sqlQuery	= "SELECT id,quote,bywhom FROM $table ORDER BY RAND() LIMIT 1";
			
			$result		= $this->dbObj->getOneRow($sqlQuery);
			
			return $result;
	 }

	/*
	 *  Random Comments
	 */
	 public function getComments($table){
			
			$sqlQuery	= "SELECT comts.id,user.firstname,user.lastname,comment FROM $table comts, ".TB_USERS." user WHERE comts.user_id = user.id AND is_approved = 1 ORDER BY id DESC LIMIT 3";
			
			$result		= $this->dbObj->getAllResults($sqlQuery);
			
			return $result;
	 }

	/*
	 *  Place Comment
	 */
	 public function dropFewWords($table,$userId,$comment){
			
			$sql		= 'INSERT INTO '.$table.' (user_id, comment , is_approved ) VALUES ( '.$userId.', "'.$comment.'", 0 ) ';
			
			$result		= $this->dbObj->executeQuery($sql);
			
			return $result;
	 }	 

	/*
	 *  Get Users
	 */
	 public function getUsers($table){

			$sql		= 'SELECT id,username,mail_id FROM '.$table;

			$result		= $this->dbObj->getAllResults($sql);
						
			return $result;
	 }	

	/*
	 *  Get Approved Users For Committee Assignment
	 */
	 public function getApprovedUsersForCommittee($table){

			$sql		= 'SELECT id, firstname, lastname, address, image
						   FROM '.$table.'
						   WHERE status = 1
						   ORDER BY firstname ASC, lastname ASC';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 }	
	 
	/*
	 *  Get New Comments
	 */
	 public function getNewComments($table){

			$sql		= 'SELECT commts.id,commts.user_id,user.username,user.firstname,user.lastname,commts.comment FROM '.$table.' commts, '.TB_USERS.' user WHERE commts.user_id = user.id AND is_approved = 0';

			$result		= $this->dbObj->getAllResults($sql);
						
			return $result;
	 }	
	 
	/*
	 *  Approve Comments
	 */
	 public function approveComments($table,$valueArray){

			$noOfChecked	= sizeof($valueArray);

			for($i = 0; $i < $noOfChecked; $i++){
				
				$value		= $valueArray[$i];

				$sql		= 'UPDATE '.$table.' SET is_approved = 1 WHERE id = '.$value;

				$result[$i]	= $this->dbObj->executeQuery($sql);
			}
						
			return $result;
	 }	
	
	/*
	 *  Add Quotations
	 */
	 public function addQuotation($table,$quotation,$quoteBy){

			$sql		= 'INSERT INTO '.$table.' (quote, bywhom ) VALUES ( "'.$quotation.'", "'.$quoteBy.'" ) ';
			
			$result		= $this->dbObj->executeQuery($sql);
			
			return $result;
	 }	
	 
	/*
	 *  Admin Registration
	 */
	 public function adminRegistration($table,$varArray){
			
			$uName		= $varArray['adminname'];			
			
			$checkUser	= $this->adminLogin($table,$uName);
			
			if( empty($checkUser) ){

				$values		= "('".implode("','", $varArray)."')";

				$sql		= 'INSERT INTO '.$table.'(adminname, password, mail_id, firstname, lastname, gender, address, mobile_no, qualification) VALUES '.$values;

				$result		= $this->dbObj->executeQuery($sql);

			}else{
				
				$result		= 'username already taken';
			}
			return $result;
	 }
	 
	/*
	 *  GET BATCHES
	 */
 	 public function getBatches($table){
 			
 			$sql		= 'SELECT 
								id, batch
 						   FROM 
 						   		'.$table.'
 						   WHERE
 						   		1';

 			$result		= $this->dbObj->getAllResults($sql);

 			return $result;
 	 } 

	/*
	 *  Add BATCH
	 */
 	 public function addBatch($table,$varArray){

 			$batchName	= $varArray['batch_name'];
 			
			$sql		= 'INSERT INTO '.$table.' (batch) VALUES ( "'.$batchName.'") ';
 			
 			$result		= $this->dbObj->executeQuery($sql);
 			
 			return $result;
 	 }	
	 
	/*
	 *  UPDATE BATCH
	 */
 	 public function editBatch($table, $varArray){
 			
 			$batchId		= $varArray['batch_id'];
 			$batchName		= $varArray['batch_name'];

			$sqlQuery		= 'UPDATE '.$table.' SET batch = "'.$batchName.'" WHERE id = '.$batchId;
 			
 			$result		= $this->dbObj->executeQuery($sqlQuery);
 			
 			return $result;
 	}
	
	/*
	 *  GET BATCH BY ID
	 */
 	 public function getBatchById($table,$batchId){
 			
 			$sql		= 'SELECT 
								id, batch
 						   FROM 
 						   		'.$table.'
 						   WHERE
 						   		id = '.$batchId;

 			$result		= $this->dbObj->getAllResults($sql);

 			return $result;
 	 } 
	
	/*
	 *  DELETE BATCH
	 */
	 public function deleteBatch($table, $batchId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$batchId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET STREAMS
	 */
	 public function getStreams($table){
			
			$sql		= 'SELECT 
								id, stream_code, stream_name 
						   FROM 
						   		'.$table.'
						   WHERE
						   		1';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  GET OR CREATE STREAM BY CODE (used for Year selection)
	 */
	 public function getOrCreateStreamIdByCode($table, $streamCode, $streamName = ''){
			$streamCode = trim((string)$streamCode);
			if ($streamCode === '') {
				return 0;
			}

			$safeCode = addslashes($streamCode);
			$existing = $this->dbObj->getAllResults(
				'SELECT id FROM '.$table.' WHERE LOWER(stream_code) = LOWER("'.$safeCode.'") LIMIT 1'
			);

			if (!empty($existing)) {
				return (int)$existing[0]['id'];
			}

			$safeName = addslashes(trim((string)$streamName));
			if ($safeName === '') {
				$safeName = $safeCode;
			}

			$inserted = $this->dbObj->executeQuery(
				'INSERT INTO '.$table.' (stream_code, stream_name) VALUES ("'.$safeCode.'", "'.$safeName.'")'
			);

			if ($inserted) {
				return (int)$this->dbObj->getLastInsertId();
			}

			return 0;
	 }

	/*
	 *  Add BRANCH
	 */
	 public function addBranch($table,$varArray){

			$branchCode	= $varArray['branch_code'];
			$branchName	= $varArray['branch_name'];
			
			$sql		= 'INSERT INTO '.$table.' ( stream_code , stream_name ) VALUES ( "'.$branchCode.'", "'.$branchName.'") ';
			
			$result		= $this->dbObj->executeQuery($sql);
			
			return $result;
	 }	
	 
	/*
	 *  UPDATE BRANCH
	 */
	 public function editBranch($table, $varArray){
			
			$branchId		= $varArray['branch_id'];
			$branchCode		= $varArray['branch_code'];
			$branchName		= $varArray['branch_name'];

			$sqlQuery		= 'UPDATE '.$table.' SET stream_code = "'.$branchCode.'", stream_name = "'.$branchName.'" WHERE id = '.$branchId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}
	
	/*
	 *  GET BATCH BY ID
	 */
	 public function getBranchById($table,$branchId){
			
			$sql		= 'SELECT 
								id, stream_code, stream_name 
						   FROM 
						   		'.$table.'
						   WHERE
						   		id = '.$branchId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 
	
	/*
	 *  DELETE BRANCH
	 */
	 public function deleteBranch($table, $branchId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$branchId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET CLASSES
	 */
	 public function getClasses($table){
			
			$sql		= 'SELECT 
								id, class_code, class_name 
						   FROM 
						   		'.$table.'
						   WHERE
						   		1';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  PICK DEFAULT CLASS FOR YEAR (prefers SEM I)
	 */
	 public function getDefaultClassIdForYear($table, $year){
			$year = (int)$year;
			if ($year <= 0) {
				return 0;
			}

			$needle = addslashes((string)$year);
			$pref = $this->dbObj->getAllResults(
				'SELECT id FROM '.$table.'
				 WHERE LOWER(class_name) LIKE LOWER("'.$needle.'%year%sem i%")
				 LIMIT 1'
			);
			if (!empty($pref)) {
				return (int)$pref[0]['id'];
			}

			$any = $this->dbObj->getAllResults(
				'SELECT id FROM '.$table.'
				 WHERE LOWER(class_name) LIKE LOWER("'.$needle.'%year%")
				 LIMIT 1'
			);
			if (!empty($any)) {
				return (int)$any[0]['id'];
			}

			return 0;
	 }

	/*
	 *  GET CLASSES WITHOUT PASSOUT
	 */
	 public function getClassesWOPO($table){
			
			$sql		= 'SELECT 
								id, class_code, class_name 
						   FROM 
						   		'.$table.'
						   WHERE
						   		id != 0';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 
	
	/*
	 *  GET CLASSES BY ID
	 */
	 public function getClassById($table,$classId){
			
			$sql		= 'SELECT 
								id, class_code, class_name 
						   FROM 
						   		'.$table.'
						   WHERE
						   		id = '.$classId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 
	
	/*
	 *  Add CLASS
	 */
	 public function addClass($table,$varArray){

			$classCode	= $varArray['class_code'];
			$className	= $varArray['class_name'];
			
			$sql		= 'INSERT INTO '.$table.' (class_code, class_name ) VALUES ( "'.$classCode.'", "'.$className.'" ) ';
			
			$result		= $this->dbObj->executeQuery($sql);
			
			return $result;
	 }	
	 
	/*
	 *  UPDATE CLASS
	 */
	 public function editClass($table, $varArray){
			
			$classId		= $varArray['class_id'];
			$clsName		= $varArray['class_name'];
			$clsCode		= $varArray['class_code'];

			$sqlQuery		= 'UPDATE '.$table.' SET class_code = "'.$clsCode.'", class_name = "'.$clsName.'" WHERE id = '.$classId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  DELETE CLASS
	 */
	 public function deleteClass($table, $classId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$classId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET CLASS BY SECTION
	 */
	 public function getClsBySec($table,$secId){
			$where = 'sec.section_code = "'.$secId.'"';
			if (is_numeric($secId)) {
				$where = 'sec.id = '.$secId;
			}

			$sql		= 'SELECT
								sec.id section_id, sec.section_code, sec.section_name , cls.id class_id, cls.class_code, cls.class_name 
						   FROM 
						   		class cls,
								'.$table.' sec
								
						   WHERE
						   		'.$where.'
							AND
								sec.class_id = cls.id';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  GET SECTION
	 */
	 public function getSections($table, $classId = null, $batchId = null){
			$table = $this->assertSafeIdentifier($table);

			$conditions = array();
			if ($classId !== null && $classId !== '') {
				$conditions[] = 'class_id = ' . (int)$classId;
			}

			$batchId = (int)($batchId ?? 0);
			if ($batchId > 0 && $this->sectionSupportsBatchId()) {
				$conditions[] = 'batch_id = ' . $batchId;
			}

			$where = empty($conditions) ? '1' : implode(' AND ', $conditions);

			$selectCols = 'id, section_code, section_name';
			if ($this->sectionSupportsBatchId()) {
				$selectCols .= ', batch_id';
			}

			$sql = 'SELECT '.$selectCols.' FROM `'.$table.'` WHERE '.$where.' ORDER BY id DESC';
			return $this->dbObj->getAllResults($sql);
	 } 
	 
	/*
	 *  GET SECTION BY ID
	 */
	 public function getSectionById($table,$secId){
			$table = $this->assertSafeIdentifier($table);
			$secId = (int)$secId;

			$selectCols = 'tb.id, tb.section_code, tb.section_name, cls.class_name, cls.class_code, cls.id class_id';
			if ($this->sectionSupportsBatchId()) {
				$selectCols .= ', tb.batch_id, bat.batch';
			}

			$sql = 'SELECT '.$selectCols.'
					FROM `'.$table.'` tb
					LEFT JOIN `class` cls ON tb.class_id = cls.id';
			if ($this->sectionSupportsBatchId()) {
				$sql .= ' LEFT JOIN `'.TB_BATCH.'` bat ON tb.batch_id = bat.id';
			}
			$sql .= ' WHERE tb.id = '.$secId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 
	
	/*
	 *  Add SECTION
	 */
	 public function addSection($table,$varArray){
			$table = $this->assertSafeIdentifier($table);

			$clsId		= $varArray['class_id'];
			$batchId	= isset($varArray['batch_id']) ? (int)$varArray['batch_id'] : 0;
			$secCode	= $varArray['section_code'];
			$secName	= $varArray['section_name'];

			if ($this->sectionSupportsBatchId()) {
				if ($batchId <= 0) {
					return false;
				}
				$sql = 'INSERT INTO `'.$table.'` ( batch_id, class_id, section_code, section_name )
						VALUES ( "'.$batchId.'", "'.$clsId.'", "'.$secCode.'", "'.$secName.'" )';
			} else {
				$sql = 'INSERT INTO `'.$table.'` ( class_id, section_code, section_name )
						VALUES ( "'.$clsId.'", "'.$secCode.'", "'.$secName.'" )';
			}
			
			$result		= $this->dbObj->executeQuery($sql);
			
			return $result;
	 }	

	/*
	 *  GET OR CREATE CLASS BY NAME
	 */
	 public function getOrCreateClassIdByName($table, $className){
			$className = trim((string)$className);
			if ($className === '') {
				return 0;
			}

			$safeName = addslashes($className);

			$existing = $this->dbObj->getAllResults(
				'SELECT id FROM '.$table.' WHERE LOWER(class_name) = LOWER("'.$safeName.'") LIMIT 1'
			);

			if (!empty($existing)) {
				return (int)$existing[0]['id'];
			}

			$code = strtoupper(preg_replace('/[^A-Z0-9]/i', '', (string)$className));
			$code = substr($code, 0, 12);
			if ($code === '') {
				$code = 'CLASS';
			}
			$safeCode = addslashes($code);

			$inserted = $this->dbObj->executeQuery(
				'INSERT INTO '.$table.' (class_code, class_name) VALUES ("'.$safeCode.'", "'.$safeName.'")'
			);

			if ($inserted) {
				return (int)$this->dbObj->getLastInsertId();
			}

			return 0;
	 }

	/*
	 *  GET OR CREATE SECTION BY NAME FOR A CLASS
	 */
	 public function getOrCreateSectionIdByName($table, $classId, $sectionName, $batchId = 0){
 			$classId = (int)$classId;
			$batchId = (int)$batchId;
 			$sectionName = trim((string)$sectionName);
 			if ($classId <= 0 || $sectionName === '') {
 				return 0;
 			}

			if ($this->sectionSupportsBatchId() && $batchId <= 0) {
				return 0;
			}

 			$safeName = addslashes($sectionName);
			$table = $this->assertSafeIdentifier($table);
			$where = 'class_id = '.$classId.' AND LOWER(section_name) = LOWER("'.$safeName.'")';
			if ($batchId > 0 && $this->sectionSupportsBatchId()) {
				$where .= ' AND batch_id = '.$batchId;
			}
			$existing = $this->dbObj->getAllResults('SELECT id FROM `'.$table.'` WHERE '.$where.' LIMIT 1');
 
 			if (!empty($existing)) {
 				return (int)$existing[0]['id'];
 			}
 
 			$secCode = addslashes(strtoupper(preg_replace('/\\s+/', '', $sectionName)));
 			if ($secCode === '') {
 				$secCode = $safeName;
 			}
 
			if ($batchId > 0 && $this->sectionSupportsBatchId()) {
				$inserted = $this->dbObj->executeQuery(
					'INSERT INTO `'.$table.'` (batch_id, class_id, section_code, section_name) VALUES ("'.$batchId.'", "'.$classId.'", "'.$secCode.'", "'.$safeName.'")'
				);
			} else {
				$inserted = $this->dbObj->executeQuery(
					'INSERT INTO `'.$table.'` (class_id, section_code, section_name) VALUES ("'.$classId.'", "'.$secCode.'", "'.$safeName.'")'
				);
			}
 
 			if ($inserted) {
 				return (int)$this->dbObj->getLastInsertId();
 			}

			return 0;
	 }
	 
	/*
	 *  UPDATE SECTION
	 */
	 public function editSection($table, $varArray){
			$table = $this->assertSafeIdentifier($table);
			
			$classId		= $varArray['class_id'];
			$secId			= $varArray['sec_id'];
			$batchId		= isset($varArray['batch_id']) ? (int)$varArray['batch_id'] : 0;

			$secName		= $varArray['sec_name'];
			$secCode		= $varArray['sec_code'];

			if ($this->sectionSupportsBatchId()) {
				if ($batchId <= 0) {
					return false;
				}
				$sqlQuery = 'UPDATE `'.$table.'` SET batch_id = "'.$batchId.'", class_id = "'.$classId.'", section_code = "'.$secCode.'", section_name = "'.$secName.'" WHERE id = '.$secId;
			} else {
				$sqlQuery = 'UPDATE `'.$table.'` SET class_id = "'.$classId.'", section_code = "'.$secCode.'", section_name = "'.$secName.'" WHERE id = '.$secId;
			}
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	 }

	/*
	 *  DELETE SECTION
	 */
	 public function deleteSection($table, $sectionId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$sectionId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET STAFF CATEGORIES
	 */
	 public function getStaffCategories($table){
			$table = $this->assertSafeIdentifier($table);
						 
			$sql		= 'SELECT 
								id, category_name
						   FROM 
						   		'.$table.'
						   ORDER BY
						   		category_name ASC';

			$result		= $this->dbObj->getAllResults($sql);
			
			return $result;
	 } 

	/*
	 *  GET STAFF CATEGORY BY ID
	 */
	 public function getStaffCategoryById($table, $categoryId){
			$table = $this->assertSafeIdentifier($table);
			$sql = 'SELECT id, category_name FROM '.$table.' WHERE id = :id LIMIT 1';

			$result = $this->dbObj->getAllPrepared($sql, array(
				':id' => (int)$categoryId
			));

			return $result;
	 }

	/*
	 *  COUNT STAFF BY CATEGORY
	 */
	 public function countStaffByCategory($table, $categoryId){
			$table = $this->assertSafeIdentifier($table);
			$sql = 'SELECT COUNT(*) AS total FROM '.$table.' WHERE staff_categ_id = :category_id';

			$result = $this->dbObj->getOnePrepared($sql, array(
				':category_id' => (int)$categoryId
			));

			return isset($result['total']) ? (int)$result['total'] : 0;
	 }

	/*
	 *  ADD STAFF CATEGORY
	 */
	 public function addStaffCategory($table, $categoryName){
			$table = $this->assertSafeIdentifier($table);
			$categoryName = trim((string)$categoryName);

			if ($categoryName === '') {
				return false;
			}

			$existing = $this->dbObj->getOnePrepared(
				'SELECT id FROM '.$table.' WHERE LOWER(category_name) = LOWER(:category_name) LIMIT 1',
				array(':category_name' => $categoryName)
			);

			if (!empty($existing)) {
				return 0;
			}

			$result = $this->dbObj->executePrepared(
				'INSERT INTO '.$table.' (category_name) VALUES (:category_name)',
				array(':category_name' => $categoryName)
			);

			if ($result === false) {
				return false;
			}

			return (int)$this->dbObj->getLastInsertId();
	 }

	/*
	 *  UPDATE STAFF CATEGORY
	 */
	 public function updateStaffCategory($table, $categoryId, $categoryName){
			$table = $this->assertSafeIdentifier($table);
			$categoryName = trim((string)$categoryName);

			if ($categoryName === '') {
				return false;
			}

			$existing = $this->dbObj->getOnePrepared(
				'SELECT id FROM '.$table.' WHERE LOWER(category_name) = LOWER(:category_name) AND id != :id LIMIT 1',
				array(
					':category_name' => $categoryName,
					':id' => (int)$categoryId
				)
			);

			if (!empty($existing)) {
				return 0;
			}

			return $this->dbObj->executePrepared(
				'UPDATE '.$table.' SET category_name = :category_name WHERE id = :id',
				array(
					':category_name' => $categoryName,
					':id' => (int)$categoryId
				)
			);
	 }

	/*
	 *  DELETE STAFF CATEGORY
	 */
	 public function deleteStaffCategory($table, $categoryId){
			$table = $this->assertSafeIdentifier($table);

			return $this->dbObj->executePrepared(
				'DELETE FROM '.$table.' WHERE id = :id',
				array(':id' => (int)$categoryId)
			);
	 }

	/*
	 *  ADD STAFF DETAILS
	 */
	 public function addStaffDetails($table,$varArray){
			$table = $this->assertSafeIdentifier($table);
			$sql = "INSERT INTO `".$table."`
					(staff_categ_id, first_name, last_name, qualification, designation, industry_exp, teach_exp, research, publ_national, publ_international, conf_national, conf_international, e_mail, password, image)
					VALUES
					(:staff_categ_id, :first_name, :last_name, :qualification, :designation, :industry_exp, :teach_exp, :research, :publ_national, :publ_international, :conf_national, :conf_international, :email, :password, :image)";

			$result = $this->dbObj->executePrepared($sql, array(
				':staff_categ_id' => (int)$varArray['staffType'],
				':first_name' => (string)$varArray['firstName'],
				':last_name' => (string)$varArray['lastName'],
				':qualification' => (string)$varArray['staffQualif'],
				':designation' => (string)$varArray['staffDesig'],
				':industry_exp' => (string)$varArray['indusExp'],
				':teach_exp' => (string)$varArray['teachingExp'],
				':research' => (string)$varArray['research'],
				':publ_national' => (string)$varArray['pub_nat'],
				':publ_international' => (string)$varArray['pub_internat'],
				':conf_national' => (string)$varArray['conf_nat'],
				':conf_international' => (string)$varArray['conf_internat'],
				':email' => (string)$varArray['email'],
				':password' => (string)$varArray['password'],
				':image' => (string)$varArray['image']
			));

			return $result;
	 } 

	/*
	 *  EDIT STAFF DETAILS
	 */
	 public function editStaffDetails($table,$staffId,$varArray){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "UPDATE `".$table."`
						SET staff_categ_id = :staff_categ_id,
							first_name = :first_name,
							last_name = :last_name,
							qualification = :qualification,
							designation = :designation,
							industry_exp = :industry_exp,
							teach_exp = :teach_exp,
							research = :research,
							publ_national = :publ_national,
							publ_international = :publ_international,
							conf_national = :conf_national,
							conf_international = :conf_international,
							e_mail = :email,
							password = :password,
							image = :image
						WHERE id = :id";

			return $this->dbObj->executePrepared($sqlQuery, array(
				':staff_categ_id' => (int)$varArray['staffType'],
				':first_name' => (string)$varArray['firstName'],
				':last_name' => (string)$varArray['lastName'],
				':qualification' => (string)$varArray['staffQualif'],
				':designation' => (string)$varArray['staffDesig'],
				':industry_exp' => (string)$varArray['indusExp'],
				':teach_exp' => (string)$varArray['teachingExp'],
				':research' => (string)$varArray['research'],
				':publ_national' => (string)$varArray['pub_nat'],
				':publ_international' => (string)$varArray['pub_internat'],
				':conf_national' => (string)$varArray['conf_nat'],
				':conf_international' => (string)$varArray['conf_internat'],
				':email' => (string)$varArray['email'],
				':password' => (string)$varArray['password'],
				':image' => (string)$varArray['image'],
				':id' => (int)$staffId
			));
	 } 

	/*
	 *  GET STAFF DETAILS
	 */
	 public function getStaffDetails($table,$categoryId=NULL){
			
			if( $categoryId != NULL){
			
				$where	= 'staff_categ_id = '.$categoryId;
			}else{
				
				$where	= 1;
			}
			 
			$sql		= 'SELECT 
								id, first_name, last_name, designation, image, qualification 
						   FROM 
						   		'.$table.'
						   WHERE
						   		'.$where;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 
	 
	/*
	 *  GET STAFF DETAILS BY ID
	 */
	 public function getStaffDetailsById($table,$id=NULL){
			
			if( $id != NULL){
			
				$where	= 'id = '.$id;
			}else{
				
				$where	= 1;
			}
			 
			$sql		= 'SELECT 
								id, staff_categ_id, first_name, last_name, qualification, designation, industry_exp, teach_exp, research, e_mail, password, image, publ_national, publ_international, conf_national, conf_international 
						   FROM 
						   		'.$table.'
						   WHERE
						   		'.$where;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  FACULTY LOGIN
	 */
	 public function facultyLogin($table, $email){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = "SELECT id, first_name, last_name, e_mail, password, image, designation, qualification
						 FROM `".$table."`
						 WHERE e_mail = :email
						 LIMIT 1";
			return $this->dbObj->getAllPrepared($sqlQuery, array(':email' => (string)$email));
	 }

	 public function updateStaffPassword($table, $staffId, $passwordHash){
			$table = $this->assertSafeIdentifier($table);
			return $this->dbObj->executePrepared(
				"UPDATE `".$table."` SET password = :password WHERE id = :id",
				array(
					':password' => (string)$passwordHash,
					':id' => (int)$staffId
				)
			);
	 }
	 
	/*
	 *  DELETE STAFF
	 */
	 public function deleteStaff($table,$id){
			
			
			$sql		= 'DELETE
								 
						   FROM 
						   		'.$table.'
						   WHERE
						   		id = '.$id;
 
			$result		= $this->dbObj->executeQuery($sql);

			return $result;
	 } 

	/*
	 *  GET WISE COMMITTEE CATEGORIES
	 */
	 public function getComiteCatg($table){
						 
			$sql		= 'SELECT 
								id, category_name
						   FROM 
						   		'.$table;

			$result		= $this->dbObj->getAllResults($sql);
			
			return $result;
	 } 

	/*
	 *  GET OR CREATE COMMITTEE CATEGORY BY NAME
	 */
	 public function getOrCreateCommitteeCategoryId($table, $categoryName){
			$categoryName = trim((string)$categoryName);
			if ($categoryName === '') {
				return 0;
			}

			$safeCategory = addslashes($categoryName);

			$existing = $this->dbObj->getAllResults(
				'SELECT id FROM '.$table.' WHERE LOWER(category_name) = LOWER("'.$safeCategory.'") LIMIT 1'
			);

			if (!empty($existing)) {
				return (int)$existing[0]['id'];
			}

			$inserted = $this->dbObj->executeQuery(
				'INSERT INTO '.$table.' (category_name) VALUES ("'.$safeCategory.'")'
			);

			if ($inserted) {
				return (int)$this->dbObj->getLastInsertId();
			}

			return 0;
	 }

	/*
	 *  GET STAFF DETAILS
	 */
	 public function getCmtMembers($table,$categoryId){
			$this->ensureCommitteeMemberColumns($table);
			
			$sql		= 'SELECT
								tb.id,
								COALESCE(NULLIF(tb.member_name, ""), CONCAT_WS(" ", usr.firstname, usr.lastname)) AS member_name,
								COALESCE(NULLIF(tb.member_about, ""), usr.address, "") AS member_about,
								COALESCE(NULLIF(tb.member_image, ""), usr.image, "") AS member_image
						   FROM
						   		'.$table.' tb
						   LEFT JOIN
						   		'.TB_USERS.' usr
						   ON
						   		tb.user_id = usr.id
						   WHERE
						   		tb.committee_cat_id = '.$categoryId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  Ensure committee table supports manual member fields
	 */
	 public function ensureCommitteeMemberColumns($table){
			$columns = array(
				'member_name' => 'VARCHAR(255) NOT NULL DEFAULT ""',
				'member_about' => 'TEXT NULL',
				'member_image' => 'VARCHAR(255) NOT NULL DEFAULT ""'
			);

			foreach($columns as $columnName => $definition){
				$columnCheck = $this->dbObj->getAllResults('SHOW COLUMNS FROM '.$table.' LIKE "'.$columnName.'"');
				if( empty($columnCheck) ){
					$this->dbObj->executeQuery('ALTER TABLE '.$table.' ADD COLUMN '.$columnName.' '.$definition);
				}
			}

			return true;
	 }
	 
	/*
	 *  GET ALL PAST EVENTS
	 */
	 public function getPastEvents($table,$eventType=NULL){
			
			$month			= date("M Y");
			
			$startDate		= date('Y-m-01',strtotime($month));
			$endDate		= date('Y-m-t',strtotime($month));

			$whereParts		= array('event_date < "'.$startDate.'"');
			if ($eventType !== NULL && $eventType !== '') {
				$whereParts[] = 'event_type_id = '.$eventType;
			}
			
			$sql		= 'SELECT 
								id, event_name, is_registration, event_date, reg_frm_date, reg_to_date
						   FROM 
						   		'.$table.'
						   WHERE
						   		'.implode(' AND ', $whereParts);

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET ALL CURRENT EVENTS
	 */
	 public function getCurrentEvents($table,$eventType=NULL){
			
			$month			= date("M Y");
			
			$startDate		= date('Y-m-01',strtotime($month));
			$endDate		= date('Y-m-t',strtotime($month));

			$whereParts		= array('event_date BETWEEN "'.$startDate.'" AND "'.$endDate.'"');
			if ($eventType !== NULL && $eventType !== '') {
				$whereParts[] = 'event_type_id = '.$eventType;
			}
			
			$sql		= 'SELECT 
								id, event_name, is_registration, event_date, reg_frm_date, reg_to_date
						   FROM 
						   		'.$table.'
						   WHERE
						   		'.implode(' AND ', $whereParts);

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 
	
	/*
	 *  GET ALL FUTURE EVENTS
	 */
	 public function getFutureEvents($table,$eventType=NULL){
			
			$month			= date("M Y");
			
			$startDate		= date('Y-m-01',strtotime($month));
			$endDate		= date('Y-m-t',strtotime($month));

			$whereParts		= array('event_date > "'.$endDate.'"');
			if ($eventType !== NULL && $eventType !== '') {
				$whereParts[] = 'event_type_id = '.$eventType;
			}
			
			$sql		= 'SELECT 
								id, event_name, is_registration, event_date, reg_frm_date, reg_to_date
						   FROM 
						   		'.$table.'
						   WHERE
						   		'.implode(' AND ', $whereParts);

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	}

	/*
	 *  GET EVENT DETAILS
	 */
	 public function getEventDetails($table,$eventId=NULL){
			
			if( $eventId==NULL ){
				$where	= 1;
			}else{
				$where	= 'id = '.$eventId;
			}
			
			$sql		= 'SELECT 
								id, event_name, event_desc, event_address, event_type_id, is_registration, event_date, reg_frm_date, reg_to_date
						   FROM 
						   		'.$table.'
						   WHERE
						   		'.$where;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  UPDATE EVENT DETAILS
	 */
	 public function updateEvent($table, $varArray, $eventId){
			
			$eventType		= $varArray['event_type_id'];
			$eventName		= $varArray['event_name'];
			$eventDesc		= $varArray['event_desc'];
			$eventAddr		= $varArray['event_address'];
			$eventDate		= $varArray['event_date'];
			$eventFrmDate	= $varArray['reg_frm_date'];
			$eventToDate	= $varArray['reg_to_date'];
			$isRegis		= $varArray['is_registration'];
				
			$sql			= 'UPDATE '.$table.' SET event_type_id = '.$eventType.', event_name = "'.$eventName.'", event_desc = "'.$eventDesc.'", event_address = "'.$eventAddr.'" , event_date = "'.$eventDate.'", reg_frm_date = "'.$eventFrmDate.'", reg_to_date = "'.$eventToDate.'", is_registration = "'.$isRegis.'" WHERE id = '.$eventId;

			$result			= $this->dbObj->executeQuery($sql);
			
			return $result;
	 } 
	 
	/*
	 *  GET EVENT REGISTRATION CHECK
	 */
	 public function eventRegCheck($table,$eventId,$userId){
			
			$sql		= 'SELECT 
								tb.id, tb.event_id, tb.user_id, evt.event_name
						   FROM 
						   		'.$table.' tb,
								events evt
						   WHERE
						   		tb.event_id = '.$eventId.'
							AND
								tb.user_id	= '.$userId.'
							AND
								tb.event_id = evt.id';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET EVENT REGISTRATION 
	 */
	 public function eventRegister($table,$eventId,$userId){
			
			$sql		= 'INSERT INTO
							'.$table.' ( event_id, user_id, status)
							VALUES ( '.$eventId.', '.$userId.', 0 )';

			$result		= $this->dbObj->executeQuery($sql);

			return $result;
	 } 	 

	/*
	 *  GET EVENTS FOR SHORT LISTED
	 */
	 public function getShortListedEvents($table,$eventType){
			
			$today		= date('Y-m-d');
			$endDay		= date('Y-m-d', strtotime('2 day', strtotime($today)));
			
			$sql		= 'SELECT 
								id, event_name, is_registration, event_date, reg_frm_date, reg_to_date
						   FROM 
						   		'.$table.'
						   WHERE
						   		event_type_id = '.$eventType.'
							AND
								event_date BETWEEN "'.$today.'" AND "'.$endDay.'"';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET EVENTS FOR REGISTERED CANDIDATES (ADMIN)
	 */
	 public function getRegisteredCandidateEvents($table,$eventType){
			
			$sql		= 'SELECT 
								id, event_name, is_registration, event_date, reg_frm_date, reg_to_date
						   FROM 
						   		'.$table.'
						   WHERE
						   		event_type_id = '.$eventType.'
							AND
								is_registration = 1
						   ORDER BY
						   		event_date DESC';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET EVENT SHORT LIST CANDIDATES 
	 */
	 public function getEventSLCand($table,$eventId){
			
			$sql		= 'SELECT
								usr.id, usr.firstname, usr.lastname, str.stream_code, cls.class_name, usr.admission_id, sec.section_name, evnt.event_name, evnt.event_desc
							FROM
								'.TB_USERS.' usr, class cls, section sec, stream str, '.$table.' tb, events evnt
							WHERE
								tb.event_id = '.$eventId.'
							AND
								tb.user_id = usr.id
							AND
								usr.section = sec.id
							AND
								sec.class_id = cls.id
							AND
								usr.stream_id = str.id
							AND
								tb.event_id = evnt.id
							AND
								tb.status = 1';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET EVENT REGISTERED CANDIDATES 
	 */
	 public function getEventRegCand($table,$eventId){
			
			$sql		= 'SELECT
								usr.id, usr.firstname, usr.lastname, str.stream_code, cls.class_name, usr.admission_id, sec.section_name, evnt.event_name, evnt.event_desc
							FROM
								'.TB_USERS.' usr, class cls, section sec, stream str, '.$table.' tb, events evnt
							WHERE
								tb.event_id = '.$eventId.'
							AND
								tb.user_id = usr.id
							AND
								usr.section = sec.id
							AND
								sec.class_id = cls.id
							AND
								usr.stream_id = str.id
							AND
								tb.event_id = evnt.id
							AND
								tb.status = 0';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  APPROVE USERS FOR EVENTS 
	 */
	 public function approveUserForEvent($table,$eventId,$userId){
			
			$sql		= 'UPDATE '.$table.' SET status = 1 WHERE event_id = '.$eventId.' AND user_id = '.$userId;

			$result		= $this->dbObj->executeQuery($sql);

			return $result;
	 } 	

	/*
	 *  GET EVENTS FOR RESULTS
	 */
	 public function getResultedEvents($table,$eventType=NULL){
			
			$today		= date('Y-m-d');

			$whereParts	= array('event_date <= "'.$today.'"');
			if ($eventType !== NULL && $eventType !== '') {
				$whereParts[] = 'event_type_id = '.$eventType;
			}
			
			$sql		= 'SELECT 
								id, event_name, is_registration, event_date, reg_frm_date, reg_to_date
						   FROM 
						   		'.$table.'
						   WHERE
						   		'.implode(' AND ', $whereParts);

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET EVENT RESULTS 
	 */
	 public function getEventResult($table,$eventId){
			
			$sql		= 'SELECT
								usr.id, usr.firstname, usr.lastname, cls.class_name, usr.admission_id, sec.section_name, evnt.event_name, evnt.event_desc, tb.award
							FROM
								".TB_USERS." usr, class cls, section sec, '.$table.' tb, events evnt
							WHERE
								tb.event_id = '.$eventId.'
							AND
								tb.user_id = usr.id
							AND
								usr.section = sec.id
							AND
								sec.class_id = cls.id
							AND
								tb.event_id = evnt.id';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 
	 
	/*
	 *  ANNOUNCE EVENT RESULT 
	 */
	 public function eventResult($table,$userDet,$eventId){
			
			$userId		= $userDet['user_id'];
			$award		= $userDet['award'];
			
			$userRes	= $this->eventResultCheck($table,$userId,$eventId);
			
			$result	= 0;
			
			if(empty($userRes)){
				
				$sql		= 'INSERT INTO '.$table.' ( event_id, user_id, award ) VALUES ( '.$eventId.', '.$userId.', "'.$award.'" )';
	
				$result		= $this->dbObj->executeQuery($sql);
			}
			
			return $result;
	 } 
	 
	/*
	 *  CHECK EVENT RESULT 
	 */
	 public function eventResultCheck($table,$userId,$eventId){
			
			$sql		= 'SELECT event_id, user_id, award FROM '.$table.' WHERE user_id = '.$userId.' AND event_id = '.$eventId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  DELETE EVENT
	 */
	 public function deleteEvent($table, $eventId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$eventId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  INSERT NEW SYLLABUS
	 */
 	 public function addSyllabus($table, $varArray){
 			
 			$classId		= $varArray['class_id'];
 			$sylName		= $varArray['syllabus_name'];
			$batchId		= isset($varArray['batch_id']) ? intval($varArray['batch_id']) : 0;

			$sqlQuery		= 'INSERT INTO '.$table.' ( syllabus_name, class_id, batch_id)
							 	VALUES ( "'.$sylName.'" , "'.$classId.'" , "'.$batchId.'" )';
 			
 			$result		= $this->dbObj->executeQuery($sqlQuery);
 			
 			return $result;
 	}

	/*
	 *  UPDATE SYLLABUS
	 */
 	 public function editSyllabus($table, $varArray){
 			
 			$sylId			= isset($varArray['syl_id']) ? intval($varArray['syl_id']) : 0;
 			$classId		= $varArray['class_id'];
 			$sylName		= $varArray['syllabus_name'];
			$batchId		= isset($varArray['batch_id']) ? intval($varArray['batch_id']) : 0;

 			if ($sylId > 0) {
				$sqlQuery		= 'UPDATE '.$table.' SET syllabus_name = "'.$sylName.'", class_id = "'.$classId.'", batch_id = "'.$batchId.'" WHERE id = '.$sylId;
 			} else {
 				// Legacy fallback (older callers only knew class_id).
				$sqlQuery		= 'UPDATE '.$table.' SET syllabus_name = "'.$sylName.'", batch_id = "'.$batchId.'" WHERE class_id = "'.$classId.'"';
 			}
 			
 			$result		= $this->dbObj->executeQuery($sqlQuery);
 			
 			return $result;
 	}

	/*
	 *  DELETE SYLLABUS
	 */
	 public function deleteSyllabus($table, $sylId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$sylId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET SYLLABUS FOR ID 
	 */
 	 public function getSyllabusById($table,$sylId){
 			
 			$sql		= 'SELECT
								id, syllabus_name, class_id, batch_id
 							FROM
 								'.$table.'
 							WHERE
 								id = '.$sylId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET SYLLABUS FOR CLASS 
	 */
	 public function getSyllabusForClass($table,$classId,$batchId = 0){
 			
 			$sql		= 'SELECT
								id, syllabus_name, batch_id
 							FROM
 								'.$table.'
 							WHERE
								class_id = '.$classId;
			if ($batchId > 0 && $this->tableHasColumn($table, 'batch_id')) {
				$sql .= ' AND batch_id = '.intval($batchId);
			}
			$sql .= ' ORDER BY id DESC';

 			$result		= $this->dbObj->getAllResults($sql);

 			return $result;
 	 } 	 
	 
	/*
	 *  GET SUBJECTS FOR CLASS 
	 */
	 public function getSubjectsForClass($table,$classId,$batchId = 0){
 			
 			$sql		= 'SELECT
								id, sub_name, sub_code, batch_id
 							FROM
 								'.$table.'
 							WHERE
								class_id = '.$classId;
			if ($batchId > 0 && $this->tableHasColumn($table, 'batch_id')) {
				$sql .= ' AND batch_id = '.intval($batchId);
			}
			$sql .= ' ORDER BY id ASC';

 			$result		= $this->dbObj->getAllResults($sql);

 			return $result;
 	 } 	 

	/*
	 *  GET SUBJECT BY ID
	 */
 	 public function getSubjectById($table,$subjId){
 			
 			$classTable = TB_CLASS;
 			$sql		= 'SELECT 
								tb.id, tb.sub_code, tb.sub_name, tb.batch_id, cls.class_name, cls.class_code, cls.id class_id
 						   FROM 
 						   		'.$table.' tb
 						   INNER JOIN
 						   		'.$classTable.' cls
						   ON
						   		tb.class_id = cls.id
						   WHERE
								tb.id = '.intval($subjId);

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 
	
	/*
	 *  Add SUBJECT
	 */
 	 public function addSubject($table,$varArray){

 			$clsId		= $varArray['class_id'];
			$batchId	= isset($varArray['batch_id']) ? intval($varArray['batch_id']) : 0;
 			$subCode	= $varArray['subj_code'];
 			$subName	= $varArray['subj_name'];
 			
			$sql		= 'INSERT INTO '.$table.' ( batch_id, class_id, sub_code, sub_name ) VALUES ( "'.$batchId.'", "'.$clsId.'", "'.$subCode.'", "'.$subName.'" ) ';
 			
 			$result		= $this->dbObj->executeQuery($sql);
 			
 			return $result;
 	 }	
	 
	/*
	 *  UPDATE SUBJECT
	 */
 	 public function editSubject($table, $varArray){
 			
 			$classId		= $varArray['class_id'];
 			$subjId			= $varArray['subj_id'];

 			$subjName		= $varArray['subj_name'];
 			$subjCode		= $varArray['subj_code'];
			$batchId		= isset($varArray['batch_id']) ? intval($varArray['batch_id']) : 0;

			$sqlQuery		= 'UPDATE '.$table.' SET batch_id = "'.$batchId.'", class_id = "'.$classId.'", sub_code = "'.$subjCode.'", sub_name = "'.$subjName.'" WHERE id = '.$subjId;
 			
 			$result		= $this->dbObj->executeQuery($sqlQuery);
 			
 			return $result;
 	}

	/*
	 *  DELETE SUBJECT
	 */
	 public function deleteSubject($table, $subjectId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$subjectId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET MATERIALS FOR SUBJECTS 
	 */
	 public function getMaterialsForSubj($table,$subjId){
			
			$sql		= 'SELECT
								id, material_name, mater_file
							FROM
								'.$table.'
							WHERE
								sub_id = '.$subjId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET MATERIALS 
	 */
	 public function getMaterialById($table,$materialId){
			
			$sql		= 'SELECT
								tb.id id, tb.material_name, tb.mater_file, cls.id class_id, sub.id subject_id, sub.sub_code
							FROM
								'.$table.' tb,
								 class cls,
								 subjects sub
							WHERE
								tb.id = '.$materialId.'
							AND
								tb.sub_id = sub.id
							AND
								sub.class_id = cls.id';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  INSERT NEW MATERIAL
	 */
	 public function addMaterial($table, $varArray){
			
			$classId		= $varArray['class_id'];
			$subjId			= $varArray['subj_id'];
			$materName		= $varArray['material_name'];
			$materFileName	= $varArray['material_file_name'];

			$sqlQuery		= 'INSERT INTO '.$table.' ( sub_id, material_name, mater_file)
							 	VALUES ( "'.$subjId.'" , "'.$materName.'", "'.$materFileName.'" )';
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  UPDATE MATERIAL
	 */
	 public function editMaterial($table, $varArray){
			
			$materialId		= $varArray['material_id'];
			
			$classId		= $varArray['class_id'];
			$subjId			= $varArray['subj_id'];
			$materName		= $varArray['material_name'];
			$materFileName	= $varArray['material_file_name'];

			$sqlQuery		= 'UPDATE '.$table.' SET sub_id = "'.$subjId.'" , material_name = "'.$materName.'" , mater_file = "'.$materFileName.'" WHERE id = '.$materialId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  DELETE MATERIAL
	 */
	 public function deleteMaterial($table, $materialId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$materialId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET PREVIOUS PAPERS FOR SUBJECTS 
	 */
	 public function getPrePapersForSubj($table,$subjId){
			
			$sql		= 'SELECT
								id, paper_name, paper_file
							FROM
								'.$table.'
							WHERE
								subj_id = '.$subjId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  INSERT NEW PAPER
	 */
	 public function addPaper($table, $varArray){
			
			$classId		= $varArray['class_id'];
			$subjId			= $varArray['subj_id'];
			$paperName		= $varArray['paper_name'];
			$paperFileName	= $varArray['paper_file_name'];

			$sqlQuery		= 'INSERT INTO '.$table.' ( subj_id, paper_name, paper_file)
							 	VALUES ( "'.$subjId.'" , "'.$paperName.'", "'.$paperFileName.'" )';
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET PAPER 
	 */
	 public function getPaperById($table,$paperId){
			
			$sql		= 'SELECT
								tb.id id, tb.paper_name	, tb.paper_file, cls.id class_id, sub.id subject_id, sub.sub_code
							FROM
								'.$table.' tb,
								 class cls,
								 subjects sub
							WHERE
								tb.id = '.$paperId.'
							AND
								tb.subj_id = sub.id
							AND
								sub.class_id = cls.id';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  UPDATE PAPER
	 */
	 public function editPaper($table, $varArray){
			
			$paperId		= $varArray['paper_id'];
			
			$classId		= $varArray['class_id'];
			$subjId			= $varArray['subj_id'];
			$paperName		= $varArray['paper_name'];
			$paperFileName	= $varArray['paper_file_name'];

			$sqlQuery		= 'UPDATE '.$table.' SET subj_id = "'.$subjId.'" , paper_name = "'.$paperName.'" , paper_file = "'.$paperFileName.'" WHERE id = '.$paperId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  DELETE PAPER
	 */
	 public function deletePaper($table, $paperId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$paperId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET ACHEIVEMENTS 
	 */
	 public function getAchievements($table, $categoty_id){
			
			$sql		= 'SELECT
								id, category_id, achievement_desc
							FROM
								'.$table.'
							WHERE
								category_id = '.$categoty_id;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  GET ACHIEVEMENTS FOR A STUDENT (by roll no tag in description)
	 */
	 public function getAchievementsForAdmission($table, $admissionId){
			$admissionId = trim((string)$admissionId);
			if ($admissionId === '') {
				return array();
			}

			$safeId = addslashes($admissionId);
			$needle = '%['.$safeId.']%';

			$sql = 'SELECT id, category_id, achievement_desc
					FROM '.$table.'
					WHERE achievement_desc LIKE "'.$needle.'"
					ORDER BY id DESC';

			return $this->dbObj->getAllResults($sql);
	 }

	/*
	 *  INSERT NEW ACHIEVEMENT
	 */
	 public function addAchievement($table, $varArray){
			
			$typeId			= $varArray['typeId'];
			$achieveDesc	= $varArray['achievement_desc'];

			$sqlQuery		= 'INSERT INTO '.$table.' ( category_id, achievement_desc )
							 	VALUES ( "'.$typeId.'" , "'.$achieveDesc.'" )';
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET ACHEIVEMENTS BY ID
	 */
	 public function getAchievementsByid($table, $id){
			
			$sql		= 'SELECT
								id, category_id, achievement_desc
							FROM
								'.$table.'
							WHERE
								id = '.$id;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  DELETE ACHIEVEMENT
	 */
	 public function deleteAchievement($table, $achieveId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$achieveId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET PLACEMENTS 
	 */
	 public function getPlacements($table, $categoty_id){
			
			$sql		= 'SELECT
								id, placement_desc
							FROM
								'.$table.'
							WHERE
								category_id = '.$categoty_id;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  INSERT NEW PLACEMENT
	 */
	 public function addPlacement($table, $varArray){
			
			$typeId			= $varArray['typeId'];
			$placementDesc	= $varArray['placement_desc'];

			$sqlQuery		= 'INSERT INTO '.$table.' ( category_id, placement_desc )
							 	VALUES ( "'.$typeId.'" , "'.$placementDesc.'" )';
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  DELETE PLACEMENT
	 */
	 public function deletePlacement($table, $placementId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$placementId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET ALUMNI
	 */
	 public function getAlumniDet($table, $categoty_id){
			
			$sql		= 'SELECT
								id, alumni_desc
							FROM
								'.$table.'
							WHERE
								category_id = '.$categoty_id;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 
	
	/*
	 *  GET ALUMNI BY BATCH
	 */
	 public function getAlumniDetails($table, $batchId){
			
			$sql		= 'SELECT
								id, alumni_desc, alumni_img
							FROM
								'.$table.'
							WHERE
								batch_id = '.$batchId;

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 

	/*
	 *  INSERT NEW ALUMNI
	 */
	 public function addAlumni($table, $varArray){
			
			$typeId			= $varArray['typeId'];
			$alumniDesc		= $varArray['alumni_desc'];

			$sqlQuery		= 'INSERT INTO '.$table.' ( category_id, alumni_desc )
							 	VALUES ( "'.$typeId.'" , "'.$alumniDesc.'" )';
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  INSERT NEW ALUMNI DETAILS
	 */
	 public function addAlumniDetails($table, $varArray){
			
			$batchId		= $varArray['typeId'];
			$alumniDesc		= $varArray['alumni_desc'];
			$alumniImage	= $varArray['image'];

			$sqlQuery		= 'INSERT INTO '.$table.' ( batch_id, alumni_desc, alumni_img )
							 	VALUES ( "'.$batchId.'" , "'.$alumniDesc.'" , "'.$alumniImage.'" )';
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  DELETE ALUMNI
	 */
	 public function deleteAlumni($table, $alumniId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$alumniId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET COMMENTS 
	 */
	 public function getComment($table, $type){
			
			$sql		= 'SELECT
								id, name, qualification, designation, comment, image
							FROM
								'.$table.'
							WHERE
								type = "'.$type.'"';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 	 
	
	/*
	 *  GET USERS BY SECTION ID 
	 */
	 public function getUsersBySecId($table, $sectionId){
			
			$sqlQuery	= 'SELECT id, username, password, firstname, mail_id, admission_id, image, status FROM '.$table.' WHERE section = "'.$sectionId.'" AND status = 1';

			$result		= $this->dbObj->getAllResults($sqlQuery);
			
			return $result;
	}
	
	
	/*
	 *  ASSIGN USERS AS WISE COMMITTEE MEMBERS
	 */
	 public function addCommitteeMember($table, $varArray){
			$this->ensureCommitteeMemberColumns($table);
			
			$cmtCatId		= (int)$varArray['committee_cat_id'];
			$userId			= isset($varArray['user_id']) ? (int)$varArray['user_id'] : 0;
			$memberName		= isset($varArray['member_name']) ? addslashes((string)$varArray['member_name']) : '';
			$memberAbout	= isset($varArray['member_about']) ? addslashes((string)$varArray['member_about']) : '';
			$memberImage	= isset($varArray['member_image']) ? addslashes((string)$varArray['member_image']) : '';
			
			$msg	= '';
			
			$isCommitMem	= $this->getCmtMembers($table,$cmtCatId);
			
			if( !empty( $isCommitMem ) ){

				$sql		= 'UPDATE '.$table.' 
							SET user_id = '.$userId.',
								member_name = "'.$memberName.'",
								member_about = "'.$memberAbout.'",
								member_image = "'.$memberImage.'"
							WHERE committee_cat_id = '.$cmtCatId;

				$addCmtMem	= $this->dbObj->executeQuery($sql);

				if( $addCmtMem	){
					$msg		= 'Successfully Added';
				}else{
					$msg		= 'Sorry, Please Try Again';
				}
			}else{
				
				$sql		= 'INSERT INTO '.$table.' ( committee_cat_id , user_id, member_name, member_about, member_image ) 
							values ('.$cmtCatId.' , '.$userId.', "'.$memberName.'", "'.$memberAbout.'", "'.$memberImage.'" )';
					
				$addCmtMem	= $this->dbObj->executeQuery($sql);
				
				if( $addCmtMem	){
					$msg		= 'Successfully Added';
				}else{
					$msg		= 'Sorry, Please Try Again';
				}
			}
			
			return $msg;
	}
	
	/*
	 *  GET EVENT TYPES
	 */
	 public function getEventTypes($table){
			
			$sqlQuery	= 'SELECT id, event_type FROM '.$table;

			$result		= $this->dbObj->getAllResults($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET OR CREATE EVENT TYPE BY NAME
	 */
	 public function getOrCreateEventTypeId($table, $eventType){
			$eventType = trim((string)$eventType);
			if ($eventType === '') {
				return 0;
			}

			$safeType = addslashes($eventType);

			$existing = $this->dbObj->getAllResults(
				'SELECT id FROM '.$table.' WHERE LOWER(event_type) = LOWER("'.$safeType.'") LIMIT 1'
			);

			if (!empty($existing)) {
				return (int)$existing[0]['id'];
			}

			$inserted = $this->dbObj->executeQuery(
				'INSERT INTO '.$table.' (event_type) VALUES ("'.$safeType.'")'
			);

			if ($inserted) {
				return (int)$this->dbObj->getLastInsertId();
			}

			return 0;
	 }
	
	/*
	 *  GET NEW EVENT
	 */
	 public function addNewEvent($table, $varArray){
			
			$eventTypeId		= $varArray['event_type_id'];
			$eventName			= $varArray['event_name'];
			$eventDesc			= $varArray['event_desc'];
			$eventAddress		= $varArray['event_address'];
			$eventDate			= $varArray['event_date'];
			$eventRegStartDate	= $varArray['reg_frm_date'];
			$eventRegEndDate	= $varArray['reg_to_date'];
			$isReg				= $varArray['is_registration'];
			
			$sqlQuery	= 'INSERT INTO '.$table.' ( event_type_id, event_name, event_desc, event_address, event_date, reg_frm_date, reg_to_date , is_registration)
							 VALUES ( "'.$eventTypeId.'" , "'.$eventName.'" , "'.$eventDesc.'" , "'.$eventAddress.'", "'.$eventDate.'", "'.$eventRegStartDate.'", "'.$eventRegEndDate.'", "'.$isReg.'" )';
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}
	
	/*
	 *  CHECK COMMENT IS EXIST 
	 */
	 public function checkComments($table,$comType){
			
			
			$sql		= 'SELECT id, name, qualification, designation, comment 
							FROM '.$table.' 
							WHERE type = "'.$comType.'"';

			$result		= $this->dbObj->getAllResults($sql);

			return $result;
	 } 

	/*
	 *  CHANGE COMMENTS 
	 */
	 public function changeComments($table,$varArray){
			
			$comType	= $varArray['comType'];
			$comName	= $varArray['comName'];
			$comQualif	= $varArray['comQualif'];
			$comDesig	= $varArray['comDesig'];
			$comComment	= $varArray['comComment'];
			
			$image		= $varArray['image'];
			
			$checkCmt	= $this->checkComments ( $table, $comType );
			
			if( !empty( $checkCmt ) ){
				$sql		= 'UPDATE '.$table.' SET name = "'.$comName.'", qualification = "'.$comQualif.'", designation = "'.$comDesig.'", comment = "'.$comComment.'", image =  "'.$image.'" WHERE type = "'.$comType.'"';
			}else{
				$sql		= 'INSERT INTO '.$table.' (name , type, qualification , designation, comment, image ) VALUES("'.$comName.'", "'.$comType.'", "'.$comQualif.'", "'.$comDesig.'", "'.$comComment.'", "'.$image.'" )';
			}

			$result		= $this->dbObj->executeQuery($sql);

			return $result;
	 } 
	 
	/*
	 *  GET HIGH LIGHTS
	 */
	 public function getHighLights($table, $type){
			
			$sqlQuery	= 'SELECT id, type, high_light FROM '.$table.' WHERE type = '.$type.' ORDER BY id DESC';

			$result		= $this->dbObj->getAllResults($sqlQuery);
			
			return $result;
	}
	
	/*
	 *  ADD HIGH LIGHTS
	 */
	 public function addHighLight($table, $varArray){
			
			$typeId		= $varArray['typeId'];
			$highLight	= $varArray['highLight'];
			
			$sqlQuery	= 'INSERT INTO '.$table.' ( type, high_light ) VALUES ('.$typeId.', "'.$highLight.'")';

			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  DELETE HIGH LIGHT
	 */
	 public function deleteHighLight($table, $highLightId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$highLightId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}


	/*
	 *  GALLERY CATEGORIES
	 */
	 public function getGalleryCategories($table, $activeOnly = false){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = 'SELECT id, category_name, linked_event_id, sort_order, is_active
						FROM `'.$table.'`';
			if ($activeOnly) {
				$sqlQuery .= ' WHERE is_active = 1';
			}
			$sqlQuery .= ' ORDER BY sort_order ASC, category_name ASC';
			return $this->dbObj->getAllResults($sqlQuery);
	}

	 public function getGalleryCategoryById($table, $categoryId){
			$table = $this->assertSafeIdentifier($table);
			$categoryId = (int)$categoryId;
			if ($categoryId <= 0) {
				return array();
			}
			$sqlQuery = 'SELECT id, category_name, category_name AS event_name, linked_event_id, sort_order, is_active
						FROM `'.$table.'`
						WHERE id = :category_id
						LIMIT 1';
			$result = $this->dbObj->getAllPrepared($sqlQuery, array(':category_id' => $categoryId));
			return $result;
	}

	 public function addGalleryCategory($table, $categoryName, $linkedEventId = null, $sortOrder = 0, $isActive = 1){
			$table = $this->assertSafeIdentifier($table);
			$categoryName = trim((string)$categoryName);
			$sortOrder = (int)$sortOrder;
			$isActive = (int)$isActive === 0 ? 0 : 1;
			$linkedEventValue = ($linkedEventId === '' || $linkedEventId === null) ? null : (int)$linkedEventId;

			if ($categoryName === '') {
				return false;
			}

			$duplicate = $this->dbObj->getOnePrepared(
				'SELECT id FROM `'.$table.'` WHERE LOWER(category_name) = LOWER(:category_name) LIMIT 1',
				array(':category_name' => $categoryName)
			);
			if (!empty($duplicate)) {
				return 0;
			}

			$result = $this->dbObj->executePrepared(
				'INSERT INTO `'.$table.'` (category_name, linked_event_id, sort_order, is_active)
				 VALUES (:category_name, :linked_event_id, :sort_order, :is_active)',
				array(
					':category_name' => $categoryName,
					':linked_event_id' => $linkedEventValue,
					':sort_order' => $sortOrder,
					':is_active' => $isActive
				)
			);
			if (!$result) {
				return false;
			}
			return (int)$this->dbObj->getLastInsertId();
	}

	 public function updateGalleryCategory($table, $categoryId, $categoryName, $linkedEventId = null, $sortOrder = 0, $isActive = 1){
			$table = $this->assertSafeIdentifier($table);
			$categoryId = (int)$categoryId;
			$categoryName = trim((string)$categoryName);
			$sortOrder = (int)$sortOrder;
			$isActive = (int)$isActive === 0 ? 0 : 1;
			$linkedEventValue = ($linkedEventId === '' || $linkedEventId === null) ? null : (int)$linkedEventId;

			if ($categoryId <= 0 || $categoryName === '') {
				return false;
			}

			$duplicate = $this->dbObj->getOnePrepared(
				'SELECT id FROM `'.$table.'`
				 WHERE LOWER(category_name) = LOWER(:category_name) AND id != :category_id
				 LIMIT 1',
				array(
					':category_name' => $categoryName,
					':category_id' => $categoryId
				)
			);
			if (!empty($duplicate)) {
				return 0;
			}

			return $this->dbObj->executePrepared(
				'UPDATE `'.$table.'`
				 SET category_name = :category_name,
					 linked_event_id = :linked_event_id,
					 sort_order = :sort_order,
					 is_active = :is_active
				 WHERE id = :category_id',
				array(
					':category_name' => $categoryName,
					':linked_event_id' => $linkedEventValue,
					':sort_order' => $sortOrder,
					':is_active' => $isActive,
					':category_id' => $categoryId
				)
			);
	}

	 public function deleteGalleryCategory($table, $categoryId){
			$table = $this->assertSafeIdentifier($table);
			$categoryId = (int)$categoryId;
			if ($categoryId <= 0) {
				return false;
			}
			return $this->dbObj->executePrepared(
				'DELETE FROM `'.$table.'` WHERE id = :category_id',
				array(':category_id' => $categoryId)
			);
	}

	 public function countGalleryImagesByCategory($table, $categoryId){
			$table = $this->assertSafeIdentifier($table);
			$categoryId = (int)$categoryId;
			$result = $this->dbObj->getOnePrepared(
				'SELECT COUNT(*) AS total_rows FROM `'.$table.'` WHERE category_id = :category_id',
				array(':category_id' => $categoryId)
			);
			return !empty($result) ? (int)$result['total_rows'] : 0;
	}

	/*
	 *  EVENTS FOR GALLERY
	 */
	 public function getEventGallery($table){
			$table = $this->assertSafeIdentifier($table);
			$sqlQuery = 'SELECT c.id, c.category_name AS event_name, c.linked_event_id, c.sort_order, c.is_active,
								COUNT(g.id) AS image_count
						FROM `'.TB_GALLERY_CATEGORY.'` c
						LEFT JOIN `'.$table.'` g ON g.category_id = c.id
						WHERE c.is_active = 1
						GROUP BY c.id, c.category_name, c.linked_event_id, c.sort_order, c.is_active
						HAVING COUNT(g.id) > 0
						ORDER BY c.sort_order ASC, c.category_name ASC';
			return $this->dbObj->getAllResults($sqlQuery);
	}
	 	
	/*
	 *  GET IMAGES FOR EVENT
	 */
	 public function getImagesForEvents($table,$eventId){
			$table = $this->assertSafeIdentifier($table);
			$eventId = (int)$eventId;
			$sqlQuery = 'SELECT tb.name, tb.id, tb.description, tb.image_name, tb.category_id, tb.event_id
						FROM `'.$table.'` tb
						WHERE tb.category_id = :category_id
						ORDER BY tb.id DESC';
			return $this->dbObj->getAllPrepared($sqlQuery, array(':category_id' => $eventId));
	}

	/*
	 *  ADD GALLERY 
	 */
	 public function addGallery($table,$varArray){
			$table = $this->assertSafeIdentifier($table);
			$categoryId = isset($varArray['category_id']) ? (int)$varArray['category_id'] : 0;
			$eventId = isset($varArray['event_id']) ? (int)$varArray['event_id'] : 0;
			$imgName = isset($varArray['image_name']) ? (string)$varArray['image_name'] : '';
			$imgDesc = isset($varArray['image_desc']) ? (string)$varArray['image_desc'] : '';
			$imgLink = isset($varArray['image']) ? (string)$varArray['image'] : '';

			if ($categoryId > 0) {
				$category = $this->dbObj->getOnePrepared(
					'SELECT linked_event_id FROM `'.TB_GALLERY_CATEGORY.'` WHERE id = :category_id LIMIT 1',
					array(':category_id' => $categoryId)
				);
				if (!empty($category) && $category['linked_event_id'] !== null) {
					$eventId = (int)$category['linked_event_id'];
				}
			}

			$sql = "INSERT INTO `".$table."` (category_id, event_id, name, description, image_name) VALUES (:category_id, :event_id, :name, :description, :image_name)";
			$result = $this->dbObj->executePrepared($sql, array(
				':category_id' => $categoryId,
				':event_id' => $eventId,
				':name' => $imgName,
				':description' => $imgDesc,
				':image_name' => $imgLink
			));
			
			return $result;
	 }	 	
	 
	/*
	 *  DELETE GALLERY
	 */
	 public function deleteGallery($table, $imageId){
			
			$sqlQuery	= 'DELETE FROM '.$table.' WHERE id = '.$imageId;
			
			$result		= $this->dbObj->executeQuery($sqlQuery);
			
			return $result;
	}

	/*
	 *  GET OR CREATE GALLERY CATEGORY BY NAME
	 */
	 public function getOrCreateGalleryCategoryId($table, $categoryName){
			$table = $this->assertSafeIdentifier($table);
			$categoryName = trim((string)$categoryName);
			if ($categoryName === '') {
				return 0;
			}

			$existing = $this->dbObj->getOnePrepared(
				'SELECT id FROM `'.$table.'` WHERE LOWER(category_name) = LOWER(:category_name) LIMIT 1',
				array(':category_name' => $categoryName)
			);

			if (!empty($existing) && isset($existing['id'])) {
				return (int)$existing['id'];
			}

			$created = $this->addGalleryCategory($table, $categoryName, null, 0, 1);
			if ($created === false) {
				return 0;
			}

			if ($created > 0) {
				return (int)$created;
			}

			$existingAfter = $this->dbObj->getOnePrepared(
				'SELECT id FROM `'.$table.'` WHERE LOWER(category_name) = LOWER(:category_name) LIMIT 1',
				array(':category_name' => $categoryName)
			);

			return (!empty($existingAfter) && isset($existingAfter['id'])) ? (int)$existingAfter['id'] : 0;
	 }

	





    /*
     *  ENSURE SUPPORT SETTINGS TABLE
     */
    private function ensureSupportSettingsTable($table = TB_SUPPORT_SETTINGS){
        $table = trim((string)$table);
        if ($table === '') {
            $table = TB_SUPPORT_SETTINGS;
        }

        $createSql = "CREATE TABLE IF NOT EXISTS `".$table."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `support_email` varchar(255) NOT NULL DEFAULT '',
                        `whatsapp_number` varchar(30) NOT NULL DEFAULT '',
                        `smtp_host` varchar(255) NOT NULL DEFAULT '',
                        `smtp_port` int(11) NOT NULL DEFAULT 587,
                        `smtp_secure` varchar(10) NOT NULL DEFAULT 'tls',
                        `smtp_username` varchar(255) NOT NULL DEFAULT '',
                        `smtp_password` varchar(255) NOT NULL DEFAULT '',
                        `smtp_from_email` varchar(255) NOT NULL DEFAULT '',
                        `smtp_from_name` varchar(255) NOT NULL DEFAULT '',
                        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        PRIMARY KEY (`id`)
                     ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        $this->dbObj->executeQuery($createSql);
        $this->ensureSupportSettingsColumn($table, 'smtp_host', "varchar(255) NOT NULL DEFAULT ''");
        $this->ensureSupportSettingsColumn($table, 'smtp_port', "int(11) NOT NULL DEFAULT 587");
        $this->ensureSupportSettingsColumn($table, 'smtp_secure', "varchar(10) NOT NULL DEFAULT 'tls'");
        $this->ensureSupportSettingsColumn($table, 'smtp_username', "varchar(255) NOT NULL DEFAULT ''");
        $this->ensureSupportSettingsColumn($table, 'smtp_password', "varchar(255) NOT NULL DEFAULT ''");
        $this->ensureSupportSettingsColumn($table, 'smtp_from_email', "varchar(255) NOT NULL DEFAULT ''");
        $this->ensureSupportSettingsColumn($table, 'smtp_from_name', "varchar(255) NOT NULL DEFAULT ''");
    }

    private function ensureSupportSettingsColumn($table, $columnName, $definition){
        $table = addslashes((string)$table);
        $columnName = addslashes((string)$columnName);
        $definition = trim((string)$definition);

        $checkSql = "SELECT COUNT(*) AS cnt
                     FROM information_schema.COLUMNS
                     WHERE TABLE_SCHEMA = DATABASE()
                       AND TABLE_NAME = '".$table."'
                       AND COLUMN_NAME = '".$columnName."'";

        $result = $this->dbObj->getAllResults($checkSql);
        $exists = (!empty($result) && (int)$result[0]['cnt'] > 0);
        if (!$exists) {
            $alterSql = "ALTER TABLE `".$table."` ADD COLUMN `".$columnName."` ".$definition;
            $this->dbObj->executeQuery($alterSql);
        }
    }

    /*
     *  GET SUPPORT SETTINGS
     */
    public function getSupportSettings($table = TB_SUPPORT_SETTINGS){
        $table = $this->assertSafeIdentifier($table);
        $this->ensureSupportSettingsTable($table);

        $sqlQuery = "SELECT id, support_email, whatsapp_number,
                            smtp_host, smtp_port, smtp_secure, smtp_username, smtp_password, smtp_from_email, smtp_from_name
                     FROM ".$table."
                     ORDER BY id ASC
                     LIMIT 1";

        $result = $this->dbObj->getAllResults($sqlQuery);
        if (!empty($result)) {
            return $result[0];
        }

        $insertSql = "INSERT INTO `".$table."` (support_email, whatsapp_number) VALUES ('', '')";
        $this->dbObj->executeQuery($insertSql);

        $result = $this->dbObj->getAllResults($sqlQuery);
        if (!empty($result)) {
            return $result[0];
        }

        return array(
            'id' => 0,
            'support_email' => '',
            'whatsapp_number' => '',
            'smtp_host' => '',
            'smtp_port' => 587,
            'smtp_secure' => 'tls',
            'smtp_username' => '',
            'smtp_password' => '',
            'smtp_from_email' => '',
            'smtp_from_name' => ''
        );
    }

    /*
     *  UPDATE SUPPORT SETTINGS
     */
    public function updateSupportSettings($table, $supportEmail, $whatsappNumber, $smtpConfig = array()){
        $table = $this->assertSafeIdentifier($table);
        $this->ensureSupportSettingsTable($table);

        $settings = $this->getSupportSettings($table);
        $settingsId = isset($settings['id']) ? (int)$settings['id'] : 0;

        $supportEmail = trim((string)$supportEmail);
        $whatsappNumber = trim((string)$whatsappNumber);
        $smtpHost = trim((string)($smtpConfig['smtp_host'] ?? ''));
        $smtpPort = (int)($smtpConfig['smtp_port'] ?? 587);
        if ($smtpPort <= 0) {
            $smtpPort = 587;
        }
        $smtpSecure = strtolower(trim((string)($smtpConfig['smtp_secure'] ?? 'tls')));
        if (!in_array($smtpSecure, array('none', 'ssl', 'tls'), true)) {
            $smtpSecure = 'tls';
        }
        $smtpUsername = trim((string)($smtpConfig['smtp_username'] ?? ''));
        $smtpPassword = trim((string)($smtpConfig['smtp_password'] ?? ''));
        $smtpFromEmail = trim((string)($smtpConfig['smtp_from_email'] ?? ''));
        $smtpFromName = trim((string)($smtpConfig['smtp_from_name'] ?? ''));

        if ($settingsId > 0) {
            $sqlQuery = "UPDATE `".$table."`
                         SET support_email = :support_email,
                             whatsapp_number = :whatsapp_number,
                             smtp_host = :smtp_host,
                             smtp_port = :smtp_port,
                             smtp_secure = :smtp_secure,
                             smtp_username = :smtp_username,
                             smtp_password = :smtp_password,
                             smtp_from_email = :smtp_from_email,
                             smtp_from_name = :smtp_from_name
                         WHERE id = :settings_id";
        } else {
            $sqlQuery = "INSERT INTO `".$table."` (support_email, whatsapp_number, smtp_host, smtp_port, smtp_secure, smtp_username, smtp_password, smtp_from_email, smtp_from_name)
                         VALUES (:support_email, :whatsapp_number, :smtp_host, :smtp_port, :smtp_secure, :smtp_username, :smtp_password, :smtp_from_email, :smtp_from_name)";
        }

        $params = array(
            ':support_email' => $supportEmail,
            ':whatsapp_number' => $whatsappNumber,
            ':smtp_host' => $smtpHost,
            ':smtp_port' => $smtpPort,
            ':smtp_secure' => $smtpSecure,
            ':smtp_username' => $smtpUsername,
            ':smtp_password' => $smtpPassword,
            ':smtp_from_email' => $smtpFromEmail,
            ':smtp_from_name' => $smtpFromName
        );

        if ($settingsId > 0) {
            $params[':settings_id'] = $settingsId;
        }

        return $this->dbObj->executePrepared($sqlQuery, $params);
    }

    /*
     *  PASSWORD RESET TOKENS
     */
    private function ensurePasswordResetsTable($table = TB_PASSWORD_RESETS){
        $table = trim((string)$table);
        if ($table === '') {
            $table = TB_PASSWORD_RESETS;
        }

        $createSql = "CREATE TABLE IF NOT EXISTS `".$table."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `user_id` int(11) NOT NULL,
                        `token_hash` char(64) NOT NULL,
                        `expires_at` timestamp NOT NULL,
                        `used_at` timestamp NULL DEFAULT NULL,
                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                        `ip` varchar(45) NOT NULL DEFAULT '',
                        `user_agent` varchar(255) NOT NULL DEFAULT '',
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `uniq_token_hash` (`token_hash`),
                        KEY `idx_user_id` (`user_id`),
                        KEY `idx_expires_at` (`expires_at`)
                     ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

        try {
            $this->dbObj->executeQuery($createSql);
        } catch (Exception $e) {
            // Keep runtime stable if permissions/schema differ; callers handle failures gracefully.
        }
    }

    public function findUserByLoginIdentifier($table, $identifier){
        $table = $this->assertSafeIdentifier($table);
        $identifier = trim((string)$identifier);
        if ($identifier === '') {
            return array();
        }

        $sqlQuery = "SELECT id, username, mail_id, admission_id, status
                     FROM `".$table."`
                     WHERE username = :identifier
                        OR mail_id = :identifier
                        OR admission_id = :identifier
                     LIMIT 1";

        $row = $this->dbObj->getAllPrepared($sqlQuery, array(':identifier' => $identifier));
        return $row;
    }

    public function createPasswordReset($table, $userId, $tokenHash, $expiresAtUtc, $ip = '', $userAgent = ''){
        $table = $this->assertSafeIdentifier($table);
        $this->ensurePasswordResetsTable($table);

        $userId = (int)$userId;
        $tokenHash = trim((string)$tokenHash);
        $expiresAtUtc = trim((string)$expiresAtUtc);
        $ip = trim((string)$ip);
        $userAgent = trim((string)$userAgent);
        if ($userId <= 0 || $tokenHash === '' || $expiresAtUtc === '') {
            return false;
        }

        $sqlQuery = "INSERT INTO `".$table."` (user_id, token_hash, expires_at, ip, user_agent)
                     VALUES (:user_id, :token_hash, :expires_at, :ip, :user_agent)";

        return $this->dbObj->executePrepared($sqlQuery, array(
            ':user_id' => $userId,
            ':token_hash' => $tokenHash,
            ':expires_at' => $expiresAtUtc,
            ':ip' => $ip,
            ':user_agent' => substr($userAgent, 0, 255)
        ));
    }

    public function getValidPasswordResetByTokenHash($table, $tokenHash){
        $table = $this->assertSafeIdentifier($table);
        $this->ensurePasswordResetsTable($table);

        $tokenHash = trim((string)$tokenHash);
        if ($tokenHash === '') {
            return array();
        }

        $sqlQuery = "SELECT pr.id AS reset_id, pr.user_id, pr.expires_at, pr.used_at,
                            u.username, u.mail_id, u.status
                     FROM `".$table."` pr
                     JOIN `".TB_USERS."` u ON u.id = pr.user_id
                     WHERE pr.token_hash = :token_hash
                       AND pr.used_at IS NULL
                       AND pr.expires_at > UTC_TIMESTAMP()
                     LIMIT 1";

        return $this->dbObj->getAllPrepared($sqlQuery, array(':token_hash' => $tokenHash));
    }

    public function markPasswordResetUsed($table, $resetId){
        $table = $this->assertSafeIdentifier($table);
        $this->ensurePasswordResetsTable($table);

        $resetId = (int)$resetId;
        if ($resetId <= 0) {
            return false;
        }

        $sqlQuery = "UPDATE `".$table."` SET used_at = UTC_TIMESTAMP() WHERE id = :id";
        return $this->dbObj->executePrepared($sqlQuery, array(':id' => $resetId));
    }

    /*
     *  ADMIN: USER MANAGEMENT (SAFE QUERIES)
     */
    public function adminGetUsersList($tableUsers, $filters = array()){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $usersAlumniReady = $this->tableHasColumn($tableUsers, 'is_alumni');
        $usersUserTypeReady = $this->tableHasColumn($tableUsers, 'user_type');

        $where = array();
        $params = array();

        if (isset($filters['status']) && $filters['status'] !== '' && $filters['status'] !== null) {
            $where[] = 'u.status = :status';
            $params[':status'] = (int)$filters['status'];
        }

        if (!empty($filters['batch_id'])) {
            $where[] = 'u.batch_id = :batch_id';
            $params[':batch_id'] = (int)$filters['batch_id'];
        }

        if (!empty($filters['section_id'])) {
            $where[] = 'u.section = :section_id';
            $params[':section_id'] = (int)$filters['section_id'];
        }

        if (!empty($filters['class_id'])) {
            $where[] = 'sec.class_id = :class_id';
            $params[':class_id'] = (int)$filters['class_id'];
        }

        $typeFilter = strtolower(trim((string)($filters['type'] ?? '')));
        if ($typeFilter === 'student' || $typeFilter === 'students') {
            $filters['is_alumni'] = 0;
        } elseif ($typeFilter === 'alumni') {
            $filters['is_alumni'] = 1;
        }

        if (isset($filters['is_alumni']) && $filters['is_alumni'] !== '' && $filters['is_alumni'] !== null) {
            $wantAlumni = ((int)$filters['is_alumni'] === 1);
            if ($usersAlumniReady && $usersUserTypeReady) {
                $where[] = $wantAlumni
                    ? "(COALESCE(u.is_alumni, 0) = 1 OR COALESCE(u.user_type, 'student') = 'alumni')"
                    : "(COALESCE(u.is_alumni, 0) = 0 AND COALESCE(u.user_type, 'student') <> 'alumni')";
            } elseif ($usersAlumniReady) {
                $where[] = 'u.is_alumni = :is_alumni';
                $params[':is_alumni'] = $wantAlumni ? 1 : 0;
            } elseif ($usersUserTypeReady) {
                $where[] = 'u.user_type = :user_type';
                $params[':user_type'] = $wantAlumni ? 'alumni' : 'student';
            }
        }

        $q = trim((string)($filters['q'] ?? ''));
        if ($q !== '') {
            $where[] = '(u.username LIKE :q OR u.mail_id LIKE :q OR u.admission_id LIKE :q)';
            $params[':q'] = '%' . $q . '%';
        }

        $whereSql = '';
        if (!empty($where)) {
            $whereSql = 'WHERE ' . implode(' AND ', $where);
        }

        $limit = isset($filters['limit']) ? (int)$filters['limit'] : 100;
        if ($limit <= 0 || $limit > 500) {
            $limit = 100;
        }
        $offset = isset($filters['offset']) ? (int)$filters['offset'] : 0;
        if ($offset < 0) {
            $offset = 0;
        }

        $isAlumniExpr = $usersAlumniReady
            ? ($usersUserTypeReady ? "(CASE WHEN COALESCE(u.is_alumni, 0) = 1 OR u.user_type = 'alumni' THEN 1 ELSE 0 END)" : "COALESCE(u.is_alumni, 0)")
            : ($usersUserTypeReady ? "(CASE WHEN u.user_type = 'alumni' THEN 1 ELSE 0 END)" : "0");
        $userTypeSelect = $usersUserTypeReady ? "u.user_type" : "'student'";
        $passoutYearSelect = $this->tableHasColumn($tableUsers, 'passout_year') ? "u.passout_year" : "NULL";
        $hasOrigSectionId = $this->tableHasColumn($tableUsers, 'alumni_original_section_id');
        $origSectionIdSelect = $hasOrigSectionId ? "u.alumni_original_section_id" : "NULL";
        $origSectionLabelSelect = $this->tableHasColumn($tableUsers, 'alumni_original_section_label') ? "u.alumni_original_section_label" : "NULL";
        $origSectionNameSelect = $hasOrigSectionId ? "orig_sec.section_name" : "NULL";
        $origSectionCodeSelect = $hasOrigSectionId ? "orig_sec.section_code" : "NULL";
        $origClassNameSelect = $hasOrigSectionId ? "orig_cls.class_name" : "NULL";
        $origJoinSql = $hasOrigSectionId
            ? "LEFT JOIN `".TB_SECTION."` orig_sec ON orig_sec.id = u.alumni_original_section_id
                     LEFT JOIN `".TB_CLASS."` orig_cls ON orig_cls.id = orig_sec.class_id"
            : "";

        $sqlQuery = "SELECT u.id, u.username, u.firstname, u.lastname, u.mail_id, u.admission_id,
                            u.batch_id, b.batch AS batch_name,
                            u.section AS section_id, sec.section_name, sec.section_code,
                            sec.class_id, cls.class_name,
                            u.status,
                            ".$isAlumniExpr." AS is_alumni,
                            ".$userTypeSelect." AS user_type,
                            ".$passoutYearSelect." AS passout_year,
                            ".$origSectionIdSelect." AS original_section_id,
                            ".$origSectionLabelSelect." AS original_section_label,
                            ".$origSectionNameSelect." AS original_section_name,
                            ".$origSectionCodeSelect." AS original_section_code,
                            ".$origClassNameSelect." AS original_class_name
                     FROM `".$tableUsers."` u
                     LEFT JOIN `".TB_BATCH."` b ON b.id = u.batch_id
                     LEFT JOIN `".TB_SECTION."` sec ON sec.id = u.section
                     LEFT JOIN `".TB_CLASS."` cls ON cls.id = sec.class_id
                     ".$origJoinSql."
                     ".$whereSql."
                     ORDER BY u.id DESC
                     LIMIT ".$limit." OFFSET ".$offset;

        return $this->dbObj->getAllPrepared($sqlQuery, $params);
    }

    public function adminGetUserById($tableUsers, $userId){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        if ($userId <= 0) {
            return array();
        }

        $isAlumniExpr = $this->tableHasColumn($tableUsers, 'is_alumni') ? "COALESCE(u.is_alumni, 0)" : "0";
        $userTypeSelect = $this->tableHasColumn($tableUsers, 'user_type') ? "u.user_type" : "'student'";
        $passoutYearSelect = $this->tableHasColumn($tableUsers, 'passout_year') ? "u.passout_year" : "NULL";
        $hasOrigSectionId = $this->tableHasColumn($tableUsers, 'alumni_original_section_id');
        $origSectionNameSelect = $hasOrigSectionId ? "orig_sec.section_name" : "NULL";
        $origClassNameSelect = $hasOrigSectionId ? "orig_cls.class_name" : "NULL";
        $origJoinSql = $hasOrigSectionId
            ? "LEFT JOIN `".TB_SECTION."` orig_sec ON orig_sec.id = u.alumni_original_section_id
                     LEFT JOIN `".TB_CLASS."` orig_cls ON orig_cls.id = orig_sec.class_id"
            : "";

        $sqlQuery = "SELECT u.id, u.username, u.firstname, u.lastname, u.mail_id, u.admission_id,
                            u.batch_id, b.batch AS batch_name,
                            u.section AS section_id, sec.section_name, sec.section_code,
                            sec.class_id, cls.class_name,
                            u.status,
                            ".$isAlumniExpr." AS is_alumni,
                            ".$userTypeSelect." AS user_type,
                            ".$passoutYearSelect." AS passout_year,
                            ".$origSectionNameSelect." AS original_section_name,
                            ".$origClassNameSelect." AS original_class_name
                     FROM `".$tableUsers."` u
                     LEFT JOIN `".TB_BATCH."` b ON b.id = u.batch_id
                     LEFT JOIN `".TB_SECTION."` sec ON sec.id = u.section
                     LEFT JOIN `".TB_CLASS."` cls ON cls.id = sec.class_id
                     ".$origJoinSql."
                     WHERE u.id = :id
                     LIMIT 1";

        return $this->dbObj->getAllPrepared($sqlQuery, array(':id' => $userId));
    }

    public function adminUpdateUserStatus($tableUsers, $userId, $status){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        $status = (int)$status;
        if ($userId <= 0) {
            return false;
        }
        $sqlQuery = "UPDATE `".$tableUsers."` SET status = :status WHERE id = :id";
        return $this->dbObj->executePrepared($sqlQuery, array(':status' => $status, ':id' => $userId));
    }

    public function adminUpdateUserAcademic($tableUsers, $userId, $batchId, $sectionId){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        $batchId = (int)$batchId;
        $sectionId = (int)$sectionId;
        if ($userId <= 0 || $batchId <= 0 || $sectionId <= 0) {
            return false;
        }

        $sectionRow = $this->dbObj->getAllPrepared(
            "SELECT id FROM `".TB_SECTION."` WHERE id = :id AND batch_id = :batch_id LIMIT 1",
            array(':id' => $sectionId, ':batch_id' => $batchId)
        );
        if (empty($sectionRow)) {
            return false;
        }

        $sqlQuery = "UPDATE `".$tableUsers."` SET batch_id = :batch_id, section = :section_id WHERE id = :id";
        return $this->dbObj->executePrepared($sqlQuery, array(
            ':batch_id' => $batchId,
            ':section_id' => (string)$sectionId,
            ':id' => $userId
        ));
    }

    public function adminUpdateUserPasswordById($tableUsers, $userId, $passwordHash){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        $passwordHash = (string)$passwordHash;
        if ($userId <= 0 || $passwordHash === '') {
            return false;
        }
        $sqlQuery = "UPDATE `".$tableUsers."` SET password = :password WHERE id = :id";
        return $this->dbObj->executePrepared($sqlQuery, array(':password' => $passwordHash, ':id' => $userId));
    }

    public function isUserAlumni($tableUsers, $userId){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        if ($userId <= 0 || !$this->tableHasColumn($tableUsers, 'is_alumni')) {
            return false;
        }

        $row = $this->dbObj->getAllPrepared(
            "SELECT id FROM `".$tableUsers."` WHERE id = :id AND is_alumni = 1 LIMIT 1",
            array(':id' => $userId)
        );
        return !empty($row);
    }

    private function normalizeSectionLabel($label){
        $label = strtoupper(trim((string)$label));
        if ($label === '') {
            return '';
        }
        if (preg_match('/^[A-Z0-9]$/', $label)) {
            return $label;
        }
        if (preg_match('/\b([A-Z0-9])\b/', $label, $m)) {
            return $m[1];
        }
        return '';
    }

    private function guessSectionSuffix($sectionName, $sectionCode){
        $sectionName = strtoupper(trim((string)$sectionName));
        $sectionCode = strtoupper(trim((string)$sectionCode));

        if (preg_match('/\bSECTION\s*[- ]\s*([A-Z])\b/', $sectionName, $m)) {
            return $m[1];
        }
        if (preg_match('/\bSEC\s*[- ]\s*([A-Z])\b/', $sectionName, $m2)) {
            return $m2[1];
        }
        if (preg_match('/\b([A-Z])\b/', $sectionCode, $m3)) {
            return $m3[1];
        }

        return '';
    }

    public function adminTransferUserToAlumniKeepSectionLabel($tableUsers, $userId){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        if ($userId <= 0) {
            $this->setLastError('Invalid user id.');
            return false;
        }
        if (!$this->tableHasColumn($tableUsers, 'is_alumni')) {
            $this->setLastError(TB_USERS . '.is_alumni column missing.');
            return false;
        }

        $userRows = $this->dbObj->getAllPrepared(
            "SELECT id, section FROM `".$tableUsers."` WHERE id = :id LIMIT 1",
            array(':id' => $userId)
        );
        if (empty($userRows)) {
            $this->setLastError('User not found.');
            return false;
        }

        $sectionRaw = (string)($userRows[0]['section'] ?? '');
        $currentSectionId = (int)$sectionRaw;
        $origSectionIdForSave = null;
        $suffix = '';

        if ($currentSectionId > 0) {
            $secRows = $this->dbObj->getAllPrepared(
                "SELECT section_name, section_code FROM `".TB_SECTION."` WHERE id = :id LIMIT 1",
                array(':id' => $currentSectionId)
            );
            if (!empty($secRows)) {
                $suffix = $this->guessSectionSuffix((string)($secRows[0]['section_name'] ?? ''), (string)($secRows[0]['section_code'] ?? ''));
            }
            $origSectionIdForSave = $currentSectionId;
        } else {
            $suffix = $this->guessSectionSuffix($sectionRaw, $sectionRaw);
            if ($suffix === '') {
                $suffix = $this->normalizeSectionLabel($sectionRaw);
            }
        }

        $setRoleSql = $this->tableHasColumn($tableUsers, 'role') ? ", role = CASE WHEN role = 'admin' THEN role ELSE 'alumni' END" : '';
        $setUserTypeSql = $this->tableHasColumn($tableUsers, 'user_type') ? ", user_type = 'alumni'" : '';
        $setPassoutYearSql = $this->tableHasColumn($tableUsers, 'passout_year') ? ", passout_year = :passout_year" : '';

        $params = array(
            ':graduated_on' => gmdate('Y-m-d'),
            ':orig_section_id' => $origSectionIdForSave,
            ':orig_section_label' => $suffix !== '' ? $suffix : null,
            ':id' => $userId
        );
        if ($setPassoutYearSql !== '') {
            $params[':passout_year'] = (int)gmdate('Y');
        }

        $ok = $this->dbObj->executePrepared(
            "UPDATE `".$tableUsers."`
             SET is_alumni = 1,
                 alumni_graduated_on = :graduated_on,
                 alumni_original_section_id = :orig_section_id,
                 alumni_original_section_label = :orig_section_label
                 ".$setUserTypeSql."
                 ".$setPassoutYearSql."
                 ".$setRoleSql."
             WHERE id = :id",
            $params
        );

        if ($ok === false) {
            $this->setLastError('Failed to update alumni flag.');
            return false;
        }

        $this->setLastError('');
        return $ok;
    }

    private function removeUserFromAlumni($tableUsers, $userId){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        if ($userId <= 0 || !$this->tableHasColumn($tableUsers, 'is_alumni')) {
            return false;
        }

        $setRoleSql = $this->tableHasColumn($tableUsers, 'role') ? ", role = CASE WHEN role = 'admin' THEN role ELSE 'student' END" : '';
        $setUserTypeSql = $this->tableHasColumn($tableUsers, 'user_type') ? ", user_type = 'student'" : '';
        $setPassoutYearSql = $this->tableHasColumn($tableUsers, 'passout_year') ? ", passout_year = NULL" : '';

        return $this->dbObj->executePrepared(
            "UPDATE `".$tableUsers."`
             SET is_alumni = 0,
                 alumni_original_section_id = NULL,
                 alumni_original_section_label = NULL,
                 alumni_graduated_on = NULL
                 ".$setUserTypeSql."
                 ".$setPassoutYearSql."
                 ".$setRoleSql."
             WHERE id = :id",
            array(':id' => $userId)
        );
    }

    public function adminRemoveUserFromAlumniAndRestore($tableUsers, $userId){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        if ($userId <= 0) {
            return false;
        }

        $originalSectionId = 0;
        if ($this->tableHasColumn($tableUsers, 'alumni_original_section_id')) {
            $rows = $this->dbObj->getAllPrepared(
                "SELECT alumni_original_section_id FROM `".$tableUsers."` WHERE id = :id LIMIT 1",
                array(':id' => $userId)
            );
            $originalSectionId = !empty($rows) ? (int)($rows[0]['alumni_original_section_id'] ?? 0) : 0;
        }

        if ($originalSectionId > 0) {
            $this->dbObj->executePrepared(
                "UPDATE `".$tableUsers."` SET section = :section_id WHERE id = :id",
                array(':section_id' => (string)$originalSectionId, ':id' => $userId)
            );
        }

        return $this->removeUserFromAlumni($tableUsers, $userId);
    }

    public function getUserByLogin($tableUsers, $input){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $input = trim((string)$input);
        if ($input === '') {
            return array();
        }

        return $this->dbObj->getAllPrepared(
            "SELECT * FROM `".$tableUsers."`
             WHERE admission_id = :input
             LIMIT 1",
            array(':input' => $input)
        );
    }

    public function getAdminByLogin($tableAdmin, $input){
        $tableAdmin = $this->assertSafeIdentifier($tableAdmin);
        $input = trim((string)$input);
        if ($input === '') {
            return array();
        }

        return $this->dbObj->getAllPrepared(
            "SELECT * FROM `".$tableAdmin."`
             WHERE mail_id = :input
                OR adminname = :input
             LIMIT 1",
            array(':input' => $input)
        );
    }

    public function adminDeleteUserById($tableUsers, $userId){
        $tableUsers = $this->assertSafeIdentifier($tableUsers);
        $userId = (int)$userId;
        if ($userId <= 0) {
            return false;
        }
        $sqlQuery = "DELETE FROM `".$tableUsers."` WHERE id = :id";
        return $this->dbObj->executePrepared($sqlQuery, array(':id' => $userId));
    }

    /*
     *  GET TOTAL COUNT FROM ANY TABLE
     */
    public function getCount($table){
        
        $sqlQuery = "SELECT COUNT(*) AS total FROM ".$table;
        $result   = $this->dbObj->getAllResults($sqlQuery);

        if(!empty($result)){
            return $result[0]['total'];
        }else{
            return 0;
        }
    }


    /*
     *  GET LATEST ACTIVITIES (FOR DASHBOARD)
     */
    public function getLatestActivities($table = 'activities'){
        
        $sqlQuery = "SELECT id, title, created_at 
                     FROM ".$table." 
                     ORDER BY created_at DESC 
                     LIMIT 5";

        try {
            $result = $this->dbObj->getAllResults($sqlQuery);
            return $result;
        } catch (Exception $e) {
            // Keep dashboard functional when optional activity table is absent.
            return array();
        }
    }

    /*
     *  GET UPCOMING EVENTS (FOR DASHBOARD)
     */
    public function getUpcomingEvents($limit = 5, $table = 'events'){

        $limit = (int)$limit;
        if($limit <= 0){
            $limit = 5;
        }

        $today = date('Y-m-d');

        $sqlQuery = "SELECT id, event_name, event_date
                     FROM ".$table."
                     WHERE event_date >= '".$today."'
                     ORDER BY event_date ASC
                     LIMIT ".$limit;

        try {
            return $this->dbObj->getAllResults($sqlQuery);
        } catch (Exception $e) {
            return array();
        }
    }

    /*
     *  GET ENROLLMENT BY BATCH (FOR DASHBOARD)
     */
    public function getEnrollmentByBatch($limit = 4){

        $limit = (int)$limit;
        if($limit <= 0){
            $limit = 4;
        }

        $sqlQuery = "SELECT yb.id AS batch_id, yb.batch AS batch_name, COUNT(u.id) AS total
                     FROM year_batch yb
                     LEFT JOIN ".TB_USERS." u ON u.batch_id = yb.id
                     GROUP BY yb.id, yb.batch
                     ORDER BY yb.id DESC
                     LIMIT ".$limit;

        try {
            return $this->dbObj->getAllResults($sqlQuery);
        } catch (Exception $e) {
            return array();
        }
    }



}











