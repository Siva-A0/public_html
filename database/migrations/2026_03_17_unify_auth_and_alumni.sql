-- Unify authentication + simplify alumni handling.
-- Run once on your MySQL database (will error if columns already exist).

ALTER TABLE `students`
  ADD COLUMN `role` ENUM('student','alumni','admin') NOT NULL DEFAULT 'student';

ALTER TABLE `students`
  ADD COLUMN `is_alumni` TINYINT(1) NOT NULL DEFAULT 0,
  ADD COLUMN `alumni_original_section_id` INT(11) NULL DEFAULT NULL,
  ADD COLUMN `alumni_original_section_label` VARCHAR(20) NULL DEFAULT NULL,
  ADD COLUMN `alumni_graduated_on` DATE NULL DEFAULT NULL;

-- Optional helpful indexes for filtering in admin lists.
CREATE INDEX `idx_students_role` ON `students` (`role`);
CREATE INDEX `idx_students_is_alumni` ON `students` (`is_alumni`);

-- Admin migration:
-- This project also performs a best-effort, idempotent migration at runtime
-- (see DataFunctions::migrateLegacyAdminsToUsers()).
-- If you prefer a pure-SQL migration, implement an INSERT...SELECT here that
-- matches your exact `admin` and `students` schemas.
