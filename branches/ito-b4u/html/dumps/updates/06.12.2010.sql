CREATE TABLE  `plan` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `plan_name` VARCHAR( 255 ) NOT NULL ,
 `plan_price` FLOAT NOT NULL
) ENGINE = INNODB;

ALTER TABLE  `users` ADD  `plan_id` INT NOT NULL ;

INSERT INTO `plan` (`id`, `plan_name`, `plan_price`) VALUES 
(1, 0x5031, 100),
(2, 0x5032, 150),
(3, 0x5033, 200),
(4, 0x5034, 250),
(5, 0x5035, 500);