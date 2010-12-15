CREATE TABLE  `country` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `country_name` VARCHAR( 255 ) NOT NULL
) ENGINE = INNODB;
INSERT INTO  `b4u`.`country` (
`id` ,
`country_name`
)
VALUES (
NULL ,  'Deutschland'
);
UPDATE  `b4u`.`country` SET  `country_name` =  'Österreich' WHERE  `country`.`id` =1 LIMIT 1 ;