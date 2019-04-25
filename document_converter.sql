-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Апр 24 2019 г., 22:16
-- Версия сервера: 10.1.19-MariaDB
-- Версия PHP: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `document_converter`
--

-- --------------------------------------------------------

--
-- Структура таблицы `common_templates`
--

CREATE TABLE `common_templates` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `contracts_common` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `acts_common` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bills_common` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `path` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `contractors`
--

CREATE TABLE `contractors` (
  `contractor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contractor_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `org_name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_creation` datetime NOT NULL,
  `phone` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `contractor_email` varchar(100) CHARACTER SET latin1 NOT NULL,
  `contractor_group` varchar(50) CHARACTER SET latin1 NOT NULL,
  `contractor_inn` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `contractor_kpp` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `contractor_ogrn` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `contractor_okpo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `contractor_sign_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pass_details` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `contractors`
--

INSERT INTO `contractors` (`contractor_id`, `user_id`, `contractor_name`, `org_name`, `address`, `date_of_creation`, `phone`, `contractor_email`, `contractor_group`, `contractor_inn`, `contractor_kpp`, `contractor_ogrn`, `contractor_okpo`, `contractor_sign_name`, `position`, `pass_details`, `comment`) VALUES
(4, 15, 'Pavel', '', '', '2019-04-12 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(5, 15, 'Misha', '', '', '2019-04-07 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(6, 15, 'Vitiya', '', '', '2019-04-16 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(7, 15, 'Sanya', '', '', '2019-04-09 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(8, 15, 'Petia', '', '', '2019-04-18 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(9, 15, 'Kerya', '', '', '2019-04-27 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(10, 15, 'Karich', '', '', '2019-04-10 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(11, 15, 'Krosh', 'Krosh inc.', 'Ð£Ð».Ð•ÑÐµÐ½Ð¸Ð½Ð° Ð´Ð¾Ð¼ ÐšÐ°Ñ€ÑƒÑÐµÐ»Ð¸Ð½Ð°', '2019-04-30 00:00:00', '+7(555) 666', 'Fukus@gmail.com', '', '333333332131', '222222222', '3123123212312', '3123333333', 'ÐÐ±Ð´ÑƒÐ»Ð°ÐµÐ² Ðš,Ð ,', 'Ð“Ð»Ð°Ð²Ð½Ñ‹Ð¹ , Ð¾Ñ‡ÐµÐ½ÑŒ', 'Ð¡ÐµÑ€Ð¸Ñ 6344  ÐÐ¾Ð¼ÐµÑ€ 4445323 ', 'ÐžÑ‚ Ð²ÑÐµÐ¹ Ð´ÑƒÑˆÐ¸, Ð¼Ð¾Ð¹ Ð¿ÐµÑ€Ð²Ñ‹Ð¹ ÐºÐ¾Ð½Ñ‚Ñ€Ð°Ð³ÐµÐ½Ñ‚'),
(12, 15, 'Sovunia', '', '', '2019-02-11 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(13, 15, 'Ejik', '', '', '2019-04-23 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(14, 15, 'kopatich', '', '', '2019-04-24 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', ''),
(16, 15, '12w', '', '', '2019-04-17 00:00:00', '0', '', '', '0', '0', '0', '0', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `contractors_banks`
--

CREATE TABLE `contractors_banks` (
  `id` int(11) NOT NULL,
  `contractor_id` int(11) NOT NULL,
  `rs` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Расчетный счет',
  `state` tinyint(1) NOT NULL COMMENT 'статус счета',
  `bank` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Банк пользователя',
  `bik` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `contractors_banks`
--

INSERT INTO `contractors_banks` (`id`, `contractor_id`, `rs`, `state`, `bank`, `bik`, `comment`) VALUES
(1, 11, '2147483647', 0, '', '0', ''),
(2, 11, '22222222222222222222', 0, '', '0', ''),
(3, 4, '313213213', 0, '', '0', '');

-- --------------------------------------------------------

--
-- Структура таблицы `measure_types`
--

CREATE TABLE `measure_types` (
  `id` int(11) NOT NULL,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `measure_types`
--

INSERT INTO `measure_types` (`id`, `title`) VALUES
(1, 'кг'),
(2, 'м'),
(3, 'м2'),
(4, 'м3'),
(5, 'тонна'),
(6, 'услуга'),
(7, 'час.'),
(8, 'шт.');

-- --------------------------------------------------------

--
-- Структура таблицы `reg_users`
--

CREATE TABLE `reg_users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `patronymic` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(40) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `reg_users`
--

