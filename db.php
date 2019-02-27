<?php

$con = mysqli_connect('localhost', 'root', '', '857849-yeticave');
session_start();


if ($con === false) {
	print('Ошибка подключения: '. mysqli_connect_error());
	exit();
}

mysqli_options($con, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1); 
mysqli_set_charset($con, 'utf8');

