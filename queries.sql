USE `857849-yeticave`;

INSERT INTO `categories`
(`name`) VALUES ('Доски и лыжи'), ('Крепления'), ('Ботинки'), ('Одежда'), ('Инструменты'), ('Разное');

INSERT INTO `users` (`email`, `name`, `password`, `contact`)
VALUES 
('user1@gmail.com', 'Катя', '12345', 'Москва, ул. Мира'),
('user2@gmail.com', 'Гриша', '12345', 'Самара, ул. Виноградная'),
('user3@gmail.com', 'Марина', '12345','Брянск, ул. Орловская'),
('user4@gmail.com', 'Света', '12345', 'Тверь, ул. Московская'),
('user5@gmail.com', 'Коля', '12345', 'Астрахань, ул. Камозина'),
('user6@gmail.com', 'Дима', '12345', 'Находка, ул. Медведева'),
('user7@gmail.com', 'Витя', '12345', 'Новосибирск, ул. Почтовая');


INSERT INTO `lots` (`date_end`, `name`, `description`, `image`, `start_price`, `bet_step`, `user_id`, `category_id`)
VALUES
('2019-03-01', '2014 Rossignol District Snowboard', '2014 Rossignol District Snowboard', 'img/lot-1.jpg', 
	10999, 1000, 1, 1),

('2019-02-01', 'DC Ply Mens 2016/2017 Snowboard', 'DC Ply Mens 2016/2017 Snowboard', 'img/lot-2.jpg', 159999, 10000, 2, 1),

('2019-05-01', 'Крепления Union Contact Pro 2015 года размер L/XL', 'Крепления Union Contact Pro 2015 года размер L/XL', 'img/lot-3.jpg', 
	8000, 1000, 3, 2),

('2019-04-01', 'Ботинки для сноуборда DC Mutiny Charocal', 'Ботинки для сноуборда DC Mutiny Charocal', 'img/lot-4.jpg', 
	10999, 1000, 4, 3),

('2019-05-01', 'Куртка для сноуборда DC Mutiny Charocal', 'Куртка для сноуборда DC Mutiny Charocal', 'img/lot-5.jpg', 
	7500, 500, 5, 4),

('2018-03-01', 'Маска Oakley Canopy', 'Маска Oakley Canopy', 'img/lot-6.jpg', 5400, 500, 7, 6);

INSERT INTO `bets` (`sum_bets`, `user_id`, `lot_id`)
VALUES
(12500, 3, 1),
(170000, 4, 2),
(10000, 1, 3),
(15000, 2, 1),
(190000, 7, 2),
(11000, 5, 3);


-- получить все категории;
SELECT * FROM `categories`;

/* получить самые новые, открытые лоты. 
Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории; */

SELECT
       `l`.`name`,
       `l`.`start_price`,
       `l`.`image`,
       MAX(`b`.`sum_bets`) `max_price`,
       `c`.`name` AS `category_name`
FROM
      `lots` `l`
INNER JOIN
      `categories` `c`
      ON `l`.`category_id` = `c`.`id`
LEFT JOIN
      `bets` `b`
      ON `b`.`lot_id` = `l`.`id`
WHERE
        `l`.`date_end` > CURDATE()
        AND `l`.`winner_id` IS NULL
GROUP BY
      `l`.`id`
ORDER BY
         `l`.`start_at` DESC;


-- показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT `l`.*, `c`.`name` `category_name` FROM `lots` `l`
JOIN `categories` `c`
ON `l`.`category_id` = `c`.`id`
WHERE `l`.`id` = 4;

-- обновить название лота по его идентификатору;
UPDATE `lots` SET `name` = 'Ботинки DC Mutiny Charocal'
WHERE `id` = 4;

-- получить список самых свежих ставок для лота по его идентификатору;
SELECT `l`.`name`, `b`.`sum_bets`, `b`.`bet_at` FROM `lots` `l`
JOIN `bets` `b`
ON `l`.`id` = `b`.`lot_id`
WHERE `l`.`id` = 3
ORDER BY `b`.`bet_at` DESC