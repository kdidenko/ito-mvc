-- phpMyAdmin SQL Dump
-- version 2.10.0.2
-- http://www.phpmyadmin.net
-- 
-- Хост: localhost
-- Час створення: Чрв 06 2010 р., 08:46
-- Версія сервера: 5.0.27
-- Версія PHP: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- БД: `youcademy`
-- 

-- --------------------------------------------------------

-- 
-- Структура таблиці `categories`
-- 

CREATE TABLE `categories` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Дамп даних таблиці `categories`
-- 

