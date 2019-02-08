<?php

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

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lots]);


$layout_content = include_template('layout.php', ['main_content'=> $page_content, 'user_name' => $user_name, 'categories' => $categories,
 'title_name' => $title_name, 'is_auth' => $is_auth]);

print($layout_content);

?>
