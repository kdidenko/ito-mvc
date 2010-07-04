RENAME TABLE  `youcademy`.`valuations` TO  `youcademy`.`valuation_assignments` ;

CREATE TABLE  `valuations` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `resp_index` INT NOT NULL ,
 `user_id` INT NOT NULL ,
 `comment` VARCHAR( 1024 ) collate utf8_bin NOT NULL,
 `valuate` INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;