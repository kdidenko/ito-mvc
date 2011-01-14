UPDATE  `b4u`.`users` SET  `modified` = NOW( ) ,
`region` =  '2',
`country` =  '1' WHERE  `users`.`id` =42 LIMIT 1 ;

UPDATE  `b4u`.`users` SET  `modified` = NOW( ) ,
`region` =  '1',
`country` =  '1' WHERE  `users`.`id` =46 LIMIT 1 ;

ALTER TABLE  `users` CHANGE  `region`  `region` INT NULL DEFAULT NULL ,
CHANGE  `country`  `country_id` INT NULL DEFAULT NULL;

ALTER TABLE  `users` CHANGE  `region`  `region_id` INT( 11 ) NULL DEFAULT NULL;

ALTER TABLE  `users` CHANGE  `region_id`  `region` INT( 11 ) NULL DEFAULT NULL ,
CHANGE  `country_id`  `country` INT( 11 ) NULL DEFAULT NULL