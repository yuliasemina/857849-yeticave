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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors_bets = validate_bet($_POST);

  if (empty($errors_bets)) {
    $sum_bets = $_POST['sum_bets'];
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
      'errors' => $errors, 
      'user_name' => $user_name, 
      'categories' => get_categories($con), 
      'is_auth' => $is_auth
    ]);

}
print($layout_content);