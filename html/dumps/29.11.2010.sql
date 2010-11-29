DROP TABLE `mails`, `users`;
CREATE TABLE `mails` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) collate utf8_bin NOT NULL,
  `text` text collate utf8_bin NOT NULL,
  `crdate` datetime NOT NULL,
  `sender_id` int(11) NOT NULL,
  `getter_id` int(11) NOT NULL,
  `opened` tinyint(1) NOT NULL default '0' COMMENT '0 - new ; 1 - read',
  `status` tinyint(1) NOT NULL default '0' COMMENT '1 - trash; 2 - drafts',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

-- 
-- Дамп данных таблицы `mails`
-- 

INSERT INTO `mails` (`id`, `subject`, `text`, `crdate`, `sender_id`, `getter_id`, `opened`, `status`) VALUES 
(1, 0x6669727374206c6574746572, 0x746869732069732074657874206465736372697074696f6e206f66206c6574746572, '2010-11-26 21:34:39', 42, 43, 0, 0),
(2, 0x706c65617365, 0x746869732069732074657874, '2010-11-26 22:20:27', 43, 42, 0, 1),
(3, 0x68656c6c6f, 0x68656c6c6f2067656e74656c6d656e, '2010-11-09 22:20:42', 43, 42, 0, 2),
(4, 0x626c61, 0x626c6120626c6120626c61, '2010-11-16 22:26:45', 42, 43, 1, 0),
(5, 0x7375626a656374, 0x74657874, '2010-11-07 22:31:01', 43, 42, 0, 0);

-- --------------------------------------------------------

-- 
-- Структура таблицы `users`
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
  `company` varchar(255) default NULL,
  `vat` varchar(255) default NULL,
  `address` varchar(255) default NULL,
  `zip` varchar(25) default NULL,
  `location` varchar(50) default NULL,
  `region` varchar(50) default NULL,
  `country` varchar(50) default NULL,
  `phone` varchar(20) default NULL,
  `homepage` varchar(30) default NULL,
  `newsletter` tinyint(4) default NULL,
  `send_job` varchar(50) default NULL,
  `bank` varchar(50) default NULL,
  `acoount_number` varchar(50) default NULL,
  `payment` tinyint(4) default NULL,
  `salutation` tinyint(4) default NULL COMMENT '1- man; 2 -woman',
  `company_year` int(11) default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

-- 
-- Дамп данных таблицы `users`
-- 

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `crdate`, `modified`, `enabled`, `deleted`, `birthday`, `validation_id`, `role`, `avatar`, `company`, `vat`, `address`, `zip`, `location`, `region`, `country`, `phone`, `homepage`, `newsletter`, `send_job`, `bank`, `acoount_number`, `payment`, `salutation`, `company_year`) VALUES 
(4, 'Administrator', 'YouCademy', 'admin@youcademy.com', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-04-26 13:29:14', '2010-05-07 19:04:29', 1, 0, NULL, '840c3eda3ea42ecd90aeb3434f3510b7', 'AR', 'storage/uploads/users/admin/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 'bookkeeper', 'bookkeeper', 'bookkeeper@gmail.com', 'bookkeeper', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-16 09:38:00', '2010-11-10 16:22:45', 1, 0, NULL, 'f69e505b08403ad2298b9f262659929a', 'BR', 'storage/uploads/users/bookkeeper/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 'John', 'Volt', 'john@gmail.com', 'promoter', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-27 11:21:50', '2010-11-10 16:40:27', 1, 0, NULL, '4c8c76b39d294759a9000cbda3a6571a', 'PR', 'storage/uploads/users/user2/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(40, 'Bill', 'Gates', 'bill@gmail.com', 'user1', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-27 11:21:13', '2010-11-10 16:17:13', 1, 0, NULL, 'e0c7ccc47b2613c82d1073a4214deecc', 'UR', 'storage/uploads/users/user1/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 'Riky', 'Volly', 'supprot@ito-global.com', 'tradesman', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-27 11:23:01', '2010-11-29 18:51:07', 1, 0, NULL, '309928d4b100a5d75adff48a9bfc1ddb', 'TR', 'storage/uploads/users/tradesman/profile/avatar.jpg', 'ITO-Global', 'DH827265', 'st. Kokurudzy', '324342', 'lviv', 'lviv state', 'Ukraine', '0322493725', 'www.ito-global.com', NULL, NULL, NULL, NULL, NULL, 1, 1994),
(43, 'Andrew', 'Stabryn', 'astabryn@gmail.com', 'astabryn', 'e10adc3949ba59abbe56e057f20f883e', '2010-10-07 13:42:52', '2010-11-10 16:17:09', 0, 0, '2000-09-07', '9a1de01f893e0d2551ecbb7ce4dc963e', 'UR', 'storage/uploads/users/user4/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
