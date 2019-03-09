<?php

$transport = new Swift_SmtpTransport('phpdemo.ru', 25);
$transport->setUsername('keks@phpdemo.ru');
$transport->setPassword('htmlacademy');

$message = new Swift_Message("Ваша ставка победила");
$message->setFrom("keks@phpdemo.ru", "YetiCave");
$mailer = new Swift_Mailer($transport);


$lot_list = get_lot_winner($con);

foreach ($lot_list as $lot) {
	$lot_id = set_winner($con, intval($lot['id']), intval($lot['user_id']));

	if ($lot_id > 0) {
		$layout_content = include_template('email.php', [
			'user_name'=> $lot['user_name'], 
			'lot'=> $lot['id'], 
			'title'=> $lot['title']
		]);
		$message->setTo([$lot['user_email'] => $lot['user_name']]);
		
		$message->setBody($layout_content);
		
		$mailer->send($message);
	}

}

