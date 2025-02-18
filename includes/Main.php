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
        $degreeOptions = [];
        $subjectOptions = [];
        $api = new API();
        $degreesRaw = $api->getDegrees();
        foreach ($degreesRaw as $degree) {
            $degreeOptions[] = [
                'value' => $degree['campo_key'],
                'label' => $degree['name'],
            ];
        }
        $subjectsRaw = $api->getSubjects();
        foreach ($subjectsRaw as $subject) {
            $degreeOptions[] = [
                'value' => $subject['campo_key'],
                'label' => $subject['name'],
            ];
        }
        wp_localize_script('wp-blocks', 'sharesBlockData', [
            'degreeOptions' => $degreeOptions,
            'subjectOptions' => $subjectOptions,
        ]);
    }

    public function wpEnqueueScripts()
    {
        wp_register_style(
            'fau-degree-program-shares',
            plugins_url('assets/css/style.css', plugin()->getBasename()),
            [],
            plugin()->getVersion()
        );
        wp_register_script(
            'fau-degree-program-shares-svgarcandslice',
            plugins_url('assets/js/svgarcandpieslice.js', plugin()->getBasename()),
            ['jquery'],
            plugin()->getVersion()
        );
    }
}
