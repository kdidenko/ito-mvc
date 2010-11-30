ALTER TABLE  `mails` ADD  `hash` VARCHAR( 16 ) NOT NULL ;
ALTER TABLE  `mails` CHANGE  `status`  `status` TINYINT( 1 ) NOT NULL DEFAULT  '0' COMMENT  '1 - trash; 2 - drafts; 3 - inbox; 4 - outbox'