<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

/**
 * Class Postinator_Settings
 *
 * Handles the WooCommerce Settings tab: "Postinator".
 * Displays and saves user-defined options (like API credentials, sender information, modes, etc.).
 */
class Postinator_Settings {

    // Singleton instance
    private static $instance = null;

    /**
     * Singleton: Get the single instance of this class.
     *
     * @return Postinator_Settings
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
        // Register WooCommerce settings tab
        add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_tab'], 50);
        add_action('woocommerce_settings_tabs_postinator', [$this, 'settings_tab']);
        add_action('woocommerce_update_options_postinator', [$this, 'update_settings']);
    }

    /**
     * Add a new "Postinator" tab to the WooCommerce Settings navigation.
     */
    public function add_settings_tab($tabs) {
        $tabs['postinator'] = __('Postinator', 'postinator');
        return $tabs;
    }

    /**
     * Display the settings when the "Postinator" tab is selected.
     */
    public function settings_tab() {
        woocommerce_admin_fields($this->get_settings());
    }

    /**
     * Save the settings when the user clicks "Save changes".
     */
    public function update_settings() {
        woocommerce_update_options($this->get_settings());
    }

    /**
     * Return the array of settings to WooCommerce.
     */
    private function get_settings() {

        $settings = [
            // Section: Postinator General Settings
            [
                'title' => __('Postinator Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('General settings for Postinator.', 'postinator'),
                'id'    => 'postinator_settings_section',
            ],
            [
                'title' => __('Postinator Username', 'postinator'),
                'id'    => 'postinator_api_user',
                'type'  => 'text',
                'desc'  => __('Your Postinator Username.', 'postinator'),
            ],
            [
                'title' => __('Postinator Password', 'postinator'),
                'id'    => 'postinator_api_pass',
                'type'  => 'password',
                'desc'  => __('Your Postinator Password.', 'postinator'),
            ],
            [
                'title' => __('Mode', 'postinator'),
                'id'    => 'postinator_mode',
                'type'  => 'select',
                'desc'  => __('Choose test for free specimen labels or normal for paid labels.', 'postinator'),
                'options' => [
                    'test'   => __('Test for free specimen labels', 'postinator'),
                    'normal' => __('Normal for paid labels', 'postinator'),
                ],
                'default' => 'test',
            ],
            [
                'title' => __('Label Language', 'postinator'),
                'id'    => 'postinator_label_language',
                'type'  => 'select',
                'desc'  => __('Choose the language for the shipping label.', 'postinator'),
                'options' => [
                    'de' => __('German (DE)', 'postinator'),
                    'en' => __('English (EN)', 'postinator'),
                    'fr' => __('French (FR)', 'postinator'),
                    'it' => __('Italian (IT)', 'postinator'),
                ],
                'default' => 'en',
            ],
            [
                'type' => 'sectionend',
                'id'   => 'postinator_settings_section',
            ],

            // Section: Swiss Post Settings
            [
                'title' => __('Swiss Post Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('Swiss Post-related settings.', 'postinator'),
                'id'    => 'postinator_swiss_post_settings_section',
            ],
            [
                'title' => __('Post App Client ID', 'postinator'),
                'id'    => 'swiss_post_app_client_id',
                'type'  => 'text',
                'desc'  => __('The App Client ID received from the Swiss Post (Packet API).', 'postinator'),
            ],
            [
                'title' => __('Post Application Secret', 'postinator'),
                'id'    => 'swiss_post_app_secret',
                'type'  => 'password',
                'desc'  => __('The Application Secret received from the Swiss Post (Packet API).', 'postinator'),
            ],
            [
                'title' => __('Post Franking License', 'postinator'),
                'id'    => 'swiss_post_franking_license',
                'type'  => 'text',
                'desc'  => __('The Franking License received from the Swiss Post (Packet API).', 'postinator'),
            ],
            [
                'title' => __('Post Aplus License', 'postinator'),
                'id'    => 'swiss_post_aplus_license',
                'type'  => 'text',
                'desc'  => __('The Aplus License received from the Swiss Post (Packet API).', 'postinator'),
            ],
            [
                'title' => __('Post Application ID', 'postinator'),
                'id'    => 'swiss_post_application_id',
                'type'  => 'text',
                'desc'  => __('The Application ID received from the Swiss Post (Webstamp API).', 'postinator'),
            ],
            [
                'title' => __('Post User ID', 'postinator'),
                'id'    => 'swiss_post_user_id',
                'type'  => 'text',
                'desc'  => __('The User ID received from the Swiss Post (Webstamp API).', 'postinator'),
            ],
            [
                'title' => __('Post Password', 'postinator'),
                'id'    => 'swiss_post_password',
                'type'  => 'password',
                'desc'  => __('The Password received from the Swiss Post (Webstamp API).', 'postinator'),
            ],
            [
                'type' => 'sectionend',
                'id'   => 'postinator_swiss_post_settings_section',
            ],

            // Section: Sender Settings
            [
                'title' => __('Sender Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('Settings for the sender of the shipment.', 'postinator'),
                'id'    => 'postinator_sender_settings_section',
            ],
            [
                'title' => __('Sender Organization', 'postinator'),
                'id'    => 'sender_organization',
                'type'  => 'text',
                'desc'  => __('The company/organization sending the shipment.', 'postinator'),
            ],
            [
                'title' => __('Sender Firstname', 'postinator'),
                'id'    => 'sender_firstname',
                'type'  => 'text',
                'desc'  => __('The sender\'s first name.', 'postinator'),
            ],
            [
                'title' => __('Sender Lastname', 'postinator'),
                'id'    => 'sender_lastname',
                'type'  => 'text',
                'desc'  => __('The sender\'s last name.', 'postinator'),
            ],
            [
                'title' => __('Sender Street', 'postinator'),
                'id'    => 'sender_street',
                'type'  => 'text',
                'desc'  => __('The sender\'s street.', 'postinator'),
            ],
            [
                'title' => __('Sender Housenumber', 'postinator'),
                'id'    => 'sender_housenumber',
                'type'  => 'text',
                'desc'  => __('The sender\'s house number.', 'postinator'),
            ],
            [
                'title' => __('Sender ZIP Code', 'postinator'),
                'id'    => 'sender_zipcode',
                'type'  => 'text',
                'desc'  => __('The sender\'s ZIP code.', 'postinator'),
            ],
            [
                'title' => __('Sender Location', 'postinator'),
                'id'    => 'sender_location',
                'type'  => 'text',
                'desc'  => __('The sender\'s location.', 'postinator'),
            ],
            [
                'title' => __('Sender Country', 'postinator'),
                'id'    => 'sender_country',
                'type'  => 'text',
                'desc'  => __('The sender\'s country.', 'postinator'),
                'default' => 'CH',
            ],
            [
                'title' => __('Sender Postbox', 'postinator'),
                'id'    => 'sender_postbox',
                'type'  => 'text',
                'desc'  => __('The sender\'s postbox.', 'postinator'),
            ],
            [
                'type' => 'sectionend',
                'id'   => 'postinator_sender_settings_section',
            ],

            // Section: Labels Settings
            [
                'title' => __('Labels Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('Settings for labels.', 'postinator'),
                'id'    => 'postinator_labels_settings_section',
            ],
            [
                'title' => __('Default Output Format for Single Labels', 'postinator'),
                'id'    => 'default_output_format',
                'type'  => 'select',
                'desc'  => __('The default output printing format for single labels.', 'postinator'),
                'options' => [
                    'A6' => __('DIN-A6', 'postinator'),
                    'A7' => __('DIN-A7', 'postinator'),
                ],
            ],
            [
                'title' => __('Logo Base64 Packet Labels', 'postinator'),
                'id'    => 'logo_base64_packet',
                'type'  => 'text',
                'desc'  => __('The logo (Base64) printed on packet labels.', 'postinator'),
            ],
            [
                'title' => __('Logo Base64 Webstamp Labels', 'postinator'),
                'id'    => 'logo_base64_webstamp',
                'type'  => 'text',
                'desc'  => __('The logo (Base64) printed on webstamp labels.', 'postinator'),
            ],
            [
                'type' => 'sectionend',
                'id'   => 'postinator_labels_settings_section',
            ],
        ];

        return $settings;
    }
}