CREATE TABLE  `mails` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `subject` VARCHAR( 255 ) NOT NULL ,
 `text` TEXT NOT NULL ,
 `crdate` TIME NOT NULL ,
 `sender` INT NOT NULL ,
 `getter` INT NOT NULL
) ENGINE = INNODB;
ALTER TABLE  `mails` CHANGE  `crdate`  `crdate` DATETIME NOT NULL;