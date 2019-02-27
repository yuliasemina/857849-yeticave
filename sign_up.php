<?php

require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$tpl_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = validate_reg_form($con, $_POST);

	if (empty($errors)) {
       if ($res && empty($errors)) {
           header("Location: /sign_up.php");
       exit();
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