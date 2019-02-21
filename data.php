<?php

//$lot_bets_id = [];

function get_categories($con){
    $categories = [];
    $categories_sql = "SELECT `name` AS `category_name` FROM `categories`";

    $categories_result = mysqli_query($con, $categories_sql);
    if ($categories_result) {
        $categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
    }
    return $categories;
}

function get_lot_list($con){

    $lot_list = [];
    $lot_list_sql = "SELECT
    `l`.`id`,
    `l`.`name` AS 'title',
    `l`.`start_price` AS 'price',
    `l`.`image` AS 'url_img',
    `l`.`date_end` AS 'date_end',
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
    if ($lot_list_result) {
       $lot_list = mysqli_fetch_all($lot_list_result, MYSQLI_ASSOC);
   }

   return $lot_list;
}

function get_lot_by_id ($con, $lot_id)
{
    $lot = null;
    $sql = "
    SELECT 
    `l`.`id`,
    `l`.`name` AS 'title',
    `l`.`start_price` AS 'price',
    `l`.`image` AS 'url_img',
    `l`.`date_end` AS 'date_end',
    `l`.`description` AS description,
    `c`.`name` `category_name`,
    MAX(`b`.`sum_bets`) `max_price`,
    `l`.`bet_step` AS bet_step
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

    $lot = mysqli_fetch_assoc($res);

    return $lot;
}

function get_bets_by_lot ($con, $lot_id)
{
    $sql = 
    "
    SELECT `l`.`name` AS 'lot_name', 
    `b`.`sum_bets` AS 'sum_bets', 
    `b`.`bet_at` AS 'time',
    `u`.`name` AS 'user_name'
    FROM `lots` `l`
    JOIN 
        `bets` `b`
        ON `l`.`id` = `b`.`lot_id`
    JOIN 
        `users` `u`
        ON `b`.`user_id` = `u`.`id`    
    WHERE `l`.`id` = ?
    ORDER BY `b`.`sum_bets` DESC
    ";

    $stmt = db_get_prepare_stmt($con, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $bet_list = mysqli_fetch_all($res, MYSQLI_ASSOC) ?? [];
    
    return $bet_list;
}

function get_categories_id($con, $name){
    $sql = 
    "
    SELECT `id`
    FROM `categories`
    WHERE `categories`.`name` = ?
    GROUP BY
    `categories`.`id`
    ";

    $stmt = db_get_prepare_stmt($con, $sql, [$name]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    $cat = mysqli_fetch_assoc($res);
    return $cat;
}


function add_lot ($con, $date_end, $name, $description, $image, $start_price, $bet_step, $user_id, $category_id)
{
    $sql= "
    INSERT INTO `lots` (`date_end`, `name`, `description`, `image`, `start_price`, `bet_step`, `user_id`, `category_id`)
    VALUES ($date_end, $name, $description, $image, $start_price, $bet_step, $user_id, $category_id)
    ";

    return $sql;
}  