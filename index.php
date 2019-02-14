<?php
    
require 'db.php' ;
require 'data.php' ;
require 'functions.php' ;

$is_auth = rand(0, 1);

$user_name = 'Юлия';
$title_name = 'Главная';

$page_content = include_template('index.php', ['categories' => $categories, 'lots' => $lot_list]);


$layout_content = include_template('layout.php', ['main_content'=> $page_content, 'user_name' => $user_name, 'categories' => $categories, 
    'title_name' => $title_name, 'is_auth' => $is_auth]);

print($layout_content);

?>
