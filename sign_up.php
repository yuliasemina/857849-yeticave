<?php				

require 'db.php';				
require 'data.php';				
require 'functions.php';				
session_start();				
			
$tpl_data = [];				
$file_path ="";				
$errors = [];				

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = validate_reg_form($con, $_POST);			

	if (empty($errors)) {			
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);		

        $upload_dir = __DIR__ . '/uploads';
        if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755);
    }
		
		$file_name = uniqid() . $_FILES['image']['name'];
		if (move_uploaded_file($_FILES['image']['tmp_name'],  $upload_dir . '/' . $file_name)) {
			$file_path = 'uploads/' . $file_name;
		}
		$user_id = save_user(		
			$con,	
			[   	
				'email' => $_POST['email'], 
				'name' =>  $_POST['name'], 
				'password' =>  $password, 
				'image' => $file_path,
				'contact' =>  $_POST['contact']
			]	
		);		

		if ($user_id > 0) {		
			header("Location: /login.php");	
		}		
	}			
}				


$layout_content = include_template('sign_up.php', 				
	[   			
		'errors' => $errors,		
		'categories' => get_categories($con)		
	]);			

print($layout_content);				
