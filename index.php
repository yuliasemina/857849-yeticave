<?php
$link = mysqli_init();
mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1); 

$con = mysqli_connect('localhost', 'root', '', '857849-yeticave');

if ($con == false) {
    print('Ошибка подключения: '. mysqli_connect_error());
}
else {
    mysqli_set_charset($con, 'utf8');
    
    $lot_list_sql = "SELECT
        `l`.`name` AS 'title',
        `l`.`start_price` AS 'price',
        `l`.`image` AS 'url_img',
        `l`.`date_end` AS 'date_end',
        MAX(`b`.`sum_bets`) `max_price`,
        `c`.`name` AS `category`
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
        `l`.`start_at` DESC";

    $lot_list_result = mysqli_query($con, $lot_list_sql);
        if (!$lot_list_result) {
            $error = mysqli_error($con);
            print($error);
        }
        else {
            $lot_list = mysqli_fetch_all($lot_list_result, MYSQLI_ASSOC);
        };

    $categories_footer_sql = "SELECT `name` AS `category` FROM `categories`";

    $categories_footer_result = mysqli_query($con, $categories_footer_sql);
        if (!$categories_footer_result) {
            $error = mysqli_error($con);
            print($error);
        }
        else {
            $categories_footer = mysqli_fetch_all($categories_footer_result, MYSQLI_ASSOC);
        };
}


require 'functions.php' ;

$is_auth = rand(0, 1);

$categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];
$lots = [
    [
       'title' => '2014 Rossignol District Snowboard',
       'category' => 'Доски и лыжи',
       'price' => '10999',
       'url_img' => 'img/lot-1.jpg'
   ],
   [
       'title' => 'DC Ply Mens 2016/2017 Snowboard',
       'category' => 'Доски и лыжи',
       'price' => '159999',
       'url_img' => 'img/lot-2.jpg'
   ],
   [
       'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
       'category' => 'Крепления',
       'price' => '8000',
       'url_img' => 'img/lot-3.jpg'
   ],
   [
       'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
       'category' => 'Ботинки',
       'price' => '10999',
       'url_img' => 'img/lot-4.jpg'
   ],
   [
       'title' => 'Куртка для сноуборда DC Mutiny Charocal',
       'category' => 'Одежда',
       'price' => '7500',
       'url_img' => 'img/lot-5.jpg'
   ],
   [
       'title' => 'Маска Oakley Canopy',
       'category' => 'Разное',
       'price' => '5400',
       'url_img' => 'img/lot-6.jpg'
   ]
];
$user_name = 'Юлия';
$title_name = 'Главная';

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lot_list]);


$layout_content = include_template('layout.php', ['main_content'=> $page_content, 'user_name' => $user_name, 'categories' => $categories, 
    'categories_footer' => $categories_footer, 'title_name' => $title_name, 'is_auth' => $is_auth]);

print($layout_content);

?>
