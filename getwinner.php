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
print('1');
	if ($lot_id > 0) {
		$layout_content = include_template('email.php', [
			'user_name'=> $lot['user_name'], 
			'lot'=> $lot['id'], 
			'title'=> $lot['title']
		]);
print('2');
		$message->setTo(['semina.yulia@bk.ru' => 'Юлия']);
		print('3');
		$message->setBody($layout_content);
		// Отправка сообщения
print('4');
		$mailer->send($message);
print('--------------5');
	}

}

