-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Час створення: Чрв 05 2010 р., 10:35
-- Версія сервера: 5.0.27
-- Версія PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- БД: `youcademy`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблиці `courses`
-- 

CREATE TABLE `courses` (
  `id` int(11) NOT NULL auto_increment,
  `caption` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `level` int(11) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `crdate` datetime NOT NULL,
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `school_id` int(11) NOT NULL,
  `fee_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- 
-- Дамп даних таблиці `courses`
-- 

INSERT INTO `courses` (`id`, `caption`, `description`, `level`, `alias`, `avatar`, `crdate`, `modified`, `school_id`, `fee_id`, `rate`) VALUES 
(11, 'NLP Master Practitioner', 'Master Practitioner course created and moderated by John LaValle. The course consist of 3 sections and you need to achieve 280 points in every one of them to complete each of the 5 course levels. Certificates after level 3.', 2, 'NLPmaster', 'storage/uploads/courses/NLPmaster/avatar.jpg', '2010-05-04 09:41:50', '2010-05-11 17:33:45', 5, 0, 5),
(13, 'Persuasion Engineering', 'Sky Rocket Sales! Accelerated Bottom Line! More Responsive Customer Service! Increased Income! Influence More Gently but with Precision. 5 groups of exercises, 100 points in every one of them for each of the 2 levels.', 1, 'persuasion', 'storage/uploads/courses/persuasion/avatar.jpg', '2010-05-04 11:13:32', '2010-05-11 17:33:17', 5, 0, 3);

-- --------------------------------------------------------

-- 
-- Структура таблиці `exercises`
-- 

CREATE TABLE `exercises` (
  `id` int(11) NOT NULL auto_increment,
  `caption` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `crdate` datetime NOT NULL,
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `owner_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL default '0',
  `course_id` int(11) default NULL,
  `video` varchar(1000) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

-- 
-- Дамп даних таблиці `exercises`
-- 

INSERT INTO `exercises` (`id`, `caption`, `description`, `crdate`, `modified`, `owner_id`, `rate`, `course_id`, `video`) VALUES 
(14, 'NLP eye patterns - eye access cues ', 'Join master hypnotist and nlp practitioner Alan as he explains eye access cues.\r\n\r\nObserving eye access cues during a conversation allows you to build rapport quickly and easily and also understand someones strategy for doing things.\r\n\r\nWant to know how someone buys something, ask them to think of a previous time when they bought and watch the eye access movements. These movements will tell you whether they picture, feel or hear something and in what order when making a purchasing decision.', '2010-05-12 09:40:39', '0000-00-00 00:00:00', 36, 0, NULL, '<object width="560" height="340"><param name="movie" value="http://www.youtube-nocookie.com/v/oWfmnKAStAw&hl=ru_RU&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/oWfmnKAStAw&hl=ru_RU&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="560" height="340"></embed></object>'),
(11, 'Change your perspective ', 'When we change the way we look at things, the things we look at change. Find new ways to look at your problems with NLP and new solutions will appear like magic!\r\n\r\nThis fun interactive exercise will help you see just how easy it is to change your perspective and find happiness!', '2010-05-12 09:25:38', '2010-05-12 12:28:13', 4, 0, NULL, '<object width="560" height="340"><param name="movie" value="http://www.youtube-nocookie.com/v/v4LiI7X3Nlo&hl=ru_RU&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/v4LiI7X3Nlo&hl=ru_RU&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="560" height="340"></embed></object>'),
(12, 'Improve your mood with nlp anchoring ', 'Learn to use Anchoring to access powerful states and improve your mood any time you desire. NLP provides many tools to help people be their best, anchoring is a powerful tool to help people access positive states whenever they desire.', '2010-05-12 09:34:16', '0000-00-00 00:00:00', 36, 0, NULL, '<object width="560" height="340"><param name="movie" value="http://www.youtube-nocookie.com/v/hGK-tG4RxZc&hl=ru_RU&fs=1&rel=0"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube-nocookie.com/v/hGK-tG4RxZc&hl=ru_RU&fs=1&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="560" height="340"></embed></object>');

-- --------------------------------------------------------

-- 
-- Структура таблиці `schools`
-- 

CREATE TABLE `schools` (
  `id` int(11) NOT NULL auto_increment,
  `alias` varchar(255) NOT NULL,
  `caption` varchar(255) NOT NULL,
  `description` varchar(1024) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `crdate` datetime NOT NULL,
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `admin_id` int(11) NOT NULL,
  `fee_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL default '0',
  `language` varchar(30) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- 
-- Дамп даних таблиці `schools`
-- 

INSERT INTO `schools` (`id`, `alias`, `caption`, `description`, `avatar`, `crdate`, `modified`, `admin_id`, `fee_id`, `rate`, `language`) VALUES 
(2, 'NLP', 'Richard Bandler', 'Here you can learn from the Master himself and his team!', 'storage/uploads/schools/NLP/avatar.jpg', '2010-05-01 13:57:50', '2010-05-11 11:20:40', 4, 0, 2, 'English'),
(5, 'UoM', 'University of Maryland. University college.', 'Market yourself to employers, make more money or earn more responsibility at your current job. The emergence of a truly global business environment has made advanced business training more important than ever.', 'storage/uploads/schools/UoM/avatar.jpg', '2010-05-01 15:26:38', '2010-05-11 11:20:40', 4, 0, 5, 'English'),
(10, 'NMTI', 'National Massage Therapy Institute', 'Get the Training You Need\r\n\r\nAt NMTI, your courses will help you build a solid foundation in Swedish Massage plus additional skills in Sports Massage, Reflexology, Shiatsu, Business Skills and more.\r\n\r\nSuccessful program completion leads to a diploma and qualifies you to sit for the National Certification Exam in Therapeutic Massage and Bodywork, which is required for licensing in most states. ', 'storage/uploads/schools/NMTI/avatar.jpg', '2010-05-02 11:40:48', '2010-05-11 11:23:18', 4, 0, 4, 'Russian'),
(9, 'Sedona', 'Bennett/Stellar University ', ' This is it! If you want to enhance your career, your personal life, and learn lots of cool cutting edge techniques--you have found a home with Bennett/Stellar University.\r\n\r\nWe specialize in integrating Neuro-Linguistic Programming (NLP), Life Coaching, Hypnotherapy, and Reiki. What is NLP? How does Hypnotherapy work? What is Life Coaching and how do you become one? Answers to your questions can be found by inquiring for more info.\r\n\r\nLike-minded individuals from all over the world come to Bennett/Stellar University to discover personal development and master health enriching skills with the power of the mind, advanced communication, and energy. ', 'storage/uploads/schools/Sedona/avatar.jpg', '2010-05-02 11:37:52', '2010-05-11 11:20:40', 4, 0, 2, 'English');

-- --------------------------------------------------------

-- 
-- Структура таблиці `schools_assigned`
-- 

CREATE TABLE `schools_assigned` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

-- 
-- Дамп даних таблиці `schools_assigned`
-- 

INSERT INTO `schools_assigned` (`id`, `user_id`, `school_id`) VALUES 
(28, 36, 5);

-- --------------------------------------------------------

-- 
-- Структура таблиці `school_courses`
-- 

CREATE TABLE `school_courses` (
  `id` int(11) NOT NULL auto_increment,
  `school_id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Дамп даних таблиці `school_courses`
-- 

INSERT INTO `school_courses` (`id`, `school_id`, `course_id`) VALUES 
(1, 2, 11);

-- --------------------------------------------------------

-- 
-- Структура таблиці `users`
-- 

CREATE TABLE `users` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `crdate` datetime NOT NULL,
  `modified` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL default '0',
  `deleted` tinyint(1) NOT NULL default '0',
  `birthday` date default NULL,
  `validation_id` varchar(32) NOT NULL,
  `role` varchar(2) NOT NULL,
  `avatar` varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

-- 
-- Дамп даних таблиці `users`
-- 

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `crdate`, `modified`, `enabled`, `deleted`, `birthday`, `validation_id`, `role`, `avatar`) VALUES 
(4, 'Administrator', 'YouCademy', 'admin@youcademy.com', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-04-26 13:29:14', '2010-05-07 19:04:29', 1, 0, NULL, '840c3eda3ea42ecd90aeb3434f3510b7', 'AR', 'storage/uploads/users/admin/profile/avatar.jpg');
