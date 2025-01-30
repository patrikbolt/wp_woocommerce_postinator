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

// Require your class files
require_once plugin_dir_path(__FILE__) . 'inc/class-postinator-settings.php';
require_once plugin_dir_path(__FILE__) . 'inc/class-postinator-orders.php';
require_once plugin_dir_path(__FILE__) . 'inc/class-postinator-widget.php'; // Postinator Widget einbinden

/**
 * Initialize the plugin.
 */
function postinator_init() {
    Postinator_Settings::get_instance();

    // Initialisiere Postinator-Objekte nur im Admin-Bereich
    if (is_admin()) {
        Postinator_Orders::get_instance();
        Postinator_Widget::get_instance(); // Initialisiere das Widget
    }
}
add_action('plugins_loaded', 'postinator_init');