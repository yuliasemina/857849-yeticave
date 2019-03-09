<?php

session_start();
require 'db.php';
require 'functions.php';

$user_name = '';
$user_id = '';

if (!isset($_SESSION['user'])) {
  http_response_code('403');
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

$layout_content = include_template(
  'add.php', 
  [

    'lot' => $lot_id,    
    'errors' => $errors, 
    'user_name' => $user_name, 
    'categories' => get_categories($con)
  ]);

print($layout_content);