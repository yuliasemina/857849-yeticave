<?php

require 'db.php';
require 'data.php';
require 'functions.php';
session_start();

$user_name = '';

if (!isset($_SESSION['user'])) {
  header('add error: 403');
  $layout_content = include_template('error403.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]);

} else {

  if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];  
    $user_name = $user['name'];
  }

  $categories = get_categories($con);
  $errors = [];

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = validate_form($_POST);

    if (empty($errors)) {
      $upload_dir = __DIR__ . '/uploads';
      if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755);
      }

      $file_name = uniqid() . $_FILES['image']['name'];
      if (move_uploaded_file($_FILES['image']['tmp_name'],  $upload_dir . '/' . $file_name)) {
       $file_path = 'uploads/' . $file_name;

       $lot_id = save_lot(
        $con,
        [  
          'date_end' => $_POST['date_end'],
          'name' => $_POST['name'],
          'description' => $_POST['description'],
          'image' => $file_path,
          'start_price' => $_POST['start_price'],
          'bet_step' => $_POST['bet_step'],
          'user_id' => 1,
          'category_id' => $_POST['category_id']
        ]
      ); 
       if ($lot_id > 0) {
        header("Location: /lot.php?id=$lot_id");
      } 
    } 
  }
}

$layout_content = include_template(
  'add.php', 
  [

    'lot' => [
      'date_end' => isset($_POST['date_end']) ? htmlspecialchars($_POST['date_end']) : '',
      'name' => isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '',
      'description' => isset($_POST['description']) ? htmlspecialchars($_POST['description']) : '',
      'start_price' => isset($_POST['start_price']) ? htmlspecialchars($_POST['start_price']) : '',
      'bet_step' => isset($_POST['bet_step']) ? htmlspecialchars($_POST['bet_step']) : '',
      'user_id' => 1, // $_POST['user_id'] ? htmlspecialchars($_POST['user_id']) : '',
      'category_id' => isset($_POST['category_id']) ? intval($_POST['category_id']) : ''
    ],
    
    'errors' => $errors, 
    'user_name' => $user_name, 
    'categories' => get_categories($con)
  ]);
}
print($layout_content);