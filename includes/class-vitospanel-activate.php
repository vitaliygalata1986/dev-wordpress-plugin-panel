<?php

// следуем правилам именования класов Wordpres
class Vitospanel_Activate
{

    public static function activate()
    {
        // создадим таблицу в БД, где будем хранить слайды
        global $wpdb; // заберем объект $wpdb из глобального пространства через который мы сможем длеать запрос
        $wpdb->query("CREATE TABLE IF NOT EXISTS `vitos_panel` (
          `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
          `title` varchar(255) NOT NULL,
          `content` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;");
    }
}
