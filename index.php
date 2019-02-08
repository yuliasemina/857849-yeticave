<?php

set_include_path('D:\OSPanel\domains\857849-yeticave');
require ('functions.php') ;
require("templates/index.php");
require("templates/layout.php");

$is_auth = rand(0, 1);

/**
   * Фукция для вывода цены в формате с делением на разряды и добавлением знака рубля
   * функция принимает один аргумент — целое число.
   * 
   * @param $price = ceil($price) - округляет число.
   * @param $price = number_format($price, 0, ".", " ") - с помощью функции number_format делит число на разряды
   * @param $price .= " ₽" - добавляет к числу знак рубля
   *
   * @return $price - возвращает отформатированное число.
   *
   */

function price_format($price) {
    $price = ceil($price);
    $price = number_format($price, 0, ".", " ");
    $price .= " ₽";

    return $price;
};

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


$page_content = renderTemplate ('templates/index.php', ['categories' => $categories, 'lots' => $lots]);

$layout_content = renderTemplate ('templates/layout.php', ['main_content'=> $page_content, 'title_name' => 'Главная']);

print($layout_content);

?>
