-- School Management System database
-- Import this file in phpMyAdmin, or run: mysql -u root < schoolproject.sql

CREATE DATABASE IF NOT EXISTS `schoolproject`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `schoolproject`;

CREATE TABLE IF NOT EXISTS `user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL DEFAULT '',
  `phone` VARCHAR(30) NOT NULL DEFAULT '',
  `usertype` ENUM('admin', 'teacher', 'student') NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_user_username` (`username`),
  KEY `idx_user_usertype` (`usertype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `teacher` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL DEFAULT '',
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_teacher_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `course` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `course_name` VARCHAR(150) NOT NULL,
  `course_code` VARCHAR(50) NOT NULL,
  `teacher_id` INT UNSIGNED DEFAULT NULL,
  `duration` VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_course_code` (`course_code`),
  KEY `idx_course_teacher_id` (`teacher_id`),
  CONSTRAINT `fk_course_teacher`
    FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `student_course` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` INT UNSIGNED NOT NULL,
  `course_id` INT UNSIGNED NOT NULL,
  `registration_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_student_course` (`student_id`, `course_id`),
  KEY `idx_student_course_course_id` (`course_id`),
  CONSTRAINT `fk_student_course_student`
    FOREIGN KEY (`student_id`) REFERENCES `user` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_student_course_course`
    FOREIGN KEY (`course_id`) REFERENCES `course` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Result files sent by an admin/teacher to one student or all students.
-- Use `All Students` in student_name when every student should receive it.
CREATE TABLE IF NOT EXISTS `rusult` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_name` VARCHAR(100) NOT NULL,
  `description` TEXT NOT NULL,
  `file` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rusult_student_name` (`student_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `admission` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `message` TEXT NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `assignment` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `teacher_name` VARCHAR(100) NOT NULL,
  `student_name` VARCHAR(100) NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `deadline` DATE NOT NULL,
  `file` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_assignment_student_deadline` (`student_name`, `deadline`),
  KEY `idx_assignment_teacher_name` (`teacher_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Default administrator for the existing plaintext login implementation.
-- Username: admin  |  Password: admin123
INSERT INTO `user` (`username`, `email`, `phone`, `usertype`, `password`)
VALUES ('admin', 'admin@school.local', '', 'admin', 'admin123')
ON DUPLICATE KEY UPDATE `usertype` = VALUES(`usertype`);
