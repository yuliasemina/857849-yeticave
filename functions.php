<?php

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


?>