<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

/**
 * Class Postinator_Orders
 *
 * Adds custom features to WooCommerce Orders, such as:
 * - A new column named "Postinator".
 */
class Postinator_Orders {

    // Singleton instance
    private static $instance = null;

    /**
     * Singleton: Get the single instance of this class.
     *
     * @return Postinator_Orders
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor.
     */
    private function __construct() {
        error_log('Postinator_Orders: Singleton Constructor called');

        // Use WooCommerce hooks for Order admin columns
        add_action('current_screen', [$this, 'init_for_orders']);
    }

    /**
     * Initialize logic specifically for WooCommerce Orders admin pages.
     */
    public function init_for_orders() {
        // Check if we're on the WooCommerce Order admin page.
        if (!$this->is_woocommerce_orders_page()) {
            error_log('Postinator_Orders: Not on WooCommerce Orders page. Exiting.');
            return;
        }

        // Add WooCommerce Order table customizations
        add_filter('manage_edit-shop_order_columns', [$this, 'add_order_column']);
        add_action('manage_shop_order_posts_custom_column', [$this, 'render_order_column'], 10, 2);

        error_log('Postinator_Orders: Initialized for WooCommerce Orders.');
    }

    /**
     * Check if the current admin page is a WooCommerce Order page.
     *
     * @return bool
     */
    private function is_woocommerce_orders_page() {
        $screen = get_current_screen();

        // Ensure the current screen is loaded and check if it’s for WooCommerce Orders
        return isset($screen->id) && $screen->id === 'edit-shop_order';
    }

    /**
     * Add a custom column to the Orders admin table.
     */
    public function add_order_column($columns) {
        error_log('Postinator_Orders: Inside add_order_column method');

        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ('date' === $key) { // Add column after the "date" column
                $new_columns['postinator'] = __('Postinator', 'postinator');
                error_log('Postinator_Orders: Postinator column added');
            }
        }

        return $new_columns;
    }

    /**
     * Render custom content in the "Postinator" column.
     */
    public function render_order_column($column, $post_id) {
        if ('postinator' !== $column) {
            error_log("Postinator_Orders: Skipping column '$column'");
            return;
        }

        error_log("Postinator_Orders: Rendering Postinator column for Order ID: $post_id");

        $saved_method = get_post_meta($post_id, '_postinator_shipping_method', true) ?: '1';

        $methods = [
            '1' => __('Method 1', 'postinator'),
            '2' => __('Method 2', 'postinator'),
            '3' => __('Method 3', 'postinator'),
            '4' => __('Method 4', 'postinator'),
        ];

        echo '<select style="width:100%;" disabled>';
        foreach ($methods as $value => $label) {
            $selected = selected($saved_method, $value, false);
            echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
}