DROP TABLE `school_courses`;

CREATE TABLE `trainings` (
  `id` int(11) NOT NULL auto_increment,
  `training_id` int(11) NOT NULL,
  `training_name` varchar(255) character set latin1 NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

INSERT INTO `trainings` (`id`, `training_id`, `training_name`, `user_id`, `course_id`) VALUES 
(1, 1, 'NLP', 37, 11),
(3, 2, 'DHC', 37, 13);
