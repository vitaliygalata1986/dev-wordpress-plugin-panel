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

    public static function get_slides($all = false){ // $all - если мы хотим получить все - id, контент, название - то используем $all
        global $wpdb;
        if($all){
            return $wpdb->get_results("SELECT * FROM vitos_panel ORDER  BY title ASC", ARRAY_A); // сортируем по алфавитному порядку
        }
        $slides = $wpdb->get_results("SELECT id, title FROM vitos_panel ORDER  BY title ASC", ARRAY_A);
        $data = array();
        foreach ($slides as $slide){
            $data[$slide['id']] = $slide['title'];
        }
        return $data;
    }

    public function saved_slide()
    {
        // self::debug($_POST);
        // var_dump($_POST);
        //  die;


        if(!isset($_POST['vitospanel_add_slide']) || !wp_verify_nonce($_POST['vitospanel_add_slide'], 'vitospanel_action' )){
            // если не существует поля vitospanel_add_slide в массиве $_POST или функция wp_verify_nonce() его не проверила - тоесть возратила false
            wp_die( __( 'Error!', 'vitospanel' ) ); // завершим выполнение скрипта
        }

        $slide_title = isset($_POST['slide_title']) ? trim($_POST['slide_title']) : '';
        $slide_content = isset($_POST['slide_content']) ? trim($_POST['slide_content']) : '';
        $slide_id = isset($_POST['slide_id']) ? (int) $_POST['slide_id'] : 0; // если есть slide_id, то явно приведем его к integer

        // дальше проверим эти переменные. Так как они могут содержать либо значение, либо пустую строку.

        if(empty($slide_title) || empty ($slide_content)){
            set_transient('vitospanel_form_erros', __( 'Form fields are required', 'vitospanel' ), 30 );
            // vitospanel_form_erros - опция для сохранения ошибки
            // 30 - секунды
        }else{
            // сохраним данные в БД
            $slide_title = wp_unslash( $slide_title ); // wp_unslash - удаляет \ из переданной строки
            $slide_content = wp_unslash( $slide_content );
            global $wpdb;

            if($slide_id){
                $query = "UPDATE vitos_panel SET title = %s, content = %s WHERE id =$slide_id";
            }else{
                $query = "INSERT INTO vitos_panel (title, content) VALUES (%s, %s)";
            }

            // проверим - корректно ли выполнился SQL-запрос
            // добавляем в таблицу vitos_panel следующие значения: $slide_title, $slide_content
            if(false !== $wpdb->query($wpdb->prepare($query, $slide_title, $slide_content))){
                // false !== даже если пользователь при редактировании слайда ничего не изменит, то будет 0, и тогда false !== 0, что будет true
                // это сделано для того, чтобы пользователь не увидил ошибку и "не испугался"
                // если он выполнился:
                set_transient( 'vitospanel_form_success', __( 'Slide saved', 'vitospanel' ), 30 );
            }else{
                set_transient( 'vitospanel_form_errors', __( 'Error saving slide', 'vitospanel' ), 30 );
            }
        }
        wp_redirect( $_POST['_wp_http_referer'] ); // _wp_http_referer - адрес страницы куда хотим сделать редирект


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
        wp_enqueue_style( 'vitospanel-jquery-ui', VITOSPANEL_PLUGIN_URL . 'admin/assets/jquery-ui-accordion/jquery-ui.min.css' );
        wp_enqueue_style('vitospanel', VITOSPANEL_PLUGIN_URL . 'admin/css/vitospanel-admin.css');
        wp_register_script( 'vitospanel-jquery-ui', VITOSPANEL_PLUGIN_URL . 'admin/assets/jquery-ui-accordion/jquery-ui.min.js' );
        wp_enqueue_script('vitospanel', VITOSPANEL_PLUGIN_URL . 'admin/js/vitospanel-admin.js', array('jquery','vitospanel-jquery-ui'));
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