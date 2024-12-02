<?php
/*
Plugin Name: Vitos Post Panel
Plugin URI: https://github.com/vitaliygalata1986
Description: The plugin allows you to add a slide with the necessary content for the selected posts
Version: 1.0
Author: Vitaliy Galata
Author URI: https://github.com/vitaliygalata1986
Text Domain: vitospanel
Domain Path: /languages
*/

defined('ABSPATH') or die;

define('VITOSPANEL_PLUGIN_DIR', plugin_dir_path(__FILE__));
// echo VITOSPANEL_PLUGIN_DIR; // C:\OpenServer\domains\hoock\wordpress\wp-content\plugins\vitospanel/

define('VITOSPANEL_PLUGIN_URL', plugin_dir_url(__FILE__));
// echo VITOSPANEL_PLUGIN_URL; // http://sport-island.loc/wp-content/plugins/vitospanel/

define('VITOSPANEL_PLUGIN_NAME', dirname(plugin_basename(__FILE__)));
// echo VITOSPANEL_PLUGIN_NAME; // vitospanel

function vitos_activate()
{
    require_once VITOSPANEL_PLUGIN_DIR . '/includes/class-vitospanel-activate.php';
    Vitospanel_Activate::activate(); // static method call
}

register_activation_hook(__FILE__, 'vitos_activate'); // activation hook

require_once VITOSPANEL_PLUGIN_DIR . '/includes/class-vitospanel.php';

function run_vitospanel()
{
    $plugin = new Vitospanel();
}

run_vitospanel();