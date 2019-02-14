<?php
$categories = [];
$lot_list = [];

    $categories_sql = "SELECT `name` AS `category` FROM `categories`";

    $categories_result = mysqli_query($con, $categories_sql);
        if ($categories_result) {
            $categories = mysqli_fetch_all($categories_result, MYSQLI_ASSOC);
        }

    $lot_list_sql = "SELECT
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
  