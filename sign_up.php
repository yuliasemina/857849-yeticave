<?php

require 'db.php';
require 'data.php';
require 'functions.php';

$is_auth = rand(0, 1);
$tpl_data = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$errors = [];

	$rec_fields = ['email', 'password', 'name', 'contact'];
	foreach ($rec_fields as $field) {
		if (empty($_POST[$field])) {
			$errors = "Не заполнено поле " . $field;
		}
	};

	if (empty($errors)) {
		$email = mysqli_real_escape_string($con, $_POST['mail']);
		$sql = "SELECT `id` FROM `users` WHERE `email` = '$email'";
		$res = mysqli_query ($con, $sql);

		if (mysqli_num_rows($res) > 0) {
			$errors = 'Пользователь с таким email уже зарегистрирован';
		} else {
			$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
			$sql = "INSERT INTO `users` (`email`, `name`, `password`, `contact`)
			VALUES (?, ?, ?, ?)";

			$stmt = db_get_prepare_stmt($con, $sql, [
				$_POST['email'], 
				$_POST['name'], 
				$password, 
				$_POST['contact']
			]);
			$res = mysqli_stmt_execute($stmt);

		}

		if ($res && empty($errors)) {
			header("Location: /sign_up.php");
			exit();
		}

	}else {
		var_dump($errors);
	}

	$tpl_data['errors'] = $errors;
	$tpl_data['values'] = $_POST;

}

$page_content = include_template('sign_up.php', $tpl_data);

$layout_content = include_template('sign_up.php', 
	[
		'categories' => get_categories($con), 
		'is_auth' => $is_auth,
		'tpl_data' => $tpl_data
	]);


print($layout_content);