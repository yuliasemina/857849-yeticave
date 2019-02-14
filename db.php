<?php
$link = mysqli_init();
mysqli_options($link, MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1); 

$con = mysqli_connect('localhost', 'root', '1', '857849-yeticave');

if ($con === false) {
	print('Ошибка подключения: '. mysqli_connect_error());
	exit();
}

mysqli_set_charset($con, 'utf8');

