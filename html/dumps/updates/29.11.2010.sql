ALTER TABLE  `users` ADD  `salutation` TINYINT NULL COMMENT  '1- man; 2 -woman';
ALTER TABLE  `users` ADD  `company_year` INT NULL ;
ALTER TABLE  `users` DROP  `skype` ,DROP  `gender` ;