<?php

require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$tpl_data = [];
$file_path ="";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = validate_reg_form($con, $_POST);

	if (empty($errors)) {
		$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
		
		if (isset($_FILES['image'])) {
			if (!in_array(mime_content_type($_FILES['image']['tmp_name']),
				['image/png', 'image/jpeg', 'image.jpg'])) {
				$errors['image'] = 'Только JPG или PNG';
		} else
			$file_name = uniqid() . $_FILES['image']['name'];
			if (move_uploaded_file($_FILES['image']['tmp_name'],  $upload_dir . '/' . $file_name)) {
				$file_path = 'uploads/' . $file_name;
			} 
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

	if ($res && empty($errors)) {
		if ($user_id > 0) {
			header("Location: /sign_up.php");
			exit();
		}
	}
}
}

$layout_content = include_template('sign_up.php', 
	[   
		'errors' => $errors,
		'categories' => get_categories($con), 
		'is_auth' => $is_auth
	]);

print($layout_content);