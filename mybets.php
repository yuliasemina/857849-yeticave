<?php

require 'db.php';
require 'data.php';
require 'functions.php';
session_start();

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
$lot_list = get_lot_list_by_bets($con, $user_id);


$layout_content = include_template('mybets.php', [

	'categories' => $categories, 
	'lots' => $lot_list,
	'title_name' => $title_name, 
	'user_name' => $user_name, 
	'categories' => $categories
]);

print($layout_content);

?>
