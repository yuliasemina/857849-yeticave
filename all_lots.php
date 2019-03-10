<?php

session_start();
require 'db.php';
require 'functions.php';

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

$layout_content ='';
$categories = get_categories($con);
$cat = get_cat_by_id($con, intval($_GET['id']));


$cur_page = $_GET['page'] ?? 1;
$page_items = 3;  
$offset = ($cur_page - 1) * $page_items;

$items_count = get_lot_list_by_cat_total($con, intval($_GET['id']));

$pages_count = ceil($items_count / $page_items);
$pages = range(1, $pages_count);


$lot_list = get_lot_list_by_cat ($con, intval($_GET['id']), $page_items, $offset);

if (!isset($cat['id'])){
  $layout_content = include_template('error.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]);

} else {

$title_name ='Все лоты «'.htmlspecialchars($cat['name']).'»';

$page_content = include_template('all_lots.php', [
    'pages' => $pages,
      'pages_count' => $pages_count,
      'cur_page' => $cur_page,
      'items_count' => $items_count,
      'cat' => $cat,
      'lots' => $lot_list, 
      'categories' => get_categories($con)
    ]);

$layout_content = include_template('layout_inner.php', [
  'categories' => get_categories($con), 
   'user_name' => $user_name, 
  'main_content'=> $page_content, 
  'title_name' => $title_name
]);
}

print($layout_content);