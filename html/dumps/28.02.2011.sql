-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Время создания: Фев 28 2011 г., 15:28
-- Версия сервера: 5.0.27
-- Версия PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- База данных: `sound`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблицы `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `firstname` varchar(32) default NULL,
  `lastname` varchar(32) default NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;

-- 
-- Дамп данных таблицы `users`
-- 

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `username`, `password`, `crdate`, `modified`, `enabled`, `deleted`, `birthday`, `validation_id`, `role`, `avatar`) VALUES 
(4, 'Administrator', 'YouCademy', 'admin@youcademy.com', 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-04-26 13:29:14', '2011-02-01 17:37:00', 1, 0, NULL, '840c3eda3ea42ecd90aeb3434f3510b7', 'AR', 'storage/uploads/users/admin/profile/avatar.jpg'),
(40, 'Bill', 'Gates', 'bill@gmail.com', 'user', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-06-27 11:21:13', '2011-01-14 19:33:23', 1, 0, NULL, 'e0c7ccc47b2613c82d1073a4214deecc', 'UR', 'storage/uploads/users/tradesman2/profile/avatar.jpg'),
(43, 'Andrew', 'Stabryn', 'astabryn@gmail.com', 'astabryn', '5f4dcc3b5aa765d61d8327deb882cf99', '2010-10-07 13:42:52', '2010-12-04 22:47:50', 0, 0, '2000-09-07', '9a1de01f893e0d2551ecbb7ce4dc963e', 'UR', 'storage/uploads/users/user4/profile/avatar.jpg'),
(49, NULL, NULL, 'astabryn@ito-global.com', 'astabryn2', '5a86b2f3a6a168f8b39979ff73985689', '2011-02-28 16:45:28', '2011-02-28 20:32:48', 1, 0, NULL, '0ffd15f002b98f6bd62346b947b8d15a', 'UR', 'storage/uploads/users/astabryn2/profile/avatar.jpg');
