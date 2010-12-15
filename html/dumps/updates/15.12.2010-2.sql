CREATE TABLE  `orders` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `category_id` INT NOT NULL ,
 `subcategory_id` INT NOT NULL ,
 `order_name` VARCHAR( 255 ) NOT NULL ,
 `order_desc` TEXT NOT NULL ,
 `street` VARCHAR( 255 ) NOT NULL ,
 `zip` VARCHAR( 255 ) NOT NULL ,
 `city` VARCHAR( 255 ) NOT NULL ,
 `region` INT NOT NULL ,
 `country` INT NOT NULL ,
 `from_date` DATE NOT NULL ,
 `until_date` DATE NOT NULL
) ENGINE = INNODB;

ALTER TABLE  `orders` ADD  `hash` VARCHAR( 32 ) NOT NULL ;


ALTER TABLE  `orders` ADD  `owner` INT NOT NULL AFTER  `id` ;