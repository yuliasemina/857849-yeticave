<?php

require 'db.php';
require 'data.php';
require 'functions.php';
session_start();

$is_auth = 0;
$user_name = '';
if (isset($_SESSION['user'])) {
    $is_auth = 1; 
  $user = $_SESSION['user'];  
  $user_name = $user['name'];
}  


if (!isset($_GET['id'])) {
  echo include_template('error.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con), 
      'is_auth' => $is_auth
    ]
  );
  exit;
}

$categories = get_categories($con);

$lot_list = get_lot_list_by_cat ($con, intval($_GET['id']));
$cat = get_cat_by_id($con, intval($_GET['id']));

if (is_null($cat['id'])){
  $layout_content = include_template('error.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con), 
      'is_auth' => $is_auth
    ]);

} else {
  $layout_content = include_template('all_lots.php', 
    [
      'lots' => $lot_list, 
      'user_name' => $user_name, 
      'categories' => get_categories($con), 
      'is_auth' => $is_auth
    ]);

}
print($layout_content);