<?php
    
require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Юлия';

$categories = get_categories($con);

$lot = get_lot_by_id($con, intval($_GET['id']));


/*$sql = "SELECT * FROM lots WHERE id = ?"; -- работает
$res = mysqli_prepare($con, $sql);
$stmt = db_get_prepare_stmt($con, $sql, [$_GET['id']]);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$lot = mysqli_fetch_assoc($res);
var_dump($lot);
*/

if (is_null($lot['id'])){
  //header("Location: /error.php/");
  //exit();
$layout_content = include_template('error.php', 
  [
  'user_name' => $user_name, 
  'categories' => get_categories($con), 
  'is_auth' => $is_auth
]);

} else {

$layout_content = include_template('lot.php', 
  [
  'lot' => $lot, 
  'user_name' => $user_name, 
  'categories' => get_categories($con), 
  'is_auth' => $is_auth
]);
 
 }
print($layout_content);