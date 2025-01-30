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

/**
 * Enqueue Postinator styles.
 */
function postinator_enqueue_styles() {
    // Check if we are in the admin area
    if (is_admin()) {
        // Load the CSS file from assets/css/postinator.css
        wp_enqueue_style(
            'postinator-styles', // Handle name
            plugin_dir_url(__FILE__) . 'assets/css/postinator.css', // Path to CSS file
            [], // Dependencies
            '1.0', // Version of the CSS
            'all' // Media type
        );
    }
}
add_action('admin_enqueue_scripts', 'postinator_enqueue_styles');