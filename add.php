<?php
    
require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Юлия';
$lot = "";
$errors = [];
$dict =[];

$categories = get_categories($con);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $lot = $_POST;
  $required = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
  $dict = ['lot-name' => 'Наименование', 'category' => 'Категория', 'message' => 'Описание', 
          'lot-rate' => 'Начальная цена', 'lot-step' => 'Шаг ставки', 'lot-date' => 'Дата окончания торгов', 'img-file' => 'Изображение'];
  foreach ($required as $key) {
    if (empty($_POST[$key])) {
            $errors[$key] = 'Это поле необходимо заполнить';
    }
  }
    if (isset($_FILES['img-file']['name'])) {
      $tmp_name = $_FILES['img-file']['tmp_name'];
      $path = $_FILES['img-file']['name'];

      $finfo = finfo_open(FILEINFO_MIME_TYPE);
      $file_type = finfo_file($finfo, $tmp_name);

      if ($file_size > 2097152) {
          print("Максимальный размер файла: 2Мб");
       }

      if ($file_type !== "image/png") {
        $errors['img-file'] = 'Загрузите фото в формате PNG или JPG';
      }
      else {
        move_uploaded_file($tmp_name, 'uploads/' . $path);
        $lot['path'] = $path;
      }
    }
    else {
      $errors['img-file'] = 'Вы не загрузили файл';
    }

    if (empty($errors)) {
      $date_end  = $required['lot-date'];
      $name = $required['lot-name'];
      $description = $required['message'];
      $image = 'uploads/' . $path;
      $start_price = $required['lot-rate'];
      $bet_step = $required['lot-step'];
      $user_id = "1";
      $categories = get_categories_id($con, $required['category']);
      $category_id = $categories['id'];
      
      $new_lot = add_lot ($con, $date_end, $name, $description, $image, $start_price, $bet_step, $user_id, $category_id);
    }

}

$layout_content = include_template('add.php', 
  [
    'lot' => $lot, 
    'errors' => $errors, 
    'dict' => $dict,
  'content' => $page_content,
  'user_name' => $user_name, 
  'is_auth' => $is_auth, 
  'categories' => get_categories($con)
]);
 
print($layout_content);