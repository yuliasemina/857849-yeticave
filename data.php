<?php

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
