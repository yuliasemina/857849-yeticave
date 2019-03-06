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
