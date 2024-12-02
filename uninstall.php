<?php
// if the constant is not defined at the time of plugin removal
if(!defined('WP_UNINSTALL_PLUGIN')){
    exit;
}

global $wpdb;
$wpdb->query('DROP TABLE IF EXISTS `vitos_panel`'); // delete table vitos_panel

// remove meta data
delete_metadata('post', '', 'vitos_panel', '', true); // true - delete for all posts
