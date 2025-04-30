<?php

/*
Plugin Name:        FAU Studium Fachanteile
Plugin URI:         https://github.com/RRZE-Webteam/FAU-Studium-Fachanteile
Version:            1.1.2
Description:        Display study subject shares as pie charts
Author:             RRZE-Webteam
Author URI:         https://blogs.fau.de/webworking/
License:            GNU General Public License Version 3
License URI:        https://www.gnu.org/licenses/gpl-3.0.html
Text Domain:        fau-degree-program-shares
Domain Path:        /languages
Requires at least:  6.7
Requires PHP:       8.2
*/

namespace Fau\DegreeProgram\Shares;

defined('ABSPATH') || exit;

/**
 * Load the configuration file
 */
//require_once __DIR__ . '/config/config.php';

/**
 * Composer autoload
 */
require_once 'vendor/autoload.php';

// Register plugin hooks.
register_activation_hook(__FILE__, __NAMESPACE__ . '\activation');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\deactivation');

add_action('plugins_loaded', __NAMESPACE__ . '\loaded');

/**
 * Einbindung der Sprachdateien.
 */
function loadTextDomain() {
    load_plugin_textdomain('fau-degree-program-shares', false, sprintf('%s/languages/', dirname(plugin_basename(__FILE__))));
}

/**
 * Activation callback function.
 */
function activation() {
    loadTextDomain();
}

/**
 * Deactivation callback function.
 * Remove Roles and Caps.
 */
function deactivation() {}

/**
 * Instantiate Plugin class.
 * @return object Plugin
 */
function plugin()
{
    static $instance;
    if (null === $instance) {
        $instance = new Plugin(__FILE__);
    }

    return $instance;
}

/**
 * Check system requirements for the plugin.
 *
 * This method checks if the server environment meets the minimum WordPress and PHP version requirements
 * for the plugin to function properly.
 *
 * @return string An error message string if requirements are not met, or an empty string if requirements are satisfied.
 */
function systemRequirements(): string
{
    // Get the global WordPress version.
    global $wp_version;

    // Get the PHP version.
    $phpVersion = phpversion();

    // Initialize an error message string.
    $error = '';

    // Check if the WordPress version is compatible with the plugin's requirement.
    if (!is_wp_version_compatible(plugin()->getRequiresWP())) {
        $error = sprintf(
        /* translators: 1: Server WordPress version number, 2: Required WordPress version number. */
            __('The server is running WordPress version %1$s. The Plugin requires at least WordPress version %2$s.', 'fau-degree-program-shares'),
            $wp_version,
            plugin()->getRequiresWP()
        );
    } elseif (!is_php_version_compatible(plugin()->getRequiresPHP())) {
        // Check if the PHP version is compatible with the plugin's requirement.
        $error = sprintf(
        /* translators: 1: Server PHP version number, 2: Required PHP version number. */
            __('The server is running PHP version %1$s. The Plugin requires at least PHP version %2$s.', 'fau-degree-program-shares'),
            $phpVersion,
            plugin()->getRequiresPHP()
        );
    }

    // Return the error message string, which will be empty if requirements are satisfied.
    return $error;
}

/**
 * Handle the loading of the plugin.
 *
 * This function is responsible for initializing the plugin, loading text domains for localization,
 * checking system requirements, and displaying error notices if necessary.
 */
function loaded()
{
    loadTextDomain();

    // Trigger the 'loaded' method of the main plugin instance.
    plugin()->loaded();

    // Check system requirements and store any error messages.
    if (systemRequirements()) {
        // If there is an error, add an action to display an admin notice with the error message.
        add_action('admin_init', function () {
            $error = systemRequirements();
            // Check if the current user has the capability to activate plugins.
            if (current_user_can('activate_plugins')) {
                // Get plugin data to retrieve the plugin's name.
                $pluginName = plugin()->getName();

                // Determine the admin notice tag based on network-wide activation.
                $tag = is_plugin_active_for_network(plugin()->getBaseName()) ? 'network_admin_notices' : 'admin_notices';

                // Add an action to display the admin notice.
                add_action($tag, function () use ($pluginName, $error) {
                    printf(
                        '<div class="notice notice-error"><p>' .
                        /* translators: 1: The plugin name, 2: The error string. */
                        esc_html__('Plugins: %1$s: %2$s', 'fau-degree-program-shares') .
                        '</p></div>',
                        $pluginName,
                        $error
                    );
                });
            }
        });

        // Return to prevent further initialization if there is an error.
        return;
    }

    // If there are no errors, create an instance of the 'Main' class and trigger its 'loaded' method.
    (new Main(__FILE__))->onLoaded();

    add_action('init', __NAMESPACE__ . '\createBlock');
}

function createBlock(): void {
    register_block_type( __DIR__ . '/build/block' );
    $script_handle = generate_block_asset_handle( 'fau-degree-program/shares', 'editorScript' );
    wp_set_script_translations( $script_handle, 'fau-degree-program-shares', plugin_dir_path( __FILE__ ) . 'languages' );
}

/**
 * Adds custom block category if not already present.
 *
 * @param array   $categories Existing block categories.
 * @param WP_Post $post       Current post object.
 * @return array Modified block categories.
 */
function fau_block_category($categories, $post) {
    // Check if there is already a FAU category present
    foreach ($categories as $category) {
        if (isset($category['slug']) && $category['slug'] === 'fau') {
            return $categories;
        }
    }

    $custom_category = [
        'slug'  => 'fau',
        'title' => __('FAU', 'fau-degree-program-shares'),
    ];

    // Add FAU to the end of the categories array
    $categories[] = $custom_category;

    return $categories;
}

// Register the Custom FAU Category, if it is not set by another plugin
add_filter('block_categories_all', __NAMESPACE__ . '\fau_block_category', 10, 2);
