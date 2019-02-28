<?php				

require 'db.php';				
require 'data.php';				
require 'functions.php';				
session_start();				


$is_auth = 0;				
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
		'categories' => get_categories($con), 		
		'is_auth' => $is_auth		
	]);			

print($layout_content);				