INSERT INTO `reg_users` (`id`, `email`, `first_name`, `last_name`, `patronymic`, `password`) VALUES
(14, 'radiknalchik07@gmail.com', 'Grag', 'Vasya', '', '356a192b7913b04c54574d18c28d46e6395428ab'),
(15, 'bawer.kedrov@yandex.ru', 'Ð’Ð°Ð»ÐµÑ€Ð¸Ñ', 'ÐœÐ¸Ñ€Ð¾Ð½Ð¾Ð²Ð°', 'Ð›ÐµÐ¾Ð½Ð¸Ð´Ð¾Ð²Ð½Ð°', '356a192b7913b04c54574d18c28d46e6395428ab'),
(21, 'maniilsiluanov@gmail.com', 'Host', 'Petya', '', '356a192b7913b04c54574d18c28d46e6395428ab'),
(22, 'dada@gmail.com', 'Gots', 'vvvv', '', '40bd001563085fc35165329ea1ff5c5ecbdbbeef'),
(24, 'daniilsiluanov@gmail.com', 'Grag', 'dwqdwq', '', '356a192b7913b04c54574d18c28d46e6395428ab'),
(37, 'ffff@mail.ru', 'Mia', 'Vava', 'Rio', '356a192b7913b04c54574d18c28d46e6395428ab'),
(59, 'dfwsdfs@ffff.com', 'dsdfsdfs', 'Vasya', '', '356a192b7913b04c54574d18c28d46e6395428ab'),
(60, 'daniifflsiluanov@gmail.com', 'Host', 'Vasya', '', '356a192b7913b04c54574d18c28d46e6395428ab'),
(61, 'daniilsilluanov@gmail.com', 'Host', 'Vasya', '', '356a192b7913b04c54574d18c28d46e6395428ab');

-- --------------------------------------------------------

--
-- Структура таблицы `users_banks`
--

CREATE TABLE `users_banks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rs` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Расчетный счет',
  `state` tinyint(1) NOT NULL COMMENT 'статус счета',
  `bank` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT 'Банк пользователя',
  `bik` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users_banks`
--

INSERT INTO `users_banks` (`id`, `user_id`, `rs`, `state`, `bank`, `bik`, `comment`) VALUES
(1, 15, '40802810214400000177', 0, 'Сбербанк', '132133123', 'аааааа'),
(2, 14, '3121231', 0, '', '0', ''),
(3, 15, '40802810214400000312', 0, 'Центр Инвест', '458795125', 'Лучший расчетный счет');

-- --------------------------------------------------------

--
-- Структура таблицы `users_organization`
--

CREATE TABLE `users_organization` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip_inn` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `okpo` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `ogrnip` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `date_reg` date NOT NULL,
  `okved` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `fio_sign` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'фактический адрес',
  `client_phone` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'телефон для клиентов',
  `client_email` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'почта для клиентов',
  `website` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'сайт для ознакомления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users_organization`
--

