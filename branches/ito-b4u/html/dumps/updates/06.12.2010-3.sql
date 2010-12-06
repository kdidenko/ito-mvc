ALTER TABLE  `users` ADD  `company_desc` TEXT NOT NULL AFTER  `company` ;
UPDATE  `b4u`.`users` SET  `modified` = NOW( ) ,
`company_desc` =  'we are good company' WHERE  `users`.`id` =42 LIMIT 1 ;