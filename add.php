<?php

session_start();
require 'db.php';
require 'functions.php';

$user_name = '';
$user_id = '';
$title_name = 'Добавление лота';
$lot=[];
if (!isset($_SESSION['user'])) {
  http_response_code(403);
  $layout_content = include_template('error403.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]);
  print($layout_content);
  exit;
}

$user = $_SESSION['user'];  
$user_name = $user['name'];
$user_id = $user['id'];

$categories = get_categories($con);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require 'add_processor.php';
}

$page_content = include_template('add.php', [
     'lot' => $lot,    
    'errors' => $errors, 
    'user_name' => $user_name, 
    'categories' => get_categories($con)
]);

$layout_content = include_template('layout_inner.php', [
  'categories' => $categories, 
  'main_content'=> $page_content, 
  'title_name' => $title_name, 
  'user_name' => $user_name
]);


print($layout_content);