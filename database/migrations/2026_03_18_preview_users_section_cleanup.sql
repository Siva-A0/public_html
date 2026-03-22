-- Preview legacy students.section values that need cleanup.
-- Safe: SELECT only (no updates).

-- 1) Find students with non-numeric / zero section
SELECT id, username, mail_id, admission_id, batch_id, section
FROM students
WHERE section IS NULL
   OR section = ''
   OR section = '0'
   OR section NOT REGEXP '^[0-9]+$'
ORDER BY id ASC;

-- 2) Preview matching section rows (exact match on section_code / section_name)
-- Replace @batch_id and @section_value when running manually.
-- If your `section` table has `batch_id`, include it to reduce ambiguity.
-- Example:
--   SET @batch_id := 3;
--   SET @section_value := 'II IT A Sec';
--   SELECT s.* FROM section s
--   WHERE (s.section_code = @section_value OR s.section_name = @section_value)
--     AND s.batch_id = @batch_id;
