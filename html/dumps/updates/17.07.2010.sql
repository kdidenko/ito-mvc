ALTER TABLE `courses` ADD `number_rate` INT NOT NULL AFTER `rate` ;
ALTER TABLE `courses` CHANGE `rate` `rate` FLOAT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `schools` CHANGE `rate` `rate` FLOAT( 11 ) NOT NULL DEFAULT '0';

CREATE TABLE `courses_rate` (
  `id` int(11) NOT NULL auto_increment,
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rate` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
