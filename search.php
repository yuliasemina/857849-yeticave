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

$search = trim($_GET['search']) ?? '';
$categories = get_categories($con);
$lot_list = get_lot_list_search($con, $search);


$layout_content = include_template('search.php', 
  [
    'lots' => $lot_list, 
    'user_name' => $user_name, 
    'categories' => get_categories($con)
  ]);

var_dump($lot_list);
print($layout_content);