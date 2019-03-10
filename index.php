<?php
session_start();
require_once 'vendor/autoload.php';
require 'db.php';
require 'functions.php';
require_once 'getwinner.php';

$title_name = 'Главная';

$user_name = '';
$user_id = null;
if (isset($_SESSION['user'])) {
	$is_auth = 1;	
	$user = $_SESSION['user'];	
	$user_name = $user['name'];
	$user_id = $user['id'];
}  

$categories = get_categories($con);
$lot_list = get_lot_list($con);

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lot_list]);

$layout_content = include_template('layout.php', [
	'main_content'=> $page_content, 
	'title_name' => $title_name, 
	'user_name' => $user_name, 
	'categories' => $categories
]);
print($layout_content);
