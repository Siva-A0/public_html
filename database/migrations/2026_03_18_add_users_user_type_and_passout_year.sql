-- Backward-compatible alumni lifecycle fields for unified students table.
-- Existing INSERT queries keep working because the new fields are nullable/defaulted.

ALTER TABLE `students`
  ADD COLUMN `user_type` ENUM('student','alumni') NOT NULL DEFAULT 'student' AFTER `password`,
  ADD COLUMN `passout_year` YEAR NULL DEFAULT NULL AFTER `section`;

ALTER TABLE `students`
  MODIFY COLUMN `section` varchar(10) NULL DEFAULT NULL COMMENT 'section';

CREATE INDEX `idx_students_user_type` ON `students` (`user_type`);
CREATE INDEX `idx_students_passout_year` ON `students` (`passout_year`);
