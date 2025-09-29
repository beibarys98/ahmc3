-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Сен 25 2025 г., 14:44
-- Версия сервера: 10.6.21-MariaDB-cll-lve
-- Версия PHP: 8.4.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `p-347124_ahmc2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `answer`
--

CREATE TABLE `answer` (
  `id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `answer` varchar(1000) NOT NULL,
  `img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE `category` (
  `id` int(10) UNSIGNED NOT NULL,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `category`
--

INSERT INTO `category` (`id`, `title_kz`, `title_ru`) VALUES
(1, 'Білім жетілдіру', 'Повышение квалификации'),
(2, 'Сертификаттау курсы', 'Сертификационный курс'),
(3, 'Семинар, тренинг, шебер-класс', 'Семинар, тренинг, мастер-класс'),
(4, 'Съезд, конгресс, конференциялар', 'Съезд, конгресс, конференции'),
(5, 'Медициналық білімі жоқ адамдар \n(парамедик)', 'Лица без медицинского образование (парамедик)'),
(6, 'Білім жетілдіру', 'Повышение квалификации'),
(7, 'Сертификаттау курсы', 'Сертификационный курс'),
(8, 'Семинар, тренинг, шебер-класс', 'Семинар, тренинг, мастер-класс'),
(9, 'Съезд, конгресс, конференциялар', 'Съезд, конгресс, конференции'),
(10, 'Медициналық білімі жоқ адамдар \n(парамедик)', 'Лица без медицинского образование (парамедик)');

-- --------------------------------------------------------

--
-- Структура таблицы `course`
--

CREATE TABLE `course` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `course`
--

INSERT INTO `course` (`id`, `category_id`, `title_kz`, `title_ru`) VALUES
(1, 1, 'Мейіргер ісі', 'Сестринское дело'),
(2, 1, 'Акушерлік іс', 'Акушерское дело'),
(3, 1, 'Емдеу ісі', 'Лечебное дело'),
(4, 1, 'Зертханалық диагностика', 'Лабораторная диагностика'),
(5, 1, 'Фармация ', 'Фармация '),
(6, 1, 'Стоматология ', 'Стоматология '),
(7, 1, 'Эпидемиология және гигиена ', 'Эпидемиология и гигиена'),
(8, 1, 'Әлеуметтік жұмысшы', 'Соц работник'),
(9, 2, 'Жалпы тәжірибелік мейіргер', 'Медсестра общей практики'),
(10, 2, 'Рентгенологиядағы мейіргер ісі', 'Сестринское дело в рентгенологии'),
(11, 2, 'Классикалық және емдік массаж ', 'Классический и лечебный массаж'),
(12, 2, 'Мектеп медицинасындағы мейіргер ісі ', 'Сестринское дело в школьной медицине'),
(13, 3, 'Семинар, тренинг, шебер-класс', 'Семинар, тренинг, мастер-класс'),
(14, 4, 'Съезд, конгресс, конференциялар', '	Съезд, конгресс, конференции'),
(15, 5, 'Медициналық білімі жоқ адамдар (парамедик)', 'Лица без медицинского образование (парамедик)');

-- --------------------------------------------------------

--
-- Структура таблицы `cycle`
--

CREATE TABLE `cycle` (
  `id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `month` varchar(50) DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `cycle`
--

INSERT INTO `cycle` (`id`, `course_id`, `title_kz`, `title_ru`, `month`, `duration`) VALUES
(3, 2, 'акушерлікке цикл косу тексеру', 'создать цикл акушерству проверка', 'Қаңтар', '2 апта');

-- --------------------------------------------------------

--
-- Структура таблицы `file`
--

CREATE TABLE `file` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `cycle_id` int(10) UNSIGNED NOT NULL,
  `file_type_id` int(10) UNSIGNED NOT NULL,
  `path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `file`
--

INSERT INTO `file` (`id`, `user_id`, `cycle_id`, `file_type_id`, `path`) VALUES
(24, 9, 3, 1, 'uploads/68bfb8e4c5acd.pdf'),
(25, 9, 3, 2, 'uploads/68bfb9041cd67.pdf'),
(26, 9, 3, 3, 'uploads/68bfb90aeae2b.pdf'),
(27, 9, 3, 4, 'uploads/68bfb9116e8ae.pdf'),
(28, 9, 3, 5, 'uploads/68bfb9182586c.pdf'),
(29, 9, 3, 6, 'uploads/68bfb9539f4f3.pdf');

-- --------------------------------------------------------

--
-- Структура таблицы `file_type`
--

CREATE TABLE `file_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `file` varchar(255) NOT NULL,
  `type` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `file_type`
--

INSERT INTO `file_type` (`id`, `file`, `type`) VALUES
(1, 'Жеке куәлік ', 'required'),
(2, 'Білім туралы диплом ', 'required'),
(3, 'Денсаулық сақтау маманының сертификаты ', 'required'),
(4, 'Біліктілікті арттыру туралы куәлігі ', 'required'),
(5, 'Неке туралы куәлік (тегін өзгерткен жағдайда)', 'optional'),
(6, 'Мекеме ұйымынан  бұйрық (бюджеттік негізде тыңдаушылар үшін)', 'budget'),
(7, 'Төлем туралы түбіртек (келісім шарт бойынша тыңдаушылар үшін)', 'contract');

-- --------------------------------------------------------

--
-- Структура таблицы `question`
--

