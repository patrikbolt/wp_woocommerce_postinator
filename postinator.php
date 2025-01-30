<?php
/**
 * Plugin Name: Postinator
 * Description: Create Post Shipping Labels for Letters and Packets for Swiss Post
 * Version: 1.0
 * Author: Patrik Bolt, Elephantfishing GmbH
 * Text Domain: postinator
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Require the files that contain your classes
require_once plugin_dir_path(__FILE__) . 'inc/class-postinator-settings.php';
require_once plugin_dir_path(__FILE__) . 'inc/class-postinator-orders.php';

/**
 * Initialize the plugin.
 * This function creates instances of the classes that handle settings and orders.
 */
function postinator_init() {
    new Postinator_Settings();
    new Postinator_Orders();
}
add_action('plugins_loaded', 'postinator_init');
