<?php

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

/**
 * Class Postinator_Orders
 *
 * Adds custom features to WooCommerce Orders, such as:
 * - A new column named "Postinator"
 * - (Optional) Additional logic for saving or bulk actions
 */
class Postinator_Orders
{

    public function __construct()
    {
        // Add the new column "Postinator"
        add_filter('manage_edit-shop_order_columns', [$this, 'add_order_column']);
        // Render content in that column
        add_action('manage_shop_order_posts_custom_column', [$this, 'render_order_column'], 10, 2);

        // In the future, you could add more hooks for saving data or bulk actions.
    }

    /**
     * Add the "Postinator" column to the Orders list table.
     */
    public function add_order_column($columns)
    {
        // Insert the column near the end, or specifically at a certain point.
        $columns['postinator'] = __('Postinator', 'postinator');
        return $columns;
    }

    /**
     * Render a dropdown in the "Postinator" column with shipping methods 1..8.
     * Note: This only displays the dropdown. It doesn't save changes without extra code (e.g. AJAX).
     */
    public function render_order_column($column, $post_id)
    {
        if ('postinator' === $column) {

            // For demonstration: let's retrieve a saved method from order meta:
            $saved_method = get_post_meta($post_id, '_postinator_shipping_method', true);

            // Define shipping methods (1..8)
            $methods = [
                '1' => __('Method 1', 'postinator'),
                '2' => __('Method 2', 'postinator'),
                '3' => __('Method 3', 'postinator'),
                '4' => __('Method 4', 'postinator'),
                '5' => __('Method 5', 'postinator'),
                '6' => __('Method 6', 'postinator'),
                '7' => __('Method 7', 'postinator'),
                '8' => __('Method 8', 'postinator'),
            ];

            // Display a <select> in this column
            echo '<select style="width:100%;" disabled>'; // "disabled" so it won't be changed inline
            foreach ($methods as $value => $label) {
                // Mark the current saved option as "selected"
                $selected = selected($saved_method, $value, false);
                echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
            }
            echo '</select>';
        }
    }
}
