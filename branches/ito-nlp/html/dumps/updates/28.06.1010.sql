SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
CREATE TABLE `valuations` (
  `id` int(11) NOT NULL auto_increment,
  `v_index` int(11) NOT NULL,
  `v_name` varchar(255) character set utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;
