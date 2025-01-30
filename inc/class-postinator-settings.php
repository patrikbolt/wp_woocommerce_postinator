<?php
if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

/**
 * Class Postinator_Settings
 *
 * Handles the WooCommerce Settings tab: "Postinator".
 * Displays and saves user-defined options (like API credentials, language, mode, etc.).
 */
class Postinator_Settings {

    public function __construct() {
        // Register a new WooCommerce settings tab
        add_filter('woocommerce_settings_tabs_array', [$this, 'add_settings_tab'], 50);
        add_action('woocommerce_settings_tabs_postinator', [$this, 'settings_tab']);
        add_action('woocommerce_update_options_postinator', [$this, 'update_settings']);
    }

    /**
     * 1) Add a new "Postinator" tab to the WooCommerce Settings navigation.
     */
    public function add_settings_tab($tabs) {
        $tabs['postinator'] = __('Postinator', 'postinator');
        return $tabs;
    }

    /**
     * 2) Display the fields when the "Postinator" tab is selected.
     */
    public function settings_tab() {
        woocommerce_admin_fields($this->get_settings());
    }

    /**
     * 3) Save the settings when the user clicks "Save changes".
     */
    public function update_settings() {
        woocommerce_update_options($this->get_settings());
    }

    /**
     * 4) Return the array of settings to WooCommerce.
     *    These settings will appear under the "Postinator" tab.
     */
    public function get_settings() {

        $settings = array(

            // Section: Postinator Settings
            array(
                'title' => __('Postinator Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('General Settings.', 'postinator'),
                'id'    => 'postinator_settings_section'
            ),
            array(
                'title' => __('Postinator Username', 'postinator'),
                'id'    => 'postinator_api_user',
                'type'  => 'text',
                'desc'  => __('Your Postinator Username.', 'postinator'),
            ),
            array(
                'title' => __('Postinator Password', 'postinator'),
                'id'    => 'postinator_api_pass',
                'type'  => 'password',
                'desc'  => __('Your Postinator Password.', 'postinator'),
            ),
            array(
                'title' => __('Mode', 'postinator'),
                'id'    => 'postinator_mode',
                'type'  => 'select',
                'desc'  => __('test for free specimen labels or normal for regular paid labels.', 'postinator'),
                'options' => array(
                    'test'   => __('test for free specimen labels', 'postinator'),
                    'normal' => __('normal for paid labels', 'postinator'),
                ),
                'default' => 'test',
            ),
            array(
                'title'    => __('Label Language', 'postinator'),
                'id'       => 'postinator_language',
                'type'     => 'select',
                'desc'     => __('Choose the language for the shipping label.', 'postinator'),
                'options'  => array(
                    'de' => __('German (DE)', 'postinator'),
                    'en' => __('English (EN)', 'postinator'),
                    'fr' => __('French (FR)', 'postinator'),
                    'it' => __('Italian (IT)', 'postinator'),
                ),
                'default'  => 'en',
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'postinator_settings_section'
            ),

            // Section: Swiss Post Settings
            array(
                'title' => __('Swiss Post Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('Swiss Post related Settings.', 'postinator'),
                'id'    => 'postinator_swiss_post_settings_section'
            ),
            array(
                'title' => __('Post App Client ID', 'postinator'),
                'id'    => 'swiss_post_app_client_id',
                'type'  => 'text',
                'desc'  => __('The App Client ID received from the Swiss Post. (Packet API)', 'postinator'),
            ),
            array(
                'title' => __('Post Application Secret', 'postinator'),
                'id'    => 'swiss_post_app_secret',
                'type'  => 'password',
                'desc'  => __('The Application Secret received from the Swiss Post. (Packet API)', 'postinator'),
            ),
            array(
                'title' => __('Post Franking License', 'postinator'),
                'id'    => 'swiss_post_franking_license',
                'type'  => 'text',
                'desc'  => __('The Franking License received from the Swiss Post. (Packet API)', 'postinator'),
            ),
            array(
                'title' => __('Post Aplus License', 'postinator'),
                'id'    => 'swiss_post_aplus_license',
                'type'  => 'text',
                'desc'  => __('The Aplus License received from the Swiss Post. (Packet API)', 'postinator'),
            ),
            array(
                'title' => __('Post Application ID', 'postinator'),
                'id'    => 'swiss_post_application_id',
                'type'  => 'text',
                'desc'  => __('The Application ID received from the Swiss Post. (Webstamp API)', 'postinator'),
            ),
            array(
                'title' => __('Post User ID', 'postinator'),
                'id'    => 'swiss_post_user_id',
                'type'  => 'text',
                'desc'  => __('The User ID received from the Swiss Post. (Webstamp API)', 'postinator'),
            ),
            array(
                'title' => __('Post Password', 'postinator'),
                'id'    => 'swiss_post_password',
                'type'  => 'password',
                'desc'  => __('The Password received from the Swiss Post. (Webstamp API)', 'postinator'),
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'postinator_swiss_post_settings_section'
            ),

            // Section: Sender Settings
            array(
                'title' => __('Sender Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('Settings required for the definition of the Sender.', 'postinator'),
                'id'    => 'postinator_sender_settings_section'
            ),
            array(
                'title' => __('Sender Organization', 'postinator'),
                'id'    => 'sender_organization',
                'type'  => 'text',
                'desc'  => __('The Company/Organization sending the shipments', 'postinator'),
            ),
            array(
                'title' => __('Sender Firstname', 'postinator'),
                'id'    => 'sender_firstname',
                'type'  => 'text',
                'desc'  => __('The sender firstname', 'postinator'),
            ),
            array(
                'title' => __('Sender Lastname', 'postinator'),
                'id'    => 'sender_lastname',
                'type'  => 'text',
                'desc'  => __('The sender lastname', 'postinator'),
            ),
            array(
                'title' => __('Sender Street', 'postinator'),
                'id'    => 'sender_street',
                'type'  => 'text',
                'desc'  => __('The sender street', 'postinator'),
            ),
            array(
                'title' => __('Sender housenumber', 'postinator'),
                'id'    => 'sender_housenumber',
                'type'  => 'text',
                'desc'  => __('The sender housenumber', 'postinator'),
            ),
            array(
                'title' => __('Sender ZIP Code', 'postinator'),
                'id'    => 'sender_zipcode',
                'type'  => 'text',
                'desc'  => __('The sender zip code', 'postinator'),
            ),
            array(
                'title' => __('Sender Location', 'postinator'),
                'id'    => 'sender_location',
                'type'  => 'text',
                'desc'  => __('The sender location', 'postinator'),
            ),
            array(
                'title' => __('Sender Country', 'postinator'),
                'id'    => 'sender_country',
                'type'  => 'text',
                'desc'  => __('The sender country', 'postinator'),
                'default' => 'CH',
            ),
            array(
                'title' => __('Sender Postbox', 'postinator'),
                'id'    => 'sender_postbox',
                'type'  => 'text',
                'desc'  => __('The sender postbox', 'postinator'),
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'postinator_sender_settings_section'
            ),

            // Section: Labels Settings
            array(
                'title' => __('Labels Settings', 'postinator'),
                'type'  => 'title',
                'desc'  => __('Settings required for the Labels.', 'postinator'),
                'id'    => 'postinator_labels_settings_section'
            ),
            array(
                'title' => __('Default Output Format for Single Labels', 'postinator'),
                'id'    => 'default_output_formaat', // check spelling if needed
                'type'  => 'select',
                'desc'  => __('The Default Output Printing Format for the Single Labels, ex. A7', 'postinator'),
                'options' => array(
                    'A6' => __('DIN-A6', 'postinator'),
                    'A7' => __('DIN-A7', 'postinator'),
                ),
            ),
            array(
                'title' => __('Logo Base64 Packet Labels', 'postinator'),
                'id'    => 'logo_base64_packet',
                'type'  => 'text',
                'desc'  => __('The Logo in Base64 which has to be printed on the Labels', 'postinator'),
            ),
            array(
                'title' => __('Logo Base64 Webstamp Labels', 'postinator'),
                'id'    => 'logo_base64_webstamp',
                'type'  => 'text',
                'desc'  => __('The Logo in Base64 which has to be printed on the Labels', 'postinator'),
            ),
            array(
                'type' => 'sectionend',
                'id'   => 'postinator_labels_settings_section'
            ),
        );

        return $settings;
    }
}

