<?php
require 'mysql_helper.php';
/**
   *  Фукция шаблонизатор
   *  Функция принимает два аргумента: имя файла шаблона и ассоциативный массив с данными для этого шаблона.
   *  Функция возвращает строку — итоговый HTML-код с подставленными данными.
   *
   *  @param string $name - имя файла шаблона.
   *  @param mixed [] $data - ассоциативный массив с данными для этого шаблона.
   *
   *  @return string - возвращает строку - итоговый HTML-код с подставленными данными 
   *
   */
function include_template($name, $data) {
  $name = 'templates/' . $name;
  $result = '';

  if (!is_readable($name)) {
    return $result;
  }

  ob_start();
  extract($data);
  require $name;

  $result = ob_get_clean();

  return $result;
}


/**
   * Фукция для вывода цены в формате с делением на разряды и добавлением знака рубля
   * функция принимает один аргумент — целое число.
   * 
   * @param int $price - исходящая цена лота.
   * @return int - возвращает округленное разделенное на разряды число.
   *
   */

function price_format($price) {
  $price = ceil($price);
  $price = number_format($price, 0, ".", " ");
  $price .= " ₽";

  return $price;
};


/*function price_cur($price) {
  $price = ceil($price);
  $price = number_format($price, 0, ".", " ");

  return $price;
};
*/
/**
   * Функция определяет время, оставшееся до определенного момента
   * @param int @time_now -- по умолчанию - текущее время.
   * @param int @time_end -- задает дату, до которой нужно посчитать интервал времени
   * 
   * @return string - возвращает строку "часы:минуты".
   *
   */

function time_interval ($time_end) {
  $time_now = strtotime('now');
  $time_end = strtotime($time_end);

  $interval = $time_end - $time_now;
  $hours = floor($interval/3600);
  $minutes = ceil(($interval - $hours*3600)/60);
  $time_lots = $hours . ":" . $minutes;


  return $time_lots;
}

/**
   * Функция проверяет правильность заполнения формы с полями для добавления лота
   * @param [] @post -- массив $_POST
   * 
   * @return [] - возвращает массив с ошибками.
   *
   */
function validate_form($post)
{
  $errors = [];
  $required = ['name', 'category_id', 'description', 'date_end', 'start_price', 'bet_step'];
  $numbers= ['start_price', 'bet_step'];

  foreach ($required as $key) {
    if (empty($post[$key])) {
      $errors[$key] = 'Это поле необходимо заполнить';
    }
  }

  foreach ($numbers as $key) {
    if (!is_numeric($post[$key])) {
     $errors[$key] = 'Только число';
   }
 }

$date_current = strtotime("now")+'60';
$date_end = strtotime ($post['date_end']);

if ($date_end < $date_current) {
   $errors['date_end'] = 'Дата должна быть больше текущей минимум на 1 день';
}

 if (!isset($_FILES['image']) || empty($_FILES['image']['tmp_name'])) {
  $errors['image'] = 'Загрузите картинку лота';
} 
else if (!in_array(mime_content_type($_FILES['image']['tmp_name']), ['image/png', 'image/jpeg', 'image.jpg'])) 
{
  $errors['image'] = 'Только JPG или PNG';
}
return $errors;
}



/**
   * Функция проверяет правильность заполнения формы с полями для добавления ставки
   * @param [] @post -- массив $_POST
   * 
   * @return [] - возвращает массив с ошибками.
   *
   */
function validate_bet($post)
{
  $errors = [];
  $required = ['sum_bets'];
  $numbers= ['sum_bets'];

  if (empty($post['sum_bets'])) {
    $errors['sum_bets'] = 'Это поле необходимо заполнить';
  } else if (!is_numeric($post['sum_bets'])) {
   $errors['sum_bets'] = 'Только число';
 }

 return $errors;
}

/**
   * Функция проверяет правильность заполнения формы регистрации нового пользователя
   * @param $con mysqli Ресурс соединения
   * @param [] @post -- массив $_POST
   * 
   * @return [] - возвращает массив с ошибками.
   *
   */

