CREATE TABLE  `bargains` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `bargain_name` VARCHAR( 255 ) NOT NULL ,
 `bargain_desc` TEXT NOT NULL ,
 `category_id` INT NOT NULL ,
 `subcategory_id` INT NOT NULL ,
 `usual_price` FLOAT NOT NULL ,
 `bargain_price` FLOAT NOT NULL ,
 `bargain_image` VARCHAR( 255 ) NOT NULL ,
 `street` VARCHAR( 255 ) NOT NULL ,
 `zip` VARCHAR( 255 ) NOT NULL ,
 `city` VARCHAR( 255 ) NOT NULL ,
 `region` VARCHAR( 255 ) NOT NULL ,
 `country` VARCHAR( 255 ) NOT NULL ,
 `website` VARCHAR( 255 ) NOT NULL ,
 `from_date` DATE NOT NULL ,
 `until_date` DATE NOT NULL ,
 `number` INT NOT NULL
) ENGINE = INNODB;

INSERT INTO  `b4u`.`bargains` (
`id` ,
`bargain_name` ,
`bargain_desc` ,
`category_id` ,
`subcategory_id` ,
`usual_price` ,
`bargain_price` ,
`bargain_image` ,
`street` ,
`zip` ,
`city` ,
`region` ,
`country` ,
`website` ,
`from_date` ,
`until_date` ,
`number`
)
VALUES (
NULL ,  'bike', 'I’ve been broken hearted and lonely since my short love affair with the Tranny ended(reviewed for Bike magazine 11/09 by Noah). It’s a little over the $1000 mark, but you can subtract the additional airline bike fees typically incurred when traveling thanks to its clever, packable design–another reason it’s the only Tranny I’d take home to meet mom and dad without regret. Kudos to Ibis for making the hardtail exciting again with this stiff, svelte, and overtly sexy steed.', '1',  '1',  '1099',  '599',  'storage/uploads/bargains/1.jpg',  'Shevchenka',  '1227328',  'Lviv',  'Lviv',  'Ukraine',  'www.bike.com',  '2010-12-08',  '2010-12-29',  '6'
);

ALTER TABLE  `bargains` ADD  `user_id` INT NOT NULL AFTER  `id` ;
UPDATE  `b4u`.`bargains` SET  `user_id` =  '42' WHERE  `bargains`.`id` =1 LIMIT 1 ;