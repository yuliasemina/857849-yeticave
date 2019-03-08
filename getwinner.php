
<?php
require_once 'vendor/autoload.php';
require 'db.php';
require 'data.php';
require 'functions.php';


$lot_list = get_lot_winner($con);


foreach ($lot_list as $lot) {
	$lot_id = set_winner($con, intval($lot['id']), intval($lot['user_id']));

	if ($lot_id>0) {
		$layout_content = include_template('email.php', [
			'user_name'=> $lot['user_name'], 
			'lot'=> $lot['id'], 
			'lot_name'=> $lot['user_name'], 
			'user_name'=> $lot['user_name'], 
]);

			$transport = new Swift_SmtpTransport('keks@phpdemo.ru', 'htmlacademy', '25');
// Формирование сообщения
			$message = new Swift_Message("Ваша ставка победила");
			$message->setTo(["semina.yulia@bk.ru" => "Юлия"]);
			$message->setBody($layout_content);
			$message->setFrom("keks@phpdemo.ru", "YetiCave");
// Отправка сообщения
			$mailer = new Swift_Mailer($transport);
			$mailer->send($message);
		

	}

}

