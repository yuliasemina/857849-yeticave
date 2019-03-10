<?php				
session_start();	
require 'db.php';				
require 'functions.php';				
			
$tpl_data = [];				
$errors = [];				
$title_name = 'Вход';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = validate_login($con, $_POST);			

	if (empty($errors)) {			
		
			header("Location: /index.php");	
	}			
}				

$page_content = include_template('login.php', [
      'errors' => $errors,		
	  'categories' => get_categories($con)
    ]);

$layout_content = include_template('layout_inner.php', [
  'categories' => get_categories($con), 
  'main_content'=> $page_content, 
  'title_name' => $title_name
]);

print($layout_content);				
