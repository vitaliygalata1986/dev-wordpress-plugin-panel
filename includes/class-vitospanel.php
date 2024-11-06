<?php

class Vitospanel {
    public function __construct(){
        // echo __METHOD__; // укажет - какой клас, какой метод отработал
        $this->load_dependecies();
        $this->init_hooks();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function init_hooks(){
        // хуки, которые будут выполняться при инициализации плагина
        add_action('plugins_loaded', array($this, 'load_textdomain'));
    }
    public function load_textdomain(){
        load_plugin_textdomain('vitospanel', false, VITOSPANEL_PLUGIN_NAME . '/languages/'); // подключает файл перевода для плагина
    }

    private function load_dependecies(){ // подключенте файлов с классами, которые нам потребуются
        require_once VITOSPANEL_PLUGIN_DIR . 'admin/class-vitospanel-admin.php';
        require_once VITOSPANEL_PLUGIN_DIR . 'public/class-vitospanel-public.php';
    }

    private function define_admin_hooks(){ //
        $plugin_admin = new Vitospanel_Admin();
    }

    private function define_public_hooks(){ //
        $plugin_admin = new Vitospanel_Public();
    }
}