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


function price_cur($price) {
  $price = ceil($price);
  $price = number_format($price, 0, ".", " ");

  return $price;
};

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
  if (!isset($_FILES['image'])) {
    $errors['image'] = 'Загрузите картинку лота';
  } else if (!in_array(mime_content_type($_FILES['image']['tmp_name']), 
  ['image/png', 'image/jpeg', 'image.jpg'])) 
      {
          $errors['image'] = 'Только JPG или PNG';
      }
  return $errors;
}

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