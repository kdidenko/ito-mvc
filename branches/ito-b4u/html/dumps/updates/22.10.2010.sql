ALTER TABLE  `users` ADD  `company` VARCHAR( 255 ) NULL ,
ADD  `vat` VARCHAR( 255 ) NULL ,
ADD  `address` VARCHAR( 255 ) NULL ,
ADD  `zip` INT( 20 ) NULL ,
ADD  `location` VARCHAR( 50 ) NULL ,
ADD  `region` VARCHAR( 50 ) NULL ,
ADD  `country` VARCHAR( 50 ) NULL ,
ADD  `phone` VARCHAR( 20 ) NULL ,
ADD  `homepage` VARCHAR( 30 ) NULL ,
ADD  `newsletter` TINYINT NULL ;
ALTER TABLE  `users` ADD  `send_job` VARCHAR( 50 ) NULL ,
ADD  `bank` VARCHAR( 50 ) NULL ,
ADD  `acoount_number` VARCHAR( 50 ) NULL ,
ADD  `payment` TINYINT NULL ;