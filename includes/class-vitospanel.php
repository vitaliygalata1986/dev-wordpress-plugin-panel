<?php

class Vitospanel {
    public function __construct(){
        // echo __METHOD__; // укажет - какой клас, какой метод отработал
        $this->load_dependecies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
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