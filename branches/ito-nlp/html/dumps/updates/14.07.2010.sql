ALTER TABLE  `categories` ADD  `school_id` INT NOT NULL ;
ALTER TABLE  `courses` ADD  `category_id` INT NOT NULL ;
ALTER TABLE  `courses` CHANGE  `level`  `level` INT( 11 ) NULL;