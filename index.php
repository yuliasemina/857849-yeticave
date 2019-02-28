<?php

require 'db.php';
require 'data.php';
require 'functions.php';
session_start();

$is_auth = 0;

$user_name = 'Юлия';
$title_name = 'Главная';

$categories = get_categories($con);
$lot_list = get_lot_list($con);

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lot_list]);

$layout_content = include_template('layout.php', ['main_content'=> $page_content, 'title_name' => $title_name, 'user_name' => $user_name, 'categories' => $categories, 
	'is_auth' => $is_auth]);

print($layout_content);

?>
