<?php
session_start();
require 'db.php';
require 'functions.php';

$user_name = '';
if (isset($_SESSION['user'])) {
	$user = $_SESSION['user'];  
	$user_name = $user['name'];
}  

$categories = get_categories($con);
$search = trim($_GET['search']) ?? ''; // для вывода искомого слова на экран
$search_all = '*'.$search.'*' ?? '';


if (strlen($search)>=3) {


	$cur_page = $_GET['page'] ?? 1;
	$page_items = 3;  
	$offset = ($cur_page - 1) * $page_items;

	$items_count = get_lot_list_search_total ($con, htmlspecialchars($search_all));

	$pages_count = ceil($items_count / $page_items);
	$pages = range(1, $pages_count);


	$lot_list = get_search_lot_list ($con, $search_all, $page_items, $offset);

	$layout_content = include_template('search.php', 
		[
			'pages' => $pages,
			'pages_count' => $pages_count,
			'cur_page' => $cur_page,
			'items_count' => $items_count,
			'search' => $search,
			'lots' => $lot_list, 
			'user_name' => $user_name, 
			'categories' => get_categories($con)
		]);

print($layout_content);
}
header("Location: /index.php");