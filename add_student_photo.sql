-- Migration: store the applicant's student id and photo on admission rows.
-- Run this once if you already imported schoolproject.sql before photo support
-- was added. It is safe to run more than once.

USE `schoolproject`;

ALTER TABLE `admission`
  ADD COLUMN `student_id` VARCHAR(20) NOT NULL DEFAULT '' AFTER `id`;

ALTER TABLE `admission`
  ADD COLUMN `image` VARCHAR(255) NOT NULL DEFAULT '' AFTER `message`;

-- Existing rows have no student id; keep the unique-ish lookup index optional.
-- CREATE INDEX `idx_admission_student_id` ON `admission` (`student_id`);