<?php
    
require 'db.php';
require 'data.php';
require 'functions.php';


$is_auth = rand(0, 1);
$user_name = 'Юлия';

$categories = get_categories($con);



//$lot_id = intval($_GET['id']);
//$lot = get_lot_by_id($con, $lot_id);

$sql = get_lot_by_id($con, $lot_id);

$stmt = db_get_prepare_stmt($con, $sql, [$_GET['id']]);

//$lot = mysqli_fetch_all($res, MYSQLI_ASSOC);




$layout_content = include_template('lot.php', 
  [
  'lot' => $lot, 
  'user_name' => $user_name, 
  'categories' => get_categories($con), 
  'is_auth' => $is_auth
]);

print($layout_content);
