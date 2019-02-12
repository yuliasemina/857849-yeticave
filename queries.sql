INSERT INTO `categories`
(`name`) VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');


INSERT INTO `users` (`date_account`, `email`, `name`, `password`, `avatar`, `contact`)
VALUES 
('24.01.2018', 'user1@gmail.com', 'Катя', '12345', '', 'Москва, ул. Мира'),
('01.01.2019', 'user2@gmail.com', 'Гриша', '12345', '', 'Самара, ул. Виноградная');


INSERT INTO `lots` (`date_start`, `date_end`, `name`, `description`, `image`, `start_price`, `bet_step`, `user_id`, `category_id`, `user_winner`)
VALUES
('01.01.2019', '01.03.2019', '2014 Rossignol District Snowboard', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 
	'10999', '2', '1', '1', ''),

('10.01.2019', '06.03.2019', 'DC Ply Mens 2016/2017 Snowboard', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', '159999', '2', '1', '1', ''),

('01.02.2019', '02.03.2019', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 
	'8000', '1', '2', '2', ''),

('05.01.2019', '04.03.2019', 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 
	'10999', '2', '1', '3', ''),

('04.02.2019', '05.03.2019', 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 
	'7500', '1', '2', '4', ''),

('02.02.2019', '05.03.2019', 'Маска Oakley Canopy', 'Маска Oakley Canopy', 'img/lot-6.jpg', 
	'5400', '1', '2', '6', '');


INSERT INTO `bets` (`date_bets`, `sum_bets`, `user_id`, `lot_id`)
VALUES
('01.01.2019', '100', '2', '3'),
('24.01.2018', '1000', '1', '1');

