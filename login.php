<?php				
session_start();	
require 'db.php';				
require 'data.php';				
require 'functions.php';				
			
$tpl_data = [];				
$errors = [];				

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = validate_login($con, $_POST);			

	if (empty($errors)) {			
		
			header("Location: /index.php");	
	}			
}				


$layout_content = include_template('login.php', 				
	[   			
		'errors' => $errors,		
		'categories' => get_categories($con)
	]);			

print($layout_content);				
