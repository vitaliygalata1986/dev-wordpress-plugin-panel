<?php

class Vitospanel_Public
{
    public function __construct()
    {
        // echo __METHOD__; // let's see - which class, which method worked
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles')); // $this - from this instance of the class
        add_filter('the_content', array($this, 'add_slide'));
    }

    public function add_slide($content)
    {
        if (!is_single()) {
            return $content;
        }
        global $post;
        // get meta value
        $slide_id = get_post_meta($post->ID, 'vitos_panel', true);
        if (!$slide_id) {   // is the slide assigned to the current post?
            return $content;
        }
        // if the current post has a slide - we will get the content
        $slide = $this->get_slide($slide_id);
        ob_start(); // we will put the layout output into the buffer
        require_once VITOSPANEL_PLUGIN_DIR . 'public/templates/slide-template.php';
        $slide_html = ob_get_clean(); // the function clears the buffer and returns the output to the variable
        return $content . $slide_html;
    }

    public function get_slide($slide_id)
    {
        global $wpdb;
        // ARRAY_A - associative array
        return $wpdb->get_row("SELECT * FROM vitos_panel WHERE id = $slide_id", ARRAY_A);
    }

    public function enqueue_scripts_styles()
    {
        if (!is_single()) {
            return;
        }
        wp_enqueue_style('vitospanel', VITOSPANEL_PLUGIN_URL . 'public/css/vitospanel-public.css');
        wp_enqueue_script('slidebox', VITOSPANEL_PLUGIN_URL . 'public/js/jquery.slideBox.js', array('jquery'), false, true);
        wp_enqueue_script('vitospanel', VITOSPANEL_PLUGIN_URL . 'public/js/vitospanel-public.js', array('jquery'), false, true);
    }
}