function validate_reg_form ($con, $post)
{
  $errors = [];
  $rec_fields = ['email', 'password', 'name', 'contact'];
  $dict = ['email' => 'Введите e-mail', 'password' => 'Введите пароль', 
  'name' => 'Введите имя',  'contact' => 'Напишите как с вами связаться'];
  
  foreach ($rec_fields as $key) {
    if (empty($post[$key])) {
      $errors[$key] = $dict[$key];
    }
  }

  if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
    $errors ['email'] = 'Введите корректный email';
  }

  if (empty($errors)) {
    $email = mysqli_real_escape_string($con, $post['email']);
    $sql = "SELECT `id` FROM `users` WHERE `email` = '$email'";
    $res = mysqli_query ($con, $sql);

    if (mysqli_num_rows($res) > 0) {
      $errors ['email'] = 'Пользователь с таким email уже зарегистрирован';
    }          
  }
  
  if (!isset($_FILES['image']) || empty($_FILES['image']['tmp_name'])) {
  // $errors['image'] = 'Загрузите аватар';   -- необязательное поле
  } else 
  if (!in_array(mime_content_type($_FILES['image']['tmp_name']), ['image/png', 'image/jpeg', 'image.jpg'])) 
  {
    $errors['image'] = 'Только JPG или PNG';
  }
  

  return $errors;
}


/**
   * Функция проверяет правильность заполнения полей формы входа на сайт
   * @param $con mysqli Ресурс соединения
   * @param [] @post -- массив $_POST
   * 
   * @return [] - возвращает массив с ошибками.
   *
   */

function validate_login ($con, $post)
{
  $errors = [];
  $rec_fields = ['email', 'password'];
  $dict = ['email' => 'Введите e-mail', 'password' => 'Введите пароль'];
  
  foreach ($rec_fields as $key) {
    if (empty($post[$key])) {
      $errors[$key] = $dict[$key];
    }
  }

  if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
    $errors ['email'] = 'Введите корректный email';
  }

  if (empty($errors)) {
    $email = mysqli_real_escape_string($con, $post['email']);
    $sql = "SELECT * FROM `users` WHERE `email` = '$email'";
    $res = mysqli_query ($con, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
    if (!count($errors) and $user) {
      if (password_verify($post['password'], $user['password'])){
        $_SESSION['user'] = $user;
      } else {
        $errors['password'] = 'Неверный пароль';
      }
    }
    else {
      $errors['email'] = 'Такой пользователь не найден';
    }
    
  }
  

  return $errors;
}


/**
   * Функция модифицирует окончание формы множественного числа существительного (часы, минуты и т.д.)
   * @param int $number -- число
   * @param [] $words - массив подстановок существительного в разных формах
   * @return string- возвращает строку с подходящей формой существительного.
   *
   */

function nounEnding($number, $words = ['one', 'two', 'many'])
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $words[2];
        
        case ($mod10 > 5):
            return $words[2];
        
        case ($mod10 === 1):
            return $words[0];
        
        case ($mod10 === 2 || $mod10 === 3 || $mod10 === 4):
            return $words[1];
        
        default:
            return $words[2];
    }
}


/**
   * Функция получает список категорий из бд
   * @param $con mysqli Ресурс соединения
   * 
   * @return string - возвращает массив со списком категорий.
   *
   */
function get_categories($con){
  $categories = [];
  $categories_sql = "
  SELECT 
  `name` AS `category_name`,
  `id` AS `id`
  FROM `categories`";

  $categories_result = mysqli_query($con, $categories_sql);
  if( $categories_result !==false) {
    $categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
  }
  return $categories;
}

/**
   * Функция получает список лотов из бд
   * @param $con mysqli Ресурс соединения
   * 
   * @return string - возвращает массив со списком действующих лотов.
   *
   */
