<?php

require 'db.php';
require 'data.php';
require 'functions.php';
session_start();

$title_name = 'Главная';

$user_name = '';
if (isset($_SESSION['user'])) {
    $is_auth = 1;	
	$user = $_SESSION['user'];	
	$user_name = $user['name'];
}  

$categories = get_categories($con);
$lot_list = get_lot_list($con);

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lot_list]);

$layout_content = include_template('layout.php', ['main_content'=> $page_content, 'title_name' => $title_name, 
	'user_name' => $user_name, 'categories' => $categories]);

print($layout_content);

?>
