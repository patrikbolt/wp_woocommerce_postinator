<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

/**
 * Class Postinator_Widget
 *
 * Adds a "Postinator Widget" section above the orders table in the WooCommerce admin page.
 */
class Postinator_Widget {

    private static $instance = null;

    /**
     * Get the single instance of the class.
     *
     * @return Postinator_Widget
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor: Initialize the hooks.
     */
    private function __construct() {
        // Hook into admin_notices to display the widget
        add_action('admin_head', [$this, 'add_postinator_widget']); // Note: Changed to admin_head for testing
    }

    /**
     * Check if we are on the correct screen (WooCommerce Orders).
     *
     * @return bool
     */
    public function is_correct_screen() {
        $screen = get_current_screen();
        $screen_id = isset($screen->id) ? $screen->id : '';

        // Log the screen ID for debugging
        error_log('Current Screen ID: ' . $screen_id);

        // Valid screen IDs for WooCommerce Orders
        $valid_screen_ids = [
            'edit-shop_order',         // Standard Orders Page
            'woocommerce_page_wc-orders' // Fallback ID
        ];

        return in_array($screen_id, $valid_screen_ids, true);
    }

    /**
     * Add the "Postinator Widget" above the WooCommerce Orders table.
     */
    public function add_postinator_widget() {
        // Check if we are on the correct WooCommerce Order screen
        if (!$this->is_correct_screen()) {
            return; // Exit if not on the right screen
        }

        // Output the Postinator Widget
        echo '<div class="notice notice-info postinator-widget" style="margin-bottom: 20px; padding: 15px;">';
        echo '<h2>Postinator Widget</h2>';
        echo '<p>Welcome to the Postinator Widget! You can add your controls here, like dropdowns, buttons, or other widgets.</p>';
        echo '</div>';
    }
}