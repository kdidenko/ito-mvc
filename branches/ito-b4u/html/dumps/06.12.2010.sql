DROP TABLE `category`, `mails`, `static_block`, `subcategory`, `users`;
-- 
-- База данных: `b4u`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблицы `category`
-- 

CREATE TABLE `category` (
  `id` int(11) NOT NULL auto_increment,
  `category_name` varchar(255) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=3 ;

-- 
-- Дамп данных таблицы `category`
-- 

INSERT INTO `category` (`id`, `category_name`) VALUES 
(1, 0x4954),
(2, 0x66696e616e6365);

-- --------------------------------------------------------

-- 
-- Структура таблицы `mails`
-- 

CREATE TABLE `mails` (
  `id` int(11) NOT NULL auto_increment,
  `subject` varchar(255) collate utf8_bin NOT NULL,
  `text` text collate utf8_bin NOT NULL,
  `crdate` datetime NOT NULL,
  `sender_id` int(11) NOT NULL,
  `getter_id` int(11) NOT NULL,
  `opened` tinyint(1) NOT NULL default '0' COMMENT '0 - new ; 1 - read',
  `status` tinyint(1) NOT NULL default '0' COMMENT '1 - trash; 2 - drafts; 3 - inbox; 4 - outbox',
  `hash` varchar(32) collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=31 ;

-- 
-- Дамп данных таблицы `mails`
-- 

INSERT INTO `mails` (`id`, `subject`, `text`, `crdate`, `sender_id`, `getter_id`, `opened`, `status`, `hash`) VALUES 
(12, 0x746573742061646d696e, 0x74657374, '2010-12-01 14:33:46', 42, 4, 1, 1, 0x3532623364613631313337383432366232653939316332396564636436643039),
(16, 0x6664736166, 0x6661647366, '2010-12-01 16:21:05', 42, 4, 1, 1, 0x6565326430386633373930366532393334346239653931303366346366373136),
(22, 0x74657374, 0x74657374, '2010-12-01 18:07:02', 42, 42, 1, 1, 0x3234316434343830366535666365656330303961343439666539393664336135),
(23, 0x4677643a2074657374, 0x266c743b6469762667743b266c743b62722667743b266c743b62722667743b266c743b62722667743b266c743b2f6469762667743b266c743b6469762667743b266c743b666f6e7420636c6173733d2671756f743b4170706c652d7374796c652d7370616e2671756f743b203b3d2671756f743b2671756f743b20636f6c6f723d2671756f743b233939393939392671756f743b2667743b4f6e20323031302d31322d30312031353a30303a31352c207472616465736d616e2077726f74653a266c743b6469762667743b266c743b62722667743b266c743b2f6469762667743b74776573266c743b2f666f6e742667743b266c743b2f6469762667743b, '2010-12-02 21:45:10', 42, 42, 1, 4, 0x3130663563626637366464343564636639623662393964316232383735636134),
(24, 0x4677643a2074657374, 0x266c743b6469762667743b266c743b62722667743b266c743b62722667743b266c743b62722667743b266c743b2f6469762667743b266c743b6469762667743b266c743b666f6e7420636c6173733d2671756f743b4170706c652d7374796c652d7370616e2671756f743b203b3d2671756f743b2671756f743b20636f6c6f723d2671756f743b233939393939392671756f743b2667743b4f6e20323031302d31322d30312031353a30303a31352c207472616465736d616e2077726f74653a266c743b6469762667743b266c743b62722667743b266c743b2f6469762667743b74776573266c743b2f666f6e742667743b266c743b2f6469762667743b, '2010-12-02 21:45:11', 42, 42, 1, 1, 0x6131396535396538376634303933366536326232376563393961373663623063),
(26, 0x52653a204677643a2074657374, 0x266c743b6469762667743b76666473676264686673646766647367666773646667266c743b2f6469762667743b266c743b6469762667743b266c743b62722667743b266c743b2f6469762667743b266c743b6469762667743b66647361667364666173266c743b62722667743b266c743b62722667743b266c743b62722667743b266c743b2f6469762667743b266c743b6469762667743b266c743b666f6e7420636c6173733d2671756f743b4170706c652d7374796c652d7370616e2671756f743b203b3d2671756f743b2671756f743b20636f6c6f723d2671756f743b233939393939392671756f743b2667743b4f6e20323031302d31322d30322032313a34353a31312c207472616465736d616e2077726f74653a266c743b6469762667743b266c743b62722667743b266c743b2f6469762667743b266c743b6469762667743b266c743b62722667743b266c743b62722667743b266c743b62722667743b266c743b2f6469762667743b266c743b6469762667743b266c743b666f6e7420636c6173733d2671756f743b4170706c652d7374796c652d7370616e2671756f743b203b3d2671756f743b2671756f743b20636f6c6f723d2671756f743b233939393939392671756f743b2667743b4f6e20323031302d31322d30312031353a30303a31352c207472616465736d616e2077726f74653a266c743b6469762667743b266c743b62722667743b266c743b2f6469762667743b74776573266c743b2f666f6e742667743b266c743b2f6469762667743b266c743b2f666f6e742667743b266c743b2f6469762667743b, '2010-12-02 21:45:27', 42, 42, 1, 3, 0x3735356435356161623335303238636132386262323636386339353235623565),
(28, 0x52653a206664736166, 0x266c743b6469762667743b266c743b62722667743b266c743b62722667743b266c743b62722667743b266c743b2f6469762667743b266c743b6469762667743b266c743b666f6e7420636c6173733d2671756f743b4170706c652d7374796c652d7370616e2671756f743b203b3d2671756f743b2671756f743b20636f6c6f723d2671756f743b233939393939392671756f743b2667743b4f6e20323031302d31322d30312031363a32313a30352c207472616465736d616e2077726f74653a266c743b6469762667743b266c743b62722667743b266c743b2f6469762667743b6661647366266c743b2f666f6e742667743b266c743b2f6469762667743b, '2010-12-03 08:59:09', 4, 42, 1, 3, 0x3337323538623433323039363932636664383732613239616664616633343332),
(29, 0x646a61736b646a6b, 0x6461736a6b6c64666a61736c6b647161, '2010-12-03 18:24:43', 42, 42, 0, 4, 0x6334633962313264343030656136653936353963633537316230616133373163),
(30, 0x646a61736b646a6b, 0x6461736a6b6c64666a61736c6b647161, '2010-12-03 18:24:43', 42, 42, 0, 3, 0x6138383630626631633431326539366261393036313165643736623064303364);

-- --------------------------------------------------------

-- 
-- Структура таблицы `static_block`
-- 

CREATE TABLE `static_block` (
  `id` int(11) NOT NULL auto_increment,
  `block_title` varchar(255) collate utf8_bin NOT NULL,
  `block_desc` text collate utf8_bin NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=21 ;

-- 
-- Дамп данных таблицы `static_block`
-- 

INSERT INTO `static_block` (`id`, `block_title`, `block_desc`) VALUES 
(20, 0x7472616465736d616e20226d79206163636f756e74222070616765, 0x3c623e746869732069732074657874207769636820796f752063616e20656469742066726f6d2061646d696e20706172743c2f623e);

-- --------------------------------------------------------

-- 
-- Структура таблицы `subcategory`
-- 

CREATE TABLE `subcategory` (
  `id` int(11) NOT NULL auto_increment,
  `subcategory_name` varchar(255) collate utf8_bin NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

-- 
-- Дамп данных таблицы `subcategory`
-- 

INSERT INTO `subcategory` (`id`, `subcategory_name`, `category_id`) VALUES 
(1, 0x53454f, 1),
(2, 0x7765622d64657369676e, 1),
(3, 0x646576656c6f706d656e74, 1),
(4, 0x65636f6e6f6d6963, 2),
(5, 0x6d6f6e6579, 2);

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
  `account_number` varchar(50) default NULL,
  `payment` tinyint(4) default NULL,
  `salutation` tinyint(4) default NULL COMMENT '1- man; 2 -woman',
  `company_year` int(11) default NULL,
  `cat_id` int(11) NOT NULL,
  `subcat_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

-- 
-- Дамп данных таблицы `users`
-- 

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `crdate`, `modified`, `enabled`, `deleted`, `birthday`, `validation_id`, `role`, `avatar`, `company`, `vat`, `address`, `zip`, `location`, `region`, `country`, `phone`, `homepage`, `newsletter`, `send_job`, `bank`, `account_number`, `payment`, `salutation`, `company_year`, `cat_id`, `subcat_id`) VALUES 
(4, 'Administrator', 'YouCademy', 'admin@youcademy.com', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-04-26 13:29:14', '2010-05-07 19:04:29', 1, 0, NULL, '840c3eda3ea42ecd90aeb3434f3510b7', 'AR', 'storage/uploads/users/admin/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(37, 'bookkeeper', 'bookkeeper', 'bookkeeper@gmail.com', 'bookkeeper', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-16 09:38:00', '2010-11-10 16:22:45', 1, 0, NULL, 'f69e505b08403ad2298b9f262659929a', 'BR', 'storage/uploads/users/bookkeeper/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(41, 'John', 'Volt', 'john@gmail.com', 'promoter', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-27 11:21:50', '2010-11-10 16:40:27', 1, 0, NULL, '4c8c76b39d294759a9000cbda3a6571a', 'PR', 'storage/uploads/users/user2/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(40, 'Bill', 'Gates', 'bill@gmail.com', 'tradesman2', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-27 11:21:13', '2010-12-01 20:14:46', 1, 0, NULL, 'e0c7ccc47b2613c82d1073a4214deecc', 'UR', 'storage/uploads/users/tradesman2/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0),
(42, 'Andrew', 'Stabryn', 'astabryn@ito-global.com', 'tradesman', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-27 11:23:01', '2010-12-04 23:38:36', 1, 0, NULL, '309928d4b100a5d75adff48a9bfc1ddb', 'TR', 'storage/uploads/users/tradesman/profile/avatar.jpg', 'ITO-Global', 'DH827265', 'Shyroka', '45465', 'lviv', 'lviv state', 'Ukraine', '+384564645', 'www.ito-global.com', NULL, NULL, NULL, NULL, NULL, 1, 1994, 1, 2),
(43, 'Andrew', 'Stabryn', 'astabryn@gmail.com', 'astabryn', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-10-07 13:42:52', '2010-12-04 22:47:50', 0, 0, '2000-09-07', '9a1de01f893e0d2551ecbb7ce4dc963e', 'UR', 'storage/uploads/users/user4/profile/avatar.jpg', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0),
(46, 'test', 'test', 'test@test.test', 'test', 'e10adc3949ba59abbe56e057f20f883e', '2010-12-03 21:21:47', '2010-12-04 00:14:08', 1, 0, NULL, 'd8330f857a17c53d217014ee776bfd50', 'TR', 'storage/uploads/users/test/profile/avatar.jpg', 'dsadas', NULL, 'dsad', 'fsd', 'das', 'das', 'das', 'das', 'das', 1, '1', 'das', 'da', NULL, NULL, NULL, 0, 0);
