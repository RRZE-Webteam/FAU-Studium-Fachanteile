<?php

namespace Fau\DegreeProgram\Shares;

defined('ABSPATH') || exit;


/**
 * [Main description]
 */
class Main
{
    protected $pluginFile;

    /**
     * [__construct description]
     */
    public function __construct($pluginFile)
    {
        $this->pluginFile = $pluginFile;
    }

    public function onLoaded()
    {
        // Settings
        $settings = new Settings($this->pluginFile);
        $settings->onLoaded();

        // Enqueue scripts
        add_action('admin_enqueue_scripts', [$this, 'adminEnqueueScripts']);
        add_action('wp_enqueue_scripts', [$this, 'wpEnqueueScripts']);

        new Shortcode();
        new Metabox();
    }

    public function adminEnqueueScripts()
    {
        wp_enqueue_style(
            'fau-degree-program-shares-admin',
            plugins_url('assets/css/admin.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_enqueue_script(
                'fau-degree-program-shares-admin',
                plugins_url('assets/js/admin.js', plugin()->getBasename()),
                ['jquery'],
                plugin()->getVersion()
            );

    }

    public function wpEnqueueScripts()
    {
        /*wp_register_style(
            'fau-degree-program-shares-charts',
            plugins_url('assets/css/charts.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );*/
        wp_register_style(
            'fau-degree-program-shares',
            plugins_url('assets/css/style.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_register_style(
            'fau-degree-program-shares-apexcharts',
            plugins_url('node_modules/apexcharts/dist/apexcharts.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_register_script(
            'fau-degree-program-shares-apexcharts',
            plugins_url('node_modules/apexcharts/dist/apexcharts.js', plugin()->getBasename()),
            ['jquery'],
            plugin()->getVersion()
        );
        wp_register_script(
            'fau-degree-program-shares',
            plugins_url('assets/js/script.js', plugin()->getBasename()),
            ['jquery'],
            plugin()->getVersion()
        );
        wp_localize_script('fau-degree-program-shares', 'fdps_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('fdps-ajax-nonce'),
        ]);
    }
}