INSERT INTO `users_organization` (`id`, `user_id`, `ip_inn`, `okpo`, `ogrnip`, `date_reg`, `okved`, `fio_sign`, `address`, `client_phone`, `client_email`, `website`) VALUES
(3, 15, '667204591180', '1112124568', '310730201900021', '2016-02-12', '222222', 'Ð¨Ð°Ñ…Ð½Ð°Ð·Ð°Ñ€Ð¾Ð² Ð˜ . Ð˜.', 'Ð£Ð» Ð•ÑÐµÐ½Ð¸Ð½Ð° Ð´Ð¾Ð¼ ÐšÐ°Ñ€ÑƒÑÐµÐ»Ð¸Ð½Ð°', '88888888888', 'moyapochta@gmail.com', 'www.youtube.com'),
(4, 24, '', '', '', '0000-00-00', '', '', '', '', '', ''),
(5, 37, '', '', '', '2012-12-10', '', '', 'Ð£Ð».ÐŸÑƒÑˆÐºÐ¸Ð½Ð° Ð´Ð¾Ð¼ ÐšÐ°Ð»Ð°Ñ‚ÑƒÑˆÐºÐ¸Ð½Ð¾', '88005553535', 'danya@gmail.com', 'www.youtube.com');

-- --------------------------------------------------------

--
-- Структура таблицы `user_docs`
--

CREATE TABLE `user_docs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `doc_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `doc_type` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `date_of_creation` datetime NOT NULL,
  `document` varchar(64) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user_docs`
--

INSERT INTO `user_docs` (`id`, `user_id`, `doc_name`, `doc_type`, `date_of_creation`, `document`) VALUES
(1, 37, '', '', '0000-00-00 00:00:00', '0');

-- --------------------------------------------------------

--
-- Структура таблицы `user_templates`
--

CREATE TABLE `user_templates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title_contract` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `title_act` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `title_bill` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `date_created` date NOT NULL,
  `path` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `common_templates`
--
ALTER TABLE `common_templates`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `contractors`
--
ALTER TABLE `contractors`
  ADD PRIMARY KEY (`contractor_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `contractors_banks`
--
ALTER TABLE `contractors_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contractor_id` (`contractor_id`);

--
-- Индексы таблицы `measure_types`
--
ALTER TABLE `measure_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `reg_users`
--
ALTER TABLE `reg_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users_banks`
--
ALTER TABLE `users_banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users_organization`
--
ALTER TABLE `users_organization`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `user_docs`
--
ALTER TABLE `user_docs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `user_templates`
--
ALTER TABLE `user_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `common_templates`
--
ALTER TABLE `common_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `contractors`
--
ALTER TABLE `contractors`
  MODIFY `contractor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT для таблицы `contractors_banks`
--
ALTER TABLE `contractors_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `measure_types`
--
ALTER TABLE `measure_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT для таблицы `reg_users`
--
ALTER TABLE `reg_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- AUTO_INCREMENT для таблицы `users_banks`
--
ALTER TABLE `users_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `users_organization`
--
ALTER TABLE `users_organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `user_docs`
--
ALTER TABLE `user_docs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT для таблицы `user_templates`
--
ALTER TABLE `user_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `contractors`
--
ALTER TABLE `contractors`
  ADD CONSTRAINT `contractors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `reg_users` (`id`);

--
-- Ограничения внешнего ключа таблицы `contractors_banks`
--
ALTER TABLE `contractors_banks`
  ADD CONSTRAINT `contractors_banks_ibfk_1` FOREIGN KEY (`contractor_id`) REFERENCES `contractors` (`contractor_id`);

--
-- Ограничения внешнего ключа таблицы `users_banks`
--
ALTER TABLE `users_banks`
  ADD CONSTRAINT `users_banks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `reg_users` (`id`);

--
-- Ограничения внешнего ключа таблицы `users_organization`
--
ALTER TABLE `users_organization`
  ADD CONSTRAINT `users_organization_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `reg_users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_docs`
--
ALTER TABLE `user_docs`
  ADD CONSTRAINT `user_docs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `reg_users` (`id`);

--
-- Ограничения внешнего ключа таблицы `user_templates`
--
ALTER TABLE `user_templates`
  ADD CONSTRAINT `user_templates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `reg_users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
