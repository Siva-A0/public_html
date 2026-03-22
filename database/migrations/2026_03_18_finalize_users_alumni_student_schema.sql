-- Finalize alumni/student support on the `students` table.
-- Safe to run on databases that already have some of these columns/indexes.
-- This keeps the schema aligned with:
--   - the merged PHP code
--   - the `Siva (1).sql` dump sent with the zip

-- 1) Add alumni lifecycle columns when missing.
SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'is_alumni'
    ),
    'SELECT ''students.is_alumni already exists''',
    'ALTER TABLE `students` ADD COLUMN `is_alumni` TINYINT(1) NOT NULL DEFAULT 0 AFTER `status`'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'alumni_original_section_id'
    ),
    'SELECT ''students.alumni_original_section_id already exists''',
    'ALTER TABLE `students` ADD COLUMN `alumni_original_section_id` INT NULL DEFAULT NULL AFTER `is_alumni`'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'alumni_original_section_label'
    ),
    'SELECT ''students.alumni_original_section_label already exists''',
    'ALTER TABLE `students` ADD COLUMN `alumni_original_section_label` VARCHAR(20) NULL DEFAULT NULL AFTER `alumni_original_section_id`'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'alumni_graduated_on'
    ),
    'SELECT ''students.alumni_graduated_on already exists''',
    'ALTER TABLE `students` ADD COLUMN `alumni_graduated_on` DATE NULL DEFAULT NULL AFTER `alumni_original_section_label`'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2) Add role/user lifecycle columns when missing.
SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'role'
    ),
    'SELECT ''students.role already exists''',
    'ALTER TABLE `students` ADD COLUMN `role` ENUM(''student'',''alumni'',''admin'') NOT NULL DEFAULT ''student'' AFTER `alumni_graduated_on`'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'user_type'
    ),
    'SELECT ''students.user_type already exists''',
    'ALTER TABLE `students` ADD COLUMN `user_type` ENUM(''student'',''alumni'') NOT NULL DEFAULT ''student'' AFTER `role`'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'passout_year'
    ),
    'SELECT ''students.passout_year already exists''',
    'ALTER TABLE `students` ADD COLUMN `passout_year` YEAR NULL DEFAULT NULL AFTER `user_type`'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3) Make section nullable so alumni rows can exist without a current section.
SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND COLUMN_NAME = 'section'
        AND IS_NULLABLE = 'YES'
    ),
    'SELECT ''students.section is already nullable''',
    'ALTER TABLE `students` MODIFY COLUMN `section` VARCHAR(10) NULL DEFAULT NULL COMMENT ''section'''
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4) Add helpful indexes only when missing.
SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND INDEX_NAME = 'idx_students_is_alumni'
    ),
    'SELECT ''idx_students_is_alumni already exists''',
    'CREATE INDEX `idx_students_is_alumni` ON `students` (`is_alumni`)'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND INDEX_NAME = 'idx_students_role'
    ),
    'SELECT ''idx_students_role already exists''',
    'CREATE INDEX `idx_students_role` ON `students` (`role`)'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND INDEX_NAME = 'idx_students_user_type'
    ),
    'SELECT ''idx_students_user_type already exists''',
    'CREATE INDEX `idx_students_user_type` ON `students` (`user_type`)'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
        AND INDEX_NAME = 'idx_students_passout_year'
    ),
    'SELECT ''idx_students_passout_year already exists''',
    'CREATE INDEX `idx_students_passout_year` ON `students` (`passout_year`)'
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 5) Optional visibility check after migration.
SELECT
  COLUMN_NAME,
  COLUMN_TYPE,
  IS_NULLABLE,
  COLUMN_DEFAULT
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME = 'students'
  AND COLUMN_NAME IN (
    'section',
    'is_alumni',
    'alumni_original_section_id',
    'alumni_original_section_label',
    'alumni_graduated_on',
    'role',
    'user_type',
    'passout_year'
  )
ORDER BY ORDINAL_POSITION;
