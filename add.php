<?php

require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Юлия';

$categories = get_categories($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors = validate_form($_POST);

  if (empty($errors)) {
    $upload_dir = __DIR__ . '/uploads';
    if (!is_dir($upload_dir)) {
      mkdir($upload_dir, 0755);
    }
    if (move_uploaded_file($_FILES['image']['tmp_name'],  $upload_dir . '/' . $_FILES['image']['name']))
     $file_name = 'uploads/' . $_FILES['image']['name'];
   {

    $lot_id = save_lot(
      $con,
      [  
        'date_end' => $_POST['date_end'],
        'name' => $_POST['name'],
        'description' => $_POST['description'],
        'image' => $file_name,
        'start_price' => $_POST['start_price'],
        'bet_step' => $_POST['bet_step'],
        'user_id' => 1,
        'category_id' => $_POST['category_id']
      ]
    ); 

  } 
  

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: /lot.php?id=$lot_id");
  }
} 

}

$layout_content = include_template(
  'add.php', 
  [
    'lot' => [
      'date_end' => $_POST['date_end'] ? htmlspecialchars($_POST['date_end']) : '',
      'name' => $_POST['name'] ? htmlspecialchars($_POST['name']) : '',
      'description' => $_POST['description'] ? htmlspecialchars($_POST['description']) : '',
      'start_price' => $_POST['start_price'] ? htmlspecialchars($_POST['start_price']) : '',
      'bet_step' => $_POST['bet_step'] ? htmlspecialchars($_POST['bet_step']) : '',
      'user_id' => $_POST['user_id'] ? htmlspecialchars($_POST['user_id']) : '',
      'category_id' => $_POST['category_id'] ? htmlspecialchars($_POST['category_id']) : ''
    ],
    
    'errors' => $errors, 
    'user_name' => $user_name, 
    'is_auth' => $is_auth, 
    'categories' => get_categories($con)
  ]);

print($layout_content);