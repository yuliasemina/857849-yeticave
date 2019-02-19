<?php
    
require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Юлия';

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

$lot = get_lot_by_id($con, intval($_GET['id']));
$bets = get_bets_by_lot($con, intval($_GET['id']));



if (is_null($lot['id'])){
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
  'bets' => $bets,
  'user_name' => $user_name, 
  'categories' => get_categories($con), 
  'is_auth' => $is_auth
]);
 
 }
print($layout_content);