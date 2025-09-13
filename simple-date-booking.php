<?php
/**
 * Plugin Name: Simple Date Booking
 * Plugin URI: https://example.com/simple-date-booking
 * Description: A WordPress plugin for simple date booking with admin approval.
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: simple-date-booking
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('SIMPLE_BOOKING_VERSION', '1.0.0');
define('SIMPLE_BOOKING_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SIMPLE_BOOKING_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include files
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Activator.php';
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Deactivator.php';
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Uninstaller.php';
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Admin/Menu.php';
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Admin/ListTable.php';
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Admin/Actions.php';
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Frontend/Shortcode.php';
require_once SIMPLE_BOOKING_PLUGIN_DIR . 'includes/Api/Routes.php';

// Register hooks
register_activation_hook(__FILE__, array('Simple_Booking_Activator', 'activate'));
register_deactivation_hook(__FILE__, array('Simple_Booking_Deactivator', 'deactivate'));
register_uninstall_hook(__FILE__, array('Simple_Booking_Uninstaller', 'uninstall'));

// Initialize the plugin
function simple_booking_init() {
    // Load textdomain
    load_plugin_textdomain('simple-date-booking', false, dirname(plugin_basename(__FILE__)) . '/languages/');

    // Initialize classes
    new Simple_Booking_Admin_Menu();
    new Simple_Booking_Admin_Actions();
    new Simple_Booking_Frontend_Shortcode();
    new Simple_Booking_Api_Routes();
}
add_action('plugins_loaded', 'simple_booking_init');

// Enqueue scripts and styles
function simple_booking_enqueue_scripts() {
    if (!is_admin()) {
        wp_enqueue_script('simple-booking-frontend', SIMPLE_BOOKING_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), time(), true);
        wp_enqueue_style('simple-booking-frontend', SIMPLE_BOOKING_PLUGIN_URL . 'assets/css/frontend.css', array(), time());
        wp_localize_script('simple-booking-frontend', 'simple_booking_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'rest_url' => rest_url('simple-booking/v1/submit'),
            'nonce' => wp_create_nonce('wp_rest'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'simple_booking_enqueue_scripts');

function simple_booking_admin_enqueue_scripts($hook) {
    if (is_admin()) {
        wp_enqueue_script('simple-booking-admin', SIMPLE_BOOKING_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), time(), true);
        wp_enqueue_style('simple-booking-admin', SIMPLE_BOOKING_PLUGIN_URL . 'assets/css/admin.css', array(), time());
    }
}
add_action('admin_enqueue_scripts', 'simple_booking_admin_enqueue_scripts');
?>
