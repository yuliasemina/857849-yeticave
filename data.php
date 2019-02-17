<?php

$lot_list = [];
$lot_info_id = [];
$lot_bets_id = [];

function get_categories($con){
    $categories = [];
    $categories_sql = "SELECT `name` AS `category_name` FROM `categories`";

    $categories_result = mysqli_query($con, $categories_sql);
        if ($categories_result) {
            $categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
        }
            return $categories;
}

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



function get_lot_by_id ($con, $lot_id){ 
$lot_info_id_sql = "SELECT 
        `l`.`id`,
        `l`.`name` AS 'title',
        `l`.`start_price` AS 'price',
        `l`.`image` AS 'url_img',
        `l`.`date_end` AS 'date_end',
        `l`.`description` AS description,
        `c`.`name` `category_name`,
         MAX(`b`.`sum_bets`) `max_price`
FROM `lots` `l`
JOIN `categories` `c`
ON `l`.`category_id` = `c`.`id`
LEFT JOIN
        `bets` `b`
        ON `b`.`lot_id` = `l`.`id`
WHERE `l`.`id` = ?";

$lot_info_id_result = mysqli_query($con, $lot_info_id_sql);
        if ($lot_info_id_result) {
           $lot_info_id = mysqli_fetch_assoc ($lot_info_id_result);
        }
    return $lot_info_id;
}



$lot_bets_id_sql = "SELECT `l`.`name`, `b`.`sum_bets` AS 'sum_bets', `b`.`bet_at` FROM `lots` `l`
JOIN `bets` `b`
ON `l`.`id` = `b`.`lot_id`
WHERE `l`.`id` = $lot_id
GROUP BY `l`.`id`
ORDER BY `b`.`bet_at` DESC";

$lot_bets_id_result = mysqli_query($con, $lot_bets_id_sql);
        if ($lot_bets_id_result) {
           $lot_bets_id = mysqli_fetch_all($lot_bets_id_result, MYSQLI_ASSOC);
        } 