-- Run once in phpMyAdmin if the existing user table predates student photos.
USE `schoolproject`;

ALTER TABLE `user`
  ADD COLUMN `image` VARCHAR(255) NOT NULL DEFAULT '';
