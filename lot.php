<?php

require 'db.php';
require 'data.php';
require 'functions.php';
session_start();

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
$errors_bets = [];

$lot_price = $lot['max_price'] ?: $lot['price'];
$min_bet = $lot_price + $lot['bet_step'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sum_bets'])) {
  $errors = [];
  $sum_bets = intval($_POST['sum_bets']);

 if (empty($_POST['sum_bets'])) {
      $errors_bets['sum_bets'] = 'Это поле необходимо заполнить';
  } 
  else if (!is_numeric($_POST['sum_bets'])) {
       $errors_bets['sum_bets'] = 'Только число';
  } 
  else if ($sum_bets === 0) {
  $errors_bets['sum_bets'] = 'Ставка не может быть равна 0';
  }
  else if ($sum_bets >= $min_bet) {
 $errors_bets['sum_bets'] = 'Ставка не может быть ниже минимальной';
  } 

  if (empty($errors_bets)) {
    
    $lot_id = $lot['id'];
    save_bet ($con, $sum_bets, 1, $lot_id); 
    header("Location: /lot.php?id=$lot_id");
   } 
}


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
      'errors' => $errors_bets, 
      'user_name' => $user_name, 
      'categories' => get_categories($con), 
      'is_auth' => $is_auth
    ]);

}
print($layout_content);