INSERT INTO `categories`
(`name`) VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

INSERT INTO `users` (`date_account`, `email`, `name`, `password`, `avatar`, `contact`)
VALUES 
('24.01.2018', 'user1@gmail.com', 'Катя', '12345', '', 'Москва, ул. Мира'),
('01.01.2019', 'user2@gmail.com', 'Гриша', '12345', '', 'Самара, ул. Виноградная');

INSERT INTO `lots` (`date_start`, `date_end`, `name`, `description`, `image`, `start_price`, `bet_step`, `user_id`, `category_id`, `user_winner`)
VALUES
('01.01.2019', '2019.03.01', '2014 Rossignol District Snowboard', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 
	'10999', '2', '1', '1', ''),

('2018.11.01', '2019.02.01', 'DC Ply Mens 2016/2017 Snowboard', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', '159999', '2', '1', '1', ''),

('2019.02.01', '2019.05.01', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 
	'8000', '1', '2', '2', ''),

('2019.01.01', '2019.04.01', 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 
	'10999', '2', '1', '3', ''),

('2019.02.01', '2019.05.01', 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 
	'7500', '1', '2', '4', ''),

('2018.01.01', '2018.03.01', 'Маска Oakley Canopy', 'Маска Oakley Canopy', 'img/lot-6.jpg', 
	'5400', '1', '2', '6', '');

INSERT INTO `bets` (`date_bets`, `sum_bets`, `user_id`, `lot_id`)
VALUES
('2019.01.01', '100', '2', '3'),
('2019.01.01', '1000', '1', '1');


-- получить все категории;
SELECT (`name`) FROM `categories`;

/* получить самые новые, открытые лоты. 
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории; */

SELECT l.name, start_price, date_end, image, start_price + COALESCE (b.sum_bets, 0) AS price, c.name FROM lots l
JOIN categories c
ON l.category_id = c.id
LEFT JOIN bets b
ON b.lot_id = l.id
WHERE date_end > CURDATE();

--показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT l.name, c.name FROM lots l
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
WHERE l.id = 3 AND b.date_bets > '2018.12.31';