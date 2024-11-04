<?php
// если константа не определена в момент удаления плагина
if(!defined('WP_UNINSTALL_PLUGIN')){
    exit;
}

global $wpdb;
$wpdb->query('DROP TABLE IF EXISTS `vitos_panel`'); // удаляем таблицу vitos_panel

// удаляем мета-данные
delete_metadata('post', '', 'vitos_panel', '', true); // true - удалить для всех постов