function get_lot_list($con){

  $lot_list = [];
  $lot_list_sql = "SELECT
  `l`.`id`,
  `l`.`name` AS `title`,
  `l`.`start_price` AS `price`,
  `l`.`image` AS `image`,
  `l`.`date_end` AS `date_end`,
  MAX(`b`.`sum_bets`) `max_price`,
  `c`.`name` AS `category`
  FROM
  `lots` `l`
  INNER JOIN
  `categories` `c`
  ON `l`.`category_id` = `c`.`id`
  LEFT JOIN
  `bets` `b`
  ON `b`.`lot_id` = `l`.`id`
  WHERE
  `l`.`date_end` > CURDATE()
  AND `l`.`winner_id` IS NULL
  GROUP BY
  `l`.`id`
  ORDER BY
  `l`.`start_at` DESC";

  $lot_list_result = mysqli_query($con, $lot_list_sql);
  if( $lot_list_result !==false) {
   $lot_list = mysqli_fetch_all($lot_list_result, MYSQLI_ASSOC);
 }

 return $lot_list;
}

/**
   * Функция получает список лотов полученных из поиска
   * @param $con mysqli Ресурс соединения
   * @param $search - искомое слово
   * @param $page_items - количество лотов показанных на экране
   * @param $offset - смещение лотов для показа на следующей странице
   *
   * @return string - возвращает массив со списком лотов.
   *
   */
function get_search_lot_list ($con, $search, $page_items, $offset){

  $lot_list = [];
  $sql = "SELECT
  `l`.`id`,
  `l`.`name` AS `title`,
  `l`.`start_price` AS `price`,
  `l`.`image` AS `image`,
  `l`.`date_end` AS `date_end`,
  MAX(`b`.`sum_bets`) `max_price`,
  `c`.`name` AS `category`
  FROM
  `lots` `l`
  INNER JOIN
  `categories` `c`
  ON `l`.`category_id` = `c`.`id`
  LEFT JOIN
  `bets` `b`
  ON `b`.`lot_id` = `l`.`id`
  WHERE
  `l`.`date_end` > CURDATE()
  AND `l`.`winner_id` IS NULL
  AND MATCH(`l`.`name`, `l`.`description`) AGAINST(? IN BOOLEAN MODE)
  GROUP BY
  `l`.`id`
  ORDER BY
  `l`.`start_at` DESC LIMIT ". $page_items. " OFFSET " . $offset;

  $stmt = db_get_prepare_stmt($con, $sql, [$search]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);

  if( $res!==false) {
    $lot_list = mysqli_fetch_all($res, MYSQLI_ASSOC) ?? []; 
  }

  return $lot_list;
}

/**
   * Функция получает параметры лота по его id
   * @param $con mysqli Ресурс соединения
   * @param $lot_id - id лота
   * 
   * @return string - возвращает массив с параметрами лота.
   *
   */
