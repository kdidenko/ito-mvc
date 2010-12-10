ALTER TABLE  `plan` ADD  `tender_to` FLOAT NOT NULL ;

UPDATE  `b4u`.`plan` SET  `plan_price` =  '19',
`tender_to` =  '250' WHERE  `plan`.`id` =1 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_price` =  '39',
`tender_to` =  '1000' WHERE  `plan`.`id` =2 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_price` =  '69',
`tender_to` =  '5000' WHERE  `plan`.`id` =3 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_price` =  '99',
`tender_to` =  '10000' WHERE  `plan`.`id` =4 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_price` =  '149' WHERE  `plan`.`id` =5 LIMIT 1 ;


ALTER TABLE  `plan` ADD  `bargains` BOOL NOT NULL ;

UPDATE  `b4u`.`plan` SET  `bargains` =  '1' WHERE  `plan`.`id` =3 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `bargains` =  '1' WHERE  `plan`.`id` =4 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `bargains` =  '1' WHERE  `plan`.`id` =5 LIMIT 1 ;

ALTER TABLE  `plan` ADD  `plan_desc` TEXT NOT NULL AFTER  `id` ;

UPDATE  `b4u`.`plan` SET  `plan_desc` =  'Angebot stellen bis zu einem Auftragsvolumen von <strong>250 Euro</strong>' WHERE  `plan`.`id` =1 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_desc` =  'Angebot stellen bis zu einem Auftragsvolumen von <strong>1.000 Euro</strong>' WHERE  `plan`.`id` =2 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_desc` =  'Angebot stellen bis zu einem Auftragsvolumen von <strong>5.000 Euro</strong>' WHERE  `plan`.`id` =3 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_desc` =  'Angebot stellen bis zu einem Auftragsvolumen von <strong>10.000 Euro</strong>' WHERE  `plan`.`id` =4 LIMIT 1 ;

UPDATE  `b4u`.`plan` SET  `plan_desc` =  'kann zu jedem Auftrag in unbegrenzter Hohe ein Angebot erstellen' WHERE  `plan`.`id` =5 LIMIT 1 ;
