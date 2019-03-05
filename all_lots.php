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


if (!isset($_GET['id'])) {
  echo include_template('error.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]
  );
  exit;
}

$categories = get_categories($con);
$cat = get_cat_by_id($con, intval($_GET['id']));


$cur_page = $_GET['page'] ?? 1;
$page_items = 3;  
$offset = ($cur_page - 1) * $page_items;

$items_count = count(get_lot_list_by_cat_result($con, intval($_GET['id'])));

$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);


$lot_list = get_lot_list_by_cat ($con, intval($_GET['id']), $page_items, $offset);

if (is_null($cat['id'])){
  $layout_content = include_template('error.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]);

} else {
  $layout_content = include_template('all_lots.php', 
    [
      'pages' => $pages,
      'pages_count' => $pages_count,
      'cur_page' => $cur_page,
      '$items_count' => $items_count,
      'cat' => $cat,
      'lots' => $lot_list, 
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]);

}

print($layout_content);