function get_lot_by_id ($con, $lot_id)
{
  $lot = null;
  $sql = "
  SELECT 
  `l`.`id`,
  `l`.`name` AS `title`,
  `l`.`start_price` AS `price`,
  `l`.`image` AS `image`,
  `l`.`date_end` AS `date_end`,
  `l`.`description` AS `description`,
  `l`.`user_id` AS `user_id`,
  `c`.`name` AS `category_name`,
  MAX(`b`.`sum_bets`) `max_price`,
  `l`.`bet_step` AS `bet_step`
  FROM `lots` `l`
  JOIN `categories` `c`
  ON `l`.`category_id` = `c`.`id`
  LEFT JOIN
  `bets` `b`
  ON `b`.`lot_id` = `l`.`id`
  WHERE `l`.`id` = ?
  GROUP BY `l`.`id`
  ";

  $stmt = db_get_prepare_stmt($con, $sql, [$lot_id]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if( $res !==false) { 
   $lot = mysqli_fetch_assoc($res);
 }
 return $lot;
}


/**
   * Функция получает ставки лота по его id
   * @param $con mysqli Ресурс соединения
   * @param $lot_id - id лота
   * 
   * @return string - возвращает массив списком ставок лота.
   *
   */
function get_bets_by_lot ($con, $lot_id)
{
  $sql = 
  "
  SELECT `l`.`name` AS `lot_name`, 
  `b`.`sum_bets` AS `sum_bets`, 
  `b`.`bet_at` AS `time`,
  `b`.`user_id` AS `bet_user_id`,
  `b`.`lot_id` AS `bet_lot_id`,
  DATE_FORMAT(`b`.`bet_at`, '%d.%m.%y' ' в ' '%H:%i') AS 'time2',
  `u`.`name` AS `user_name`
  FROM `lots` `l`
  JOIN 
  `bets` `b`
  ON `l`.`id` = `b`.`lot_id`
  JOIN 
  `users` `u`
  ON `b`.`user_id` = `u`.`id`    
  WHERE `l`.`id` = ?
  GROUP BY
  `b`.`id`
  ORDER BY `b`.`sum_bets` DESC
  ";

  $stmt = db_get_prepare_stmt($con, $sql, [$lot_id]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if( $res !==false) {
    $bet_list = mysqli_fetch_all($res, MYSQLI_ASSOC) ?? [];
  }

  return $bet_list;
}

/**
   * Функция получает список лотов из категории
   * @param $con mysqli Ресурс соединения
   * @param $cat_id - id категории
   * @param $page_items - количество лотов показанных на экране
   * @param $offset - смещение лотов для показа на следующей странице
   *
   * @return string - возвращает массив со списком лотов.
   *
   */
function get_lot_list_by_cat ($con, $cat_id, $page_items, $offset){

  $sql = "SELECT
  `l`.`id`,
  `l`.`name` AS `title`,
  `l`.`image` AS `image`,
  `l`.`start_price` AS `price`,
  `l`.`date_end` AS `date_end`,
  MAX(`b`.`sum_bets`) `max_price`,
  `c`.`id` AS `id_cat`,
  `c`.`name` AS `category`

  FROM
  `lots` `l`
  INNER JOIN
  `categories` `c`
  ON `l`.`category_id` = `c`.`id`
  LEFT JOIN
  `bets` `b`
  ON `b`.`lot_id` = `l`.`id`
  WHERE
  `l`.`date_end` > CURDATE()
  AND `l`.`winner_id` IS NULL
  AND `c`.`id` = ?
  GROUP BY
  `l`.`id`
  ORDER BY
  `l`.`start_at` DESC LIMIT ". $page_items. " OFFSET " . $offset;

  $stmt = db_get_prepare_stmt($con, $sql, [$cat_id]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if( $res !==false) {
    $lot_list = mysqli_fetch_all($res, MYSQLI_ASSOC) ?? [];
  }
  return $lot_list;
}

/**
   * Функция возвращает количество найденных элементов в результате поиска
   * @param $con mysqli Ресурс соединения
   * @param $search - искомое слово
   * 
   * @return int - возвращает число найденных элементов.
   *
   */
function get_lot_list_search_total($con, $search){
  $total = null;
  $lot_list = [];
  $sql = "SELECT
  `l`.`id`,
  `l`.`name` AS `title`,
  `l`.`start_price` AS `price`,
  `l`.`image` AS `image`,
  `l`.`date_end` AS `date_end`,
  MAX(`b`.`sum_bets`) `max_price`,
  `c`.`name` AS `category`
  FROM
  `lots` `l`
  INNER JOIN
  `categories` `c`
  ON `l`.`category_id` = `c`.`id`
  LEFT JOIN
  `bets` `b`
  ON `b`.`lot_id` = `l`.`id`
  WHERE
  `l`.`date_end` > CURDATE()
  AND `l`.`winner_id` IS NULL
  AND MATCH(`l`.`name`, `l`.`description`) AGAINST(? IN BOOLEAN MODE)
  GROUP BY
  `l`.`id`
  ORDER BY
  `l`.`start_at` DESC";

  $stmt = db_get_prepare_stmt($con, $sql, [$search]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if( $res !==false) {
    $total = mysqli_num_rows($res);
  }
  return $total;
}


/**
   * Функция возвращает количество лотов в категории
   * @param $con mysqli Ресурс соединения
   * @param $cat_id - id категории
   * 
   * @return int - возвращает число лотов в категории.
   */

function get_lot_list_by_cat_total ($con, $cat_id){
  $total = null;
  $sql = "SELECT `l`.`id`
  FROM
  `lots` `l`
  INNER JOIN
  `categories` `c`
  ON `l`.`category_id` = `c`.`id`
  LEFT JOIN
  `bets` `b`
  ON `b`.`lot_id` = `l`.`id`
  WHERE
  `l`.`date_end` > CURDATE()
  AND `l`.`winner_id` IS NULL
  AND `c`.`id` = ?
  GROUP BY
  `l`.`id`
  ORDER BY
  `l`.`start_at` DESC";

  $stmt = db_get_prepare_stmt($con, $sql, [$cat_id]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if( $res !==false) {
    $total = mysqli_num_rows($res);
  }
  return $total;
}

/**
   * Функция возвращает строку с параметрами категории по ее id
   * @param $con mysqli Ресурс соединения
   * @param $cat_id - id категории
   * 
   * @return возвращает массив с параметрами категори
   */

function get_cat_by_id ($con, $cat_id)
{
  $cat = null;
  $sql = "
  SELECT *  FROM `categories`
  WHERE `id` =  ?
  ";

  $stmt = db_get_prepare_stmt($con, $sql, [$cat_id]);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  if( $res !==false) {
    $cat = mysqli_fetch_assoc($res);
  }
  return $cat;
}

/**
   * Функция добавляет новую запись в таблице лотов lots
   * @param $con mysqli Ресурс соединения
   * @param $data = [] - массив данных для добавления нового лота
   * 
   * @return возвращает id добавленного лота
   */

function save_lot($con, $data = []) {
  $sql = "INSERT INTO lots (`date_end`, `name`, `description`, `image`, 
  `start_price`, `bet_step`, `user_id`, `category_id`) 
  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($con, $sql);

  $stmt = db_get_prepare_stmt (
   $con,
   $sql,
   [
    $data['date_end'],
    $data['name'],
    $data['description'],
    $data['image'],
    $data['start_price'],
    $data['bet_step'],
    $data['user_id'],
    $data['category_id']
  ]
);
  
  mysqli_stmt_execute($stmt);

  return mysqli_insert_id($con);   

}


/**
   * Функция добавляет новую запись в таблице ставок bets
   * @param $con mysqli Ресурс соединения
   * @param $sum_bets = сумма ставки
   * @param $user_id = id пользователя, который сделал ставку
   * @param $lot_id = id лота, в котором была сделана ставка
   * 
   * @return возвращает id добавленной ставки
   */

function save_bet($con, $sum_bets, $user_id, $lot_id) {
  $sql = "INSERT INTO bets (`sum_bets`, `user_id`, `lot_id`) 
  VALUES (?, $user_id, $lot_id)";

  $stmt = mysqli_prepare($con, $sql);

  $stmt = db_get_prepare_stmt (
   $con,
   $sql,
   [$sum_bets]
 );
  
  mysqli_stmt_execute($stmt);

  return mysqli_insert_id($con);   
}

/**
   * Функция добавляет новую запись в таблице пользователей users
   * @param $con mysqli Ресурс соединения
   * @param $data = [] - массив данных для добавления нового пользователя
   * 
   * @return возвращает id добавленного пользователя
   */

function save_user($con, $data = []) {
  $sql = "INSERT INTO `users` (`email`, `name`, `password`, `avatar`, `contact`)
  VALUES (?, ?, ?, ?, ?)";

  $stmt = mysqli_prepare($con, $sql);
  $stmt = db_get_prepare_stmt($con, $sql, 
    [
      $data['email'], 
      $data['name'], 
      $data['password'], 
      $data['image'],
      $data['contact']
    ]);

  mysqli_stmt_execute($stmt);
  return mysqli_insert_id($con);   

}


/**
   * Функция возвращает строку с параметрами категории по ее id
   * @param $con mysqli Ресурс соединения
   * @param $user_id - id авторизованного на сайте пользователя
   * 
   * @return возвращает массив ставок с параметрами лота и пользователя
   */
function get_lot_list_by_bets ($con, $user_id){

 $lot_list = [];
 $sql = "SELECT
 `l`.`id`,
 `l`.`name` AS `title`,
 `l`.`start_price` AS `price`,
 `l`.`image` AS `image`,
 `l`.`date_end` AS `date_end`,
 `l`.`winner_id` AS `winner_id`,
 MAX(`b`.`sum_bets`) `max_price`,
 `b`.`bet_at` AS `time`,
 DATE_FORMAT(`b`.`bet_at`, '%d.%m.%y' ' в ' '%H:%i') AS 'time2',
 `c`.`name` AS `category`,
 `u`.`contact` AS `user_contact`,
 `u`.`email` AS `user_email`
 FROM
 `lots` `l`
 INNER JOIN
 `categories` `c`
 ON `l`.`category_id` = `c`.`id`
 LEFT JOIN
 `users` `u`
 ON `l`.`user_id` = `u`.`id`
 LEFT JOIN
 `bets` `b`
 ON `b`.`lot_id` = `l`.`id`
 WHERE
 `b`.`user_id` = ?
 GROUP BY
 `b`.`id`
 ORDER BY
 `l`.`date_end` DESC";


 $stmt = db_get_prepare_stmt($con, $sql, [$user_id]);
 mysqli_stmt_execute($stmt);
 $res = mysqli_stmt_get_result($stmt);

 if( $res!==false) {
  $lot_list = mysqli_fetch_all($res, MYSQLI_ASSOC) ?? []; 
}



return $lot_list;
}


/**
   * Функция возвращает массив пользователей, чьи ставки выйграли
   * @param $con mysqli Ресурс соединения
   * 
   * @return возвращает массив победителей
   */
function get_lot_winner($con){

  $lot_list = [];
  $lot_list_sql = "SELECT 
  `l`.`id`,
  `l`.`name` AS `title`,
  `l`.`start_price` AS `price`,
  `l`.`image` AS `image`,
  `l`.`date_end` AS `date_end`,
  `b`.`sum_bets` AS `maxbet`,
  `u`.`name` AS `user_name`,
  `u`.`id` AS `user_id`,
  `u`.`email` AS `user_email`
  
  FROM `bets` `b`
  JOIN 
  `lots` `l`
  ON `b`.`lot_id` = `l`.`id`
  JOIN 
  `users` `u`
  ON `b`.`user_id` = `u`.`id`
  WHERE
  `l`.`date_end` <= CURDATE()
  AND `l`.`winner_id` IS NULL
  
  AND `b`.`sum_bets` IN (SELECT MAX(`b`.`sum_bets`) FROM `bets` `b` JOIN `lots` `l` ON `b`.`lot_id` = `l`.`id` GROUP BY `l`.`id`)
  ORDER BY `l`.`name` DESC";

  $lot_list_result = mysqli_query($con, $lot_list_sql);
  if( $lot_list_result !==false) {
   $lot_list = mysqli_fetch_all($lot_list_result, MYSQLI_ASSOC);
 }

 return $lot_list;
}

/**
   * Функция обновляет запись в таблице лотов, устанавливает в строке лота id победителя
   * @param $con mysqli Ресурс соединения
   * 
   * @return возвращает колличество обновленных строк
   */
function set_winner($con, $lot_id, $user_id) {

  $sql = "UPDATE `lots` `l`
  SET `l`.`winner_id` = ?
  WHERE `l`.`id` = ?
  ";

  $stmt = mysqli_prepare($con, $sql);
  $stmt = db_get_prepare_stmt(
    $con, 
    $sql, 
    [$user_id, $lot_id]
  );

  mysqli_stmt_execute($stmt);
  return mysqli_affected_rows ($con);   

}