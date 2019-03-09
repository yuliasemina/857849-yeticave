<?php

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');
$mailer = new Swift_Mailer($transport);

$message = new Swift_Message("Ваша ставка победила");
$message->setFrom("keks@phpdemo.ru", "YetiCave");

$lot_list = get_lot_winner($con);

foreach ($lot_list as $lot) {
	$lot_id = set_winner($con, intval($lot['id']), intval($lot['user_id']));

	if ($lot_id>0) {
		$layout_content = include_template('email.php', [
			'user_name'=> $lot['user_name'], 
			'lot'=> $lot['id'], 
			'title'=> $lot['title']
		]);

		$message->setTo(['semina.yulia@bk.ru' => 'Юлия']);
		$message->setBody($layout_content);
		// Отправка сообщения
		$mailer = new Swift_Mailer($transport);
		$mailer->send($message);
	}

}

