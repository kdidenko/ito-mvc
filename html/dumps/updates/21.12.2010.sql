CREATE TABLE  `order_relations` (
 `id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
 `order_id` INT( 11 ) NOT NULL ,
 `upload_id` INT( 11 ) NOT NULL ,
PRIMARY KEY (  `id` )
) ENGINE = INNODB DEFAULT CHARSET = utf8 COLLATE = utf8_bin AUTO_INCREMENT =3;

ALTER TABLE  `orders` ADD  `order_price` FLOAT NOT NULL ;