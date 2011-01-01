ALTER TABLE  `bargains` ADD  `discount` FLOAT NOT NULL ;
UPDATE  `b4u`.`bargains` SET  `bargain_price` =  '240',
`discount` =  '13' WHERE  `bargains`.`id` =1 LIMIT 1 ;

UPDATE  `b4u`.`bargains` SET  `usual_price` =  '325',
`bargain_price` =  '299',
`discount` =  '8' WHERE  `bargains`.`id` =2 LIMIT 1 ;

UPDATE  `b4u`.`bargains` SET  `bargain_name` =  'Acer Aspire One D255-2DKK 10.1" Netbook' WHERE  `bargains`.`id` =2 LIMIT 1 ;