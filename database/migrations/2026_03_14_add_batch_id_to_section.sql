-- Add batch-specific sections support.
-- After running this, sections can vary by (batch_id + class_id).

ALTER TABLE `section`
  ADD COLUMN `batch_id` int(11) NOT NULL DEFAULT 0 AFTER `id`;

CREATE INDEX `idx_section_batch_class` ON `section` (`batch_id`, `class_id`);
