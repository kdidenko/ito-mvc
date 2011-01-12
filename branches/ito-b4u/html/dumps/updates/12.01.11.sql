CREATE TABLE  `bought_orders` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `order_id` INT NOT NULL ,
 `user_id` INT NOT NULL ,
 `bought_date` DATETIME NOT NULL ,
 `bought_price` FLOAT NOT NULL
) ENGINE = INNODB;

ALTER TABLE  `orders` ADD  `bought` BOOL NOT NULL ;
ALTER TABLE  `orders` CHANGE  `bought`  `bought` TINYINT( 1 ) NOT NULL DEFAULT  '0'