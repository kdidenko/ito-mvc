ALTER TABLE  `users` ADD  `skype` VARCHAR( 255 ) NULL ;
ALTER TABLE  `users` ADD  `Gender` TINYINT NULL ;
ALTER TABLE  `users` CHANGE  `Gender`  `gender` VARCHAR( 6 ) NULL DEFAULT NULL;