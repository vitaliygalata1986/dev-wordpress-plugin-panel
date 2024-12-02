<?php

class Vitospanel
{
    public function __construct()
    {
        $this->load_dependecies();
        $this->init_hooks();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function init_hooks()
    {
        // hooks that will be executed when the plugin is initialized
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }

    public function load_textdomain()
    {
        load_plugin_textdomain('vitospanel', false, VITOSPANEL_PLUGIN_NAME . '/languages/');
    }

    private function load_dependecies()
    { // connecting files with classes that we will need
        require_once VITOSPANEL_PLUGIN_DIR . 'admin/class-vitospanel-admin.php';
        require_once VITOSPANEL_PLUGIN_DIR . 'public/class-vitospanel-public.php';
    }

    private function define_admin_hooks()
    {
        $plugin_admin = new Vitospanel_Admin();
    }

    private function define_public_hooks()
    {
        $plugin_admin = new Vitospanel_Public();
    }
}