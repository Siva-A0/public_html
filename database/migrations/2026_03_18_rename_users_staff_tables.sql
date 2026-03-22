-- Rename legacy table names to the new terminology used in the codebase.
-- Safe to run once on a database that still has the old names.

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'users'
    ) AND NOT EXISTS(
      SELECT 1
      FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'students'
    ),
    'RENAME TABLE `users` TO `students`',
    'SELECT ''users -> students skipped'''
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'staff'
    ) AND NOT EXISTS(
      SELECT 1
      FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'faculty'
    ),
    'RENAME TABLE `staff` TO `faculty`',
    'SELECT ''staff -> faculty skipped'''
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SET @sql := (
  SELECT IF(
    EXISTS(
      SELECT 1
      FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'staff_category'
    ) AND NOT EXISTS(
      SELECT 1
      FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME = 'faculty_category'
    ),
    'RENAME TABLE `staff_category` TO `faculty_category`',
    'SELECT ''staff_category -> faculty_category skipped'''
  )
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

SHOW TABLES LIKE 'students';
SHOW TABLES LIKE 'faculty';
SHOW TABLES LIKE 'faculty_category';
