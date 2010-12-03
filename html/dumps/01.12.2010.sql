DROP TABLE `mails`, `users`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17 ;

-- 
-- Дамп данных таблицы `mails`
-- 

INSERT INTO `mails` (`id`, `subject`, `text`, `crdate`, `sender_id`, `getter_id`, `opened`, `status`, `hash`) VALUES 
(1, 0x6669727374206c6574746572, 0x746869732069732074657874206465736372697074696f6e206f66206c6574746572, '2010-11-26 21:34:39', 42, 43, 0, 0, 0x31313131313131313131313131313131),
(2, 0x706c65617365, 0x746869732069732074657874, '2010-11-26 22:20:27', 43, 42, 1, 3, 0x31313131313131313131313131313132),
(3, 0x68656c6c6f, 0x68656c6c6f2067656e74656c6d656e, '2010-11-09 22:20:42', 43, 42, 0, 2, 0x31313131313131313131313131313133),
(4, 0x626c61, 0x626c6120626c6120626c61, '2010-11-16 22:26:45', 42, 43, 1, 0, 0x31313131313131313131313131313134),
(5, 0x7375626a656374, 0x74657874, '2010-11-07 22:31:01', 43, 42, 0, 0, 0x31313131313131313131313131313135),
(6, 0x666473666473, 0x6664617366736466, '2010-11-30 15:33:31', 42, 43, 1, 4, 0x3232643464316231363233303563613863386435643666373664343436636531),
(7, 0x666473666473, 0x6664617366736466, '2010-11-30 15:33:31', 42, 43, 1, 3, 0x3232643464316231363233303563613863386435643666373664343436636531),
(8, 0x74657374206d657373616765, 0x53616c742069736e74206a7573742070726576656e74696720686173682d76616c756520706169722072657573652e2053616c7420697320666f722070726576656e746967207261696e626f77207461626c652061747461636b732e205261696e626f77207461626c657320617265206f6e6c792075736566756c20666f722073686f72742070617373776f7264732028362d382d313020636861726163746572732c206f7220736f292e20496620796f752073616c7420796f75722070617373776f7264732c20796f7520696e63726561736520746865206c656e677468206f66207468652068617368656420737472696e67206472616d61746963616c6c792c20736f20746865792077696c6c206e6f74206d6174636820616e797468696e6720696e20746865207261696e626f77207461626c652e0d0a0d0a54686520666163742c2074686174203820636861726163746572206c656e6774682070617373776f7264732063616e206265206272757465666f7263656420776974682061205043206973206e6f7420746865206661756c74206f6620746865204d443520616c676f726974686d2e20736861312c206f72207768617465766572206861736865732063616e2062652062726f6b656e20696e20612073616d65207761792028627275746520666f726365292e205468652070726f626c656d20697320746861742074686573652068617368657320612022746f6f2066617374222e20546865792063616e2062656e20646f6e652062696c6c696f6e73206f662074696d657320706572207365636f6e642e0d0a0d0a546f2070726576656e742028736c6f7720646f776e2920627275746520666f7263652061747461636b732c206f6e652063616e20636f6e737472756374206120686173682066756e6374696f6e20746861742069732076657279206861726420746f20636f6d707574652e20546869732063616e20626520646f6e6520656173696c793a, '2010-11-30 15:42:11', 42, 4, 1, 4, 0x3438623833336166346539336339306437343733396262353464323064663162),
(9, 0x74657374206d657373616765, 0x266e6273703b53616c742069736e74206a7573742070726576656e74696720686173682d76616c756520706169722072657573652e3c62723e3c62723e53616c7420697320666f722070726576656e746967207261696e626f77207461626c652061747461636b732e205261696e626f77207461626c657320617265206f6e6c792075736566756c20666f722073686f72742070617373776f7264732028362d382d313020636861726163746572732c206f7220736f292e20496620796f752073616c7420796f75722070617373776f7264732c20796f7520696e63726561736520746865206c656e677468206f66207468652068617368656420737472696e67206472616d61746963616c6c792c20736f20746865792077696c6c206e6f74206d6174636820616e797468696e6720696e20746865207261696e626f77207461626c652e3c62723e3c62723e54686520666163742c2074686174203820636861726163746572206c656e6774682070617373776f7264732063616e206265206272757465666f7263656420776974682061205043206973206e6f7420746865206661756c74206f6620746865204d443520616c676f726974686d2e20736861312c206f72207768617465766572206861736865732063616e2062652062726f6b656e20696e20612073616d65207761792028627275746520666f726365292e205468652070726f626c656d20697320746861742074686573652068617368657320612022746f6f2066617374222e20546865792063616e2062656e20646f6e652062696c6c696f6e73206f662074696d657320706572207365636f6e642e3c62723e3c62723e546f2070726576656e742028736c6f7720646f776e2920627275746520666f7263652061747461636b732c206f6e652063616e20636f6e737472756374206120686173682066756e6374696f6e20746861742069732076657279206861726420746f20636f6d707574652e20546869732063616e20626520646f6e6520656173696c793a, '2010-11-30 15:45:16', 42, 4, 1, 1, 0x6465316338616463656664373331613036383938323261343935383962303464),
(10, 0x74657374206d657373616765, 0x266e6273703b53616c742069736e74206a7573742070726576656e74696720686173682d76616c756520706169722072657573652e3c62723e3c62723e53616c7420697320666f722070726576656e746967207261696e626f77207461626c652061747461636b732e205261696e626f77207461626c657320617265206f6e6c792075736566756c20666f722073686f72742070617373776f7264732028362d382d313020636861726163746572732c206f7220736f292e20496620796f752073616c7420796f75722070617373776f7264732c20796f7520696e63726561736520746865206c656e677468206f66207468652068617368656420737472696e67206472616d61746963616c6c792c20736f20746865792077696c6c206e6f74206d6174636820616e797468696e6720696e20746865207261696e626f77207461626c652e3c62723e3c62723e54686520666163742c2074686174203820636861726163746572206c656e6774682070617373776f7264732063616e206265206272757465666f7263656420776974682061205043206973206e6f7420746865206661756c74206f6620746865204d443520616c676f726974686d2e20736861312c206f72207768617465766572206861736865732063616e2062652062726f6b656e20696e20612073616d65207761792028627275746520666f726365292e205468652070726f626c656d20697320746861742074686573652068617368657320612022746f6f2066617374222e20546865792063616e2062656e20646f6e652062696c6c696f6e73206f662074696d657320706572207365636f6e642e3c62723e3c62723e546f2070726576656e742028736c6f7720646f776e2920627275746520666f7263652061747461636b732c206f6e652063616e20636f6e737472756374206120686173682066756e6374696f6e20746861742069732076657279206861726420746f20636f6d707574652e20546869732063616e20626520646f6e6520656173696c793a, '2010-11-30 15:45:16', 42, 4, 1, 1, 0x6465316338616463656664373331613036383938323261343935383962303464),
(11, 0x52653a4677643a52653a4677643a52653a4677643a52653a4677643a40, 0x222222, '2010-12-01 12:00:58', 42, 43, 1, 4, 0x6235633230366136663466633766333864303466616137353435373565613030),
(12, 0x52653a4677643a52653a4677643a52653a4677643a52653a4677643a40, 0x222222, '2010-12-01 12:00:58', 42, 43, 1, 3, 0x6235633230366136663466633766333864303466616137353435373565613030),
(13, 0x52653a4677643a52653a4677643a, 0x646166647366616473406461736626233033393b646673617c402671756f743b, '2010-12-01 12:07:22', 42, 43, 1, 4, 0x6161303137363239636261623731396337303834356231616563356132393836),
(14, 0x52653a4677643a52653a4677643a, 0x646166647366616473406461736626233033393b646673617c402671756f743b, '2010-12-01 12:07:22', 42, 43, 1, 3, 0x6161303137363239636261623731396337303834356231616563356132393836),
(15, 0x52653a20666473666473, 0x6664617366736466, '2010-12-01 12:19:24', 42, 43, 0, 4, 0x3536323436323637343531383061393165623938646363616164326633656435),
(16, 0x52653a20666473666473, 0x6664617366736466, '2010-12-01 12:19:24', 42, 43, 0, 3, 0x3536323436323637343531383061393165623938646363616164326633656435);

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