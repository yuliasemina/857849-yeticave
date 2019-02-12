USE `857849-yeticave`;

INSERT INTO `categories`
(`name`) VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

INSERT INTO `users` (`date_account`, `email`, `name`, `password`, `avatar`, `contact`)
VALUES 
(NULL, 'user1@gmail.com', 'Катя', '12345', '', 'Москва, ул. Мира'),
(NULL, 'user2@gmail.com', 'Гриша', '12345', '', 'Самара, ул. Виноградная'),


(NULL, 'user3@gmail.com', 'Марина', '12345', '', 'Брянск, ул. Орловская'),
(NULL, 'user4@gmail.com', 'Света', '12345', '', 'Тверь, ул. Московская'),


(NULL, 'user5@gmail.com', 'Коля', '12345', '', 'Астрахань, ул. Камозина'),
(NULL, 'user6@gmail.com', 'Дима', '12345', '', 'Находка, ул. Медведева'),

(NULL, 'user7@gmail.com', 'Витя', '12345', '', 'Новосибирск, ул. Почтовая');


INSERT INTO `lots` (`date_start`, `date_end`, `name`, `description`, `image`, `start_price`, `bet_step`, `user_id`, `category_id`, `user_winner`)
VALUES
(NULL, '2019.03.01', '2014 Rossignol District Snowboard', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 
	'10999', '2', '1', '1', ''),

(NULL, '2019.02.01', 'DC Ply Mens 2016/2017 Snowboard', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', '159999', '2', '2', '1', ''),

(NULL, '2019.05.01', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 
	'8000', '1', '3', '2', ''),

(NULL, '2019.04.01', 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 
	'10999', '2', '4', '3', ''),

(NULL, '2019.05.01', 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 
	'7500', '1', '5', '4', ''),

(NULL, '2018.03.01', 'Маска Oakley Canopy', 'Маска Oakley Canopy', 'img/lot-6.jpg', 
	'5400', '1', '7', '6', '');

INSERT INTO `bets` (`date_bets`, `sum_bets`, `user_id`, `lot_id`)
VALUES
('2019.01.21', '100', '3', '1'),
('2019.03.01', '1000', '4', '2'),
('2019.02.05', '100', '1', '3'),
('2019.01.08', '1000', '2', '4'),
('2019.03.03', '100', '7', '5'),
('2019.01.10', '1000', '5', '6');


-- получить все категории;
SELECT * FROM `categories`;

/* получить самые новые, открытые лоты. 
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории; */

SELECT l.name, start_price, date_end, image, start_price + IFNULL (b.sum_bets, 0) AS price, c.name, '0' as lot_closed 
FROM lots l
JOIN categories c
ON l.category_id = c.id
LEFT JOIN bets b
ON b.lot_id = l.id
WHERE date_end > CURDATE()
ORDER BY l.date_start DESC;

--показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT l.name, c.name, l.date_start, l.date_end, l.image, l.start_price FROM lots l
JOIN categories c
ON l.category_id = c.id
WHERE l.id = 4;

--обновить название лота по его идентификатору;
UPDATE lots SET name = 'Ботинки DC Mutiny Charocal'
WHERE id = 4;

--получить список самых свежих ставок для лота по его идентификатору;
SELECT l.name, b.sum_bets, b.date_bets FROM lots l
JOIN bets b
ON l.id = b.lot_id
WHERE l.id = 3
ORDER BY b.date_bets DESC