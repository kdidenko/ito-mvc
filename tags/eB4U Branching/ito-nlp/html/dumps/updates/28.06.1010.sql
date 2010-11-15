ALTER TABLE  `schools` CHANGE  `fee_id`  `fee_id` INT( 11 ) NULL;
ALTER TABLE  `schools` ADD  `base_fee` INT NOT NULL AFTER  `fee_id` ;
ALTER TABLE  `courses` CHANGE  `fee_id`  `fee_id` INT( 11 ) NULL;
ALTER TABLE  `courses` ADD  `base_fee` INT NOT NULL AFTER  `fee_id` ;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
CREATE TABLE `valuations` (
  `id` int(11) NOT NULL auto_increment,
  `v_index` int(11) NOT NULL,
  `v_name` varchar(255) character set utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

CREATE TABLE  `challenges` (
 `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
 `name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
 `description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
 `owner` INT NOT NULL ,
 `ex_index` INT NOT NULL
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;