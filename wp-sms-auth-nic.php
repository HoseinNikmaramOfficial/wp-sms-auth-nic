<?php
/**
 * Plugin Name: WP SMS Auth Nic
 * Text Domain: wp-sms-auth-nic
 * Description: A WordPress plugin for SMS-based OTP authentication.
 * Version: 1.0
 * Author: Hosein Nikmaram
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue scripts and styles
function wp_sms_auth_nic_enqueue_scripts() {
    wp_enqueue_script('wp-sms-auth-script', plugin_dir_url(__FILE__) . 'public/js/script.js', array('jquery'), '1.0', true);
    wp_enqueue_style('wp-sms-auth-style', plugin_dir_url(__FILE__) . 'public/css/style.css');
    wp_enqueue_style('vazirmatn-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css');
    wp_enqueue_script('notyf-script','https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js');
    wp_enqueue_style('notyf-style', 'https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css');

    wp_localize_script('wp-sms-auth-script', 'myAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'someNonce' => wp_create_nonce('my_ajax_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'wp_sms_auth_nic_enqueue_scripts');

// Include admin settings and other necessary files
require_once plugin_dir_path(__FILE__) . 'includes/functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php'; // Admin settings functionality



// Shortcodes for registration and login forms
function wp_sms_auth_registration_form_shortcode() {
    ob_start();
    if ( is_user_logged_in() ) {
		echo '<script type="text/javascript">
				   window.location = "/my-account/"
			  </script>';
	}
    include plugin_dir_path(__FILE__) . 'public/register.php';
    return ob_get_clean();
}
add_shortcode('wp_sms_auth_register', 'wp_sms_auth_registration_form_shortcode');

function wp_sms_auth_login_form_shortcode() {
    ob_start();
    if ( is_user_logged_in() ) {
		echo '<script type="text/javascript">
				   window.location = "/my-account/"
			  </script>';
	}
    include plugin_dir_path(__FILE__) . 'public/login.php';
    return ob_get_clean();
}
add_shortcode('wp_sms_auth_login', 'wp_sms_auth_login_form_shortcode');

function wp_sms_auth_both_form_shortcode() {
    ob_start();
    if ( is_user_logged_in() ) {
		echo '<script type="text/javascript">
				   window.location = "/my-account/"
			  </script>';
	}
    include plugin_dir_path(__FILE__) . 'public/both.php';
    return ob_get_clean();
}
add_shortcode('wp_sms_auth_both', 'wp_sms_auth_both_form_shortcode');


function wp_sms_auth_nic_add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=wp_sms_auth_nic') . '">تنظیمات</a>';
    array_push($links, $settings_link);
    return $links;
}

add_filter("plugin_action_links_wp-sms-auth-nic/wp-sms-auth-nic.php", 'wp_sms_auth_nic_add_settings_link');

