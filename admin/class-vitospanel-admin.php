<?php
class Vitospanel_Admin {
    public function __construct()
    {
        // echo __METHOD__; // укажет - какой клас, какой метод отработал
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_styles')); // $this - от данного экземпляра класа
        add_action('admin_menu', array($this, 'admin_menu')); // action для добавления в админское меню новой опции
        add_action('admin_post_save_slide', array($this, 'saved_slide')); // хук admin_post_{action} используется для обработки POST-запросов в WordPress и предназначен для авторизованных пользователей (имеющих доступ к панели администратора
        // add_action('admin_post_{action}') - в action мы должны указать некое значение, которое должны передать на сервер в скрытом поле
    }

    public static function debug($data){
        echo "<pre>" . print_r($data, 1) . "</pre>";
    }

    public function saved_slide(){
        // self::debug($_POST);
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

    public static function getPosts($cnt = 10)
    {
        return new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => $cnt, // кол. постов
            'orderby' => 'ID', // сортируем по id в обратном порядке - самые последние посты будут выводится самыми первыми
            'order' => 'DESC',
            'paged' => $_GET['paged'] ?? 1, // номер страницы пагинации, если $_GET['paged'] есть то возьмем с него, иначе 1
        ));
    }
}