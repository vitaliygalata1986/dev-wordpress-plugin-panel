<?php

class Vitospanel_Admin
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
        add_action('admin_menu', array($this, 'admin_menu')); // action to add a new option to the admin menu
        add_action('admin_post_save_slide', array($this, 'saved_slide')); // The admin_post_{action} hook is used to handle POST requests in WordPress and is intended for authorized users (those with access to the admin panel)
        // add_action('admin_post_{action}') - in action we must specify some value that we must pass to the server in a hidden field
        add_action('wp_ajax_vitospanel_action', array($this, 'change_slide')); // we need only hook for authorized users, because we work on the admin panel
    }

    public static function debug($data)
    {
        echo "<pre>" . print_r($data, 1) . "</pre>";
    }

    public function change_slide()
    {
        // self::debug($_POST); // data come POST
        if (!isset($_POST['vitospanel_change_slide']) || !wp_verify_nonce($_POST['vitospanel_change_slide'], 'vitospanel_action')) {
            echo json_encode(array('answer' => 'error', 'text' => __('Security error', 'vitospanel'))); // we will send json, not a string (из переданного массива создаст объект json)
            wp_die(); // finish run script завершим выполнение скрипта
        }
        $slide_id = isset($_POST['slideId']) ? (int)$_POST['slideId'] : 0;
        $article_id = isset($_POST['articleId']) ? (int)$_POST['articleId'] : 0;
        // var_dump($slide_id);
        // var_dump($article_id);

        if (!$article_id) {
            echo json_encode(array('answer' => 'error', 'text' => __('Error article ID', 'vitospanel')));
        }

        if ($slide_id) { // if the user wants to update
            if (update_post_meta($article_id, 'vitos_panel', $slide_id)) { // if updated
                // id_post -> $article_id, meta_key -> vitos_panel, meta_value -> $slide_id
                echo json_encode(array('answer' => 'success', 'text' => __('Saved successfully', 'vitospanel')));
            } else {
                echo json_encode(array('answer' => 'error', 'text' => __('Save error', 'vitospanel')));
            }
        } else { // if user zero come
            delete_post_meta($article_id, 'vitos_panel'); // remove meta vitos_panel for article $article_id
            echo json_encode(array('answer' => 'success', 'text' => __('Saved successfully', 'vitospanel')));
        }

        wp_die(); // terminate program execution
    }

    public static function get_slides($all = false)
    { // $all - if we want to get everything - id, content, name - then we use $all
        global $wpdb;
        if ($all) {
            return $wpdb->get_results("SELECT * FROM vitos_panel ORDER  BY title ASC", ARRAY_A); // sort alphabetically
        }
        $slides = $wpdb->get_results("SELECT id, title FROM vitos_panel ORDER  BY title ASC", ARRAY_A);
        $data = array();
        foreach ($slides as $slide) {
            $data[$slide['id']] = $slide['title'];
        }
        return $data;
    }

    public function saved_slide()
    {
        // self::debug($_POST);
        // var_dump($_POST);
        // die;

        if (!isset($_POST['vitospanel_add_slide']) || !wp_verify_nonce($_POST['vitospanel_add_slide'], 'vitospanel_action')) {
            // if the vitospanel_add_slide field does not exist in the $_POST array or the wp_verify_nonce() function did not check it - i.e. returned false
            wp_die(__('Error!', 'vitospanel')); // terminate script execution
        }

        $slide_title = isset($_POST['slide_title']) ? trim($_POST['slide_title']) : '';
        $slide_content = isset($_POST['slide_content']) ? trim($_POST['slide_content']) : '';
        $slide_id = isset($_POST['slide_id']) ? (int)$_POST['slide_id'] : 0; // if there is a slide_id, then we explicitly convert it to integer

        // next, we will check these variables. Since they can contain either a value or an empty string.

        if (empty($slide_title) || empty ($slide_content)) {
            set_transient('vitospanel_form_erros', __('Form fields are required', 'vitospanel'), 30);
            // vitospanel_form_erros - option to save error
            // 30 - seconds
        } else {
            // save the data to the database
            $slide_title = wp_unslash($slide_title); // wp_unslash - removes \ from the passed string
            $slide_content = wp_unslash($slide_content);
            global $wpdb;

            if ($slide_id) {
                $query = "UPDATE vitos_panel SET title = %s, content = %s WHERE id =$slide_id";
            } else {
                $query = "INSERT INTO vitos_panel (title, content) VALUES (%s, %s)";
            }

            // let's check if the SQL query was executed correctly
            // add the following values ​​to the vitos_panel table: $slide title, $slide content
            if (false !== $wpdb->query($wpdb->prepare($query, $slide_title, $slide_content))) {
                // false !== even if the user doesn't change anything while editing the slide, it will be 0, and then false !== 0, which will be true
                // this is done so that the user doesn't see the error and "get scared"
                // if it was executed:
                set_transient('vitospanel_form_success', __('Slide saved', 'vitospanel'), 30);
            } else {
                set_transient('vitospanel_form_errors', __('Error saving slide', 'vitospanel'), 30);
            }
        }
        wp_redirect($_POST['_wp_http_referer']); // _wp_http_referer - the address of the page where we want to make a redirect
    }

    public function admin_menu()
    {
        // here we will add pages and subpages to the admin panel
        add_menu_page(__('Vitos Panel Main', 'vitos'), __('Vitos Panel', 'vitospanel'), 'manage_options', 'vitospanel-main', array($this, 'render_main_page'), 'dashicons-embed-photo');
        // manage_options - access rights
        // vitospanel-main - slug
        // render_main_page - callback function that will render all this
        // add subpages
        add_submenu_page('vitospanel-main', __('Vitos Panel Main', 'vitos'), __('Set Slide', 'vitospanel'), 'manage_options', 'vitospanel-main'); // parent slug -> vitospanel-main
        add_submenu_page('vitospanel-main', __('Slides management', 'vitos'), __('Slides management', 'vitospanel'), 'manage_options', 'vitospanel-slides', array($this, 'render_slides_page')); // parent slug -> vitospanel-main
    }

    public function render_main_page()
    {
        require_once VITOSPANEL_PLUGIN_DIR . 'admin/templates/main-page-template.php';
    }

    public function render_slides_page()
    {
        require_once VITOSPANEL_PLUGIN_DIR . 'admin/templates/slides-page-template.php';
    }

    public function enqueue_scripts_styles()
    {
        wp_enqueue_style('vitospanel-jquery-ui', VITOSPANEL_PLUGIN_URL . 'admin/assets/jquery-ui-accordion/jquery-ui.min.css');
        wp_enqueue_style('vitospanel', VITOSPANEL_PLUGIN_URL . 'admin/css/vitospanel-admin.css');
        wp_register_script('vitospanel-jquery-ui', VITOSPANEL_PLUGIN_URL . 'admin/assets/jquery-ui-accordion/jquery-ui.min.js');
        wp_enqueue_script('vitospanel-sweetalert', VITOSPANEL_PLUGIN_URL . 'admin/js/sweetalert2.all.min.js');
        wp_enqueue_script('vitospanel', VITOSPANEL_PLUGIN_URL . 'admin/js/vitospanel-admin.js', array('jquery', 'vitospanel-jquery-ui'));
        wp_localize_script('vitospanel', 'vitospanelSlide', array('nonce' => wp_create_nonce('vitospanel_action'))); // vitospanel - id script for which it is localized -
        // this is done so that the object appears before connecting this script
        // vitospanelSlide - object slide
        // wp_create_nonce() - function for generate nonce code
    }

    public static function getPosts($cnt = 10)
    {
        return new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => $cnt, // number of posts
            'orderby' => 'ID', // sort by id in reverse order - the most recent posts will be displayed first
            'order' => 'DESC',
            'paged' => $_GET['paged'] ?? 1, // pagination page number, if $_GET['paged'] is there, we'll take it from there, otherwise 1
        ));
    }
}