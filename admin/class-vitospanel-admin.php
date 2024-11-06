<?php
class Vitospanel_Admin {
    public function __construct()
    {
        // echo __METHOD__; // укажет - какой клас, какой метод отработал
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_styles')); // $this - от данного экземпляра класа
        add_action('admin_menu', array($this, 'admin_menu')); // action для добавления в админское меню новой опции
    }

    public function admin_menu(){
        // здесь будем добавлять в админку страницы и подстраницы
        add_menu_page(__('Vitos Panel Main', 'vitos'), __('Vitos Panel', 'vitospanel'), 'manage_options', 'vitospanel-main', array($this, 'render_main_page'), 'dashicons-embed-photo');
        // manage_options - права доступа
        // vitospanel-main - slug
        // render_main_page - callback функция, которая будет все это отрисовывать

        // добавим подстраницы
        add_submenu_page('vitospanel-main', __('Vitos Panel Main', 'vitos'), __('Set Slide', 'vitospanel'), 'manage_options', 'vitospanel-main'); // parent slug -> vitospanel-main
        add_submenu_page('vitospanel-main', __('Slides management', 'vitos'), __('Slides management', 'vitospanel'), 'manage_options', 'vitospanel-slides', array($this, 'render_slides_page')); // parent slug -> vitospanel-main
    }

    public function render_main_page(){
        require_once VITOSPANEL_PLUGIN_DIR . 'admin/templates/main-page-template.php';
    }

    public function render_slides_page(){
        require_once VITOSPANEL_PLUGIN_DIR . 'admin/templates/slides-page-template.php';
    }

    public function enqueue_scripts_styles()
    {
        wp_enqueue_style('vitospanel', VITOSPANEL_PLUGIN_URL . 'admin/css/vitospanel-admin.css');
        wp_enqueue_script('vitospanel', VITOSPANEL_PLUGIN_URL . 'admin/js/vitospanel-admin.js', array('jquery'));
    }
}