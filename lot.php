<?php
    
require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$user_name = 'Юлия';

$page_content = include_template('lot.php', ['categories' => $categories, 'lot_id' => $lot_id, 'lots' => $lot_info_id, 'bets' => $lot_bets_id]);


$layout_content = include_template('layout.php', ['main_content'=> $page_content, 'user_name' => $user_name, 'categories' => $categories, 
'is_auth' => $is_auth]);

print($layout_content);

?>
