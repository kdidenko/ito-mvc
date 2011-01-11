CREATE TABLE  `bought_bargain` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `user_id` INT NOT NULL ,
 `bargain_id` INT NOT NULL ,
 `bought_date` DATETIME NOT NULL
) ENGINE = INNODB;