<?php

// follow the rules of naming Wordpress classes
class Vitospanel_Activate
{
    public static function activate()
    {
        // let's create a table in the database where we will store slides
        global $wpdb; // $wpdb object from global space through which we can make a query
        $wpdb->query("CREATE TABLE IF NOT EXISTS `vitos_panel` (
          `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
          `title` varchar(255) NOT NULL,
          `content` text NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;");
    }
}
