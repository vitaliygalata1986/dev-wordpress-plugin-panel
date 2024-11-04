<?php

class Vitospanel_Public
{
    public function __construct()
    {
        // echo __METHOD__; // укажет - какой клас, какой метод отработал
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles')); // $this - от данного экземпляра класа
    }
    public function enqueue_scripts_styles()
    {
        wp_enqueue_style('vitospanel', VITOSPANEL_PLUGIN_URL . 'public/css/vitospanel-public.css');
        wp_enqueue_script('vitospanel', VITOSPANEL_PLUGIN_URL . 'public/js/vitospanel-public.js', array('jquery'), false, true);
    }
}