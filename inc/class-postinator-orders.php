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

        // Add column and customization hooks at the right time
        add_filter('manage_edit-shop_order_columns', [$this, 'add_order_column']);
        add_action('manage_shop_order_posts_custom_column', [$this, 'render_order_column'], 10, 2);
    }

    /**
     * Add a custom column to the Orders admin table.
     *
     * @param array $columns Existing columns.
     * @return array Updated columns with "Postinator" added.
     */
    public function add_order_column($columns) {
        error_log('Postinator_Orders: Adding Postinator column');

        $new_columns = [];
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            if ('order_actions' === $key) { // Add custom column before the "Order Actions" column
                $new_columns['postinator'] = __('Postinator', 'postinator');
                error_log('Postinator_Orders: Postinator column successfully added.');
            }
        }

        return $new_columns;
    }

    /**
     * Render custom content in the "Postinator" column.
     *
     * @param string $column The column key.
     * @param int $post_id The post ID.
     */
    public function render_order_column($column, $post_id) {
        if ('postinator' !== $column) {
            return; // Skip other columns
        }

        error_log("Postinator_Orders: Rendering Postinator column for Order ID: $post_id");

        // Get the saved shipping method value
        $saved_method = get_post_meta($post_id, '_postinator_shipping_method', true) ?: '1';

        // Define possible shipping methods
        $methods = [
            '1' => __('Method 1', 'postinator'),
            '2' => __('Method 2', 'postinator'),
            '3' => __('Method 3', 'postinator'),
            '4' => __('Method 4', 'postinator'),
        ];

        // Render the saved method as a <select> dropdown
        echo '<select class="postinator-column-dropdown" disabled>';
        foreach ($methods as $value => $label) {
            $selected = selected($saved_method, $value, false);
            echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
}