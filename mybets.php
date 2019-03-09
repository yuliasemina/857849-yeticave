<?php
session_start();
require 'db.php';
require 'functions.php';

$title_name = 'Мои ставки';

$user_name = '';
$user_id = null;

if (!isset($_SESSION['user'])) {
  //header('add error: 403');
  $layout_content = include_template('error403.php', 
    [
      'user_name' => $user_name, 
      'categories' => get_categories($con)
    ]);

} else  {
	$is_auth = 1;	
	$user = $_SESSION['user'];	
	$user_name = $user['name'];
	$user_id = $user['id'];
  

$categories = get_categories($con);
$lot_list = get_lot_list_by_bets($con, $user_id);

$page_content = include_template('mybets.php', [
           'lots' => $lot_list, 
		   'categories' => get_categories($con)
		]);

$layout_content = include_template('layout_inner.php', [
  'user_name' => $user_name, 
  'categories' => $categories, 
  'main_content'=> $page_content, 
  'title_name' => $title_name
]);

}

print($layout_content);

?>
