ALTER TABLE  `schools` CHANGE  `fee_id`  `fee_id` INT( 11 ) NULL;
ALTER TABLE  `schools` ADD  `base_fee` INT NOT NULL AFTER  `fee_id` ;
ALTER TABLE  `courses` CHANGE  `fee_id`  `fee_id` INT( 11 ) NULL;
ALTER TABLE  `courses` ADD  `base_fee` INT NOT NULL AFTER  `fee_id` ;