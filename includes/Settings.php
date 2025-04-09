<?php

namespace Fau\DegreeProgram\Shares;

class Settings {

    protected string $pluginFile;
    private string $title;
    private string $slug;

    public function __construct($pluginFile) {
        $this->pluginFile = $pluginFile;
    }

    public function onLoaded() {
        add_action('admin_menu', [$this,'adminMenu']);
        add_action('admin_init', [$this,'adminInit']);
        $this->title = plugin()->getName();
        $this->slug = plugin()->getSlug();
    }

    public function adminMenu() {
        add_options_page(
            $this->title . ': ' . __('Settings', 'fau-degree-program-shares'),
            $this->title,
            'manage_options',
            $this->slug,
            [$this, 'settingsPage']
        );
    }

    public function settingsPage() {
        ?>
        <div class="wrap">
            <h1><?php echo $this->title . ': ' . __('Settings', 'fau-degree-program-shares'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('fau-degree-program-shares-options');
                do_settings_sections($this->slug);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function adminInit() {
        register_setting('fau-degree-program-shares-options', 'fau-degree-program-shares');

        add_settings_section(
            'fau-degree-program-shares-api',
            __('API', 'fau-degree-program-shares'),
            '',
            $this->slug
        );

        add_settings_field(
            'dip-edu-api-key',
            __('DIP Edu API Key', 'fau-degree-program-shares'),
            [$this, 'textCallback'],
            $this->slug,
            'fau-degree-program-shares-api'
        );
    }

    public function textCallback() {
        $options = get_option('fau-degree-program-shares', '');
        $value = $options != '' ? $options['dip-edu-api-key'] : '';
        echo '<input type="text" class="regular-text" id="dip-edu-api-key" name="fau-degree-program-shares[dip-edu-api-key]" value="' . esc_attr($value) . '" />';
    }

}