CREATE TABLE `question` (
  `id` int(10) UNSIGNED NOT NULL,
  `test_id` int(10) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer` int(11) DEFAULT NULL,
  `img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `test`
--

CREATE TABLE `test` (
  `id` int(10) UNSIGNED NOT NULL,
  `cycle_id` int(10) UNSIGNED NOT NULL,
  `title_kz` varchar(255) NOT NULL,
  `title_ru` varchar(255) NOT NULL,
  `status` varchar(50) NOT NULL,
  `duration` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `organization` varchar(255) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`id`, `username`, `name`, `organization`, `password_hash`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', '$2y$13$UEA9i7uVx20A/qnXGQ/D6uQiDjZrdPH84aup561RFH0.HmP3A2Wa2', 1750917648, 1750917648),
(7, '87053792011', 'Даулетов  Н', '9656526', NULL, 1752056950, 1752057052),
(8, '87018214891', 'Серікова Әсел Досымбекқызы', 'Темір РБ', NULL, 1757393369, 1757393369),
(9, '87081568161', 'Қуанышбаева Гулнар', 'Мед колледж', NULL, 1757395001, 1757395001),
(10, '87000222094', 'Шайқы Айым', 'Мед колледд', NULL, 1757395561, 1757395561),
(11, '87081568168', 'Қуанышбаева Гулнар', 'Мед колледж', NULL, 1758171565, 1758171565),
(12, '87081568162', 'Қуанышбаева Гулнар', 'Мед колледж', NULL, 1758171629, 1758171629),
(13, '87053792012', 'Даулетов Нурболат', 'Мед колледж', NULL, 1758171706, 1758171706);

-- --------------------------------------------------------

--
-- Структура таблицы `user_answer`
--

CREATE TABLE `user_answer` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `question_id` int(10) UNSIGNED NOT NULL,
  `answer_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_cycle`
--

CREATE TABLE `user_cycle` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED DEFAULT NULL,
  `cycle_id` int(10) UNSIGNED DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `user_test`
--

CREATE TABLE `user_test` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `test_id` int(10) UNSIGNED NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `result` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_answer_question` (`question_id`);

--
-- Индексы таблицы `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_category` (`category_id`);

--
-- Индексы таблицы `cycle`
--
ALTER TABLE `cycle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cycle_course` (`course_id`);

--
-- Индексы таблицы `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_ibfk_1` (`user_id`),
  ADD KEY `file_ibfk_2` (`cycle_id`),
  ADD KEY `file_ibfk_3` (`file_type_id`);

--
-- Индексы таблицы `file_type`
--
ALTER TABLE `file_type`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `question`
--
ALTER TABLE `question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_question_test` (`test_id`);

--
-- Индексы таблицы `test`
--
ALTER TABLE `test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_test_cycle` (`cycle_id`);

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Индексы таблицы `user_answer`
--
ALTER TABLE `user_answer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_answer_user` (`user_id`),
  ADD KEY `fk_user_answer_question` (`question_id`),
  ADD KEY `fk_user_answer_answer` (`answer_id`);

--
-- Индексы таблицы `user_cycle`
--
ALTER TABLE `user_cycle`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_cycle_ibfk_2` (`cycle_id`),
  ADD KEY `fk_user_cycle_course` (`course_id`);

--
-- Индексы таблицы `user_test`
--
ALTER TABLE `user_test`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `test_id` (`test_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1008;

--
-- AUTO_INCREMENT для таблицы `category`
--
ALTER TABLE `category`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `course`
--
ALTER TABLE `course`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `cycle`
--
ALTER TABLE `cycle`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `file`
--
ALTER TABLE `file`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT для таблицы `file_type`
--
ALTER TABLE `file_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `question`
--
ALTER TABLE `question`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=217;

--
-- AUTO_INCREMENT для таблицы `test`
--
ALTER TABLE `test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `user_answer`
--
ALTER TABLE `user_answer`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT для таблицы `user_cycle`
--
ALTER TABLE `user_cycle`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `user_test`
--
ALTER TABLE `user_test`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `answer`
--
ALTER TABLE `answer`
  ADD CONSTRAINT `fk_answer_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `fk_course_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `cycle`
--
ALTER TABLE `cycle`
  ADD CONSTRAINT `fk_cycle_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `file`
--
ALTER TABLE `file`
  ADD CONSTRAINT `file_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `file_ibfk_2` FOREIGN KEY (`cycle_id`) REFERENCES `cycle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `file_ibfk_3` FOREIGN KEY (`file_type_id`) REFERENCES `file_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `question`
--
ALTER TABLE `question`
  ADD CONSTRAINT `fk_question_test` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `test`
--
ALTER TABLE `test`
  ADD CONSTRAINT `fk_test_cycle` FOREIGN KEY (`cycle_id`) REFERENCES `cycle` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_answer`
--
ALTER TABLE `user_answer`
  ADD CONSTRAINT `fk_user_answer_answer` FOREIGN KEY (`answer_id`) REFERENCES `answer` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_answer_question` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_answer_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_cycle`
--
ALTER TABLE `user_cycle`
  ADD CONSTRAINT `fk_user_cycle_course` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cycle_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_cycle_ibfk_2` FOREIGN KEY (`cycle_id`) REFERENCES `cycle` (`id`) ON DELETE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user_test`
--
ALTER TABLE `user_test`
  ADD CONSTRAINT `user_test_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_test_ibfk_2` FOREIGN KEY (`test_id`) REFERENCES `test` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
