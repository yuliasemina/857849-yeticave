<?php

require 'db.php';
require 'data.php';
require 'functions.php';
session_start();

$user_name = '';
if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];  
  $user_name = $user['name'];
}  


$categories = get_categories($con);


    $cur_page = $_GET['page'] ?? 1;
    $page_items = 3;  
    $offset = ($cur_page - 1) * $page_items;

$cat = get_cat_by_id($con, intval($_GET['id']));

$lot_list = [];
$search = $_GET['q'] ?? '';
if ($search) {
$lot_list = get_lot_list_search($con, $search);
$items_count = count($lot_list);
$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);

}


$layout_content = include_template('search.php', 
    [
      'pages' => $pages,
      'pages_count' => $pages_count,
      'cur_page' => $cur_page,
      'lots' => $lot_list, 
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]);


print($layout_content);