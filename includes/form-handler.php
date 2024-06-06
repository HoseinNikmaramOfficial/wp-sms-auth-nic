<?php
if (!defined('ABSPATH')) exit;

// Require the functions file if it's not already included
require_once plugin_dir_path(__FILE__) . 'functions.php';

// AJAX action for sending OTP during registration
add_action('wp_ajax_nopriv_send_otp_ajax', 'handle_send_otp_ajax');
add_action('wp_ajax_send_otp_ajax', 'handle_send_otp_ajax'); // If needed for logged-in users

// AJAX action for sending OTP during registration
add_action('wp_ajax_nopriv_send_register_otp_ajax', 'handle_send_register_otp_ajax');
add_action('wp_ajax_send_register_otp_ajax', 'handle_send_register_otp_ajax'); // If needed for logged-in users

// AJAX action for verifying OTP during login
add_action('wp_ajax_nopriv_verify_otp_ajax', 'handle_verify_otp_ajax');
add_action('wp_ajax_verify_otp_ajax', 'handle_verify_otp_ajax'); // If needed for logged-in users

/**
 * Handles sending OTP to the user's phone.
 */

function handle_send_register_otp_ajax() {
    // Verify nonce for security here (if using one)

    // Sanitize and validate the phone number input
    $phone_number = sanitize_text_field($_POST['phone']);
    
    if (empty($phone_number)) {
        wp_send_json_error('تلفن همراه را وارد کنید');
        wp_die();
    }
    
    $user_id = username_exists($phone_number);
    if (!$user_id && email_exists($phone_number) == false) {

        // Generate OTP
        $otp = generate_otp();

        // Store OTP in a transient for verification later. Adjust the expiration time as needed.
        set_transient('otp_' . $phone_number, $otp, 5 * MINUTE_IN_SECONDS);

        // Send the OTP via SMS
        $sent = send_sms_otp($phone_number, $otp);
        if ($sent) {
            wp_send_json_success('SENT');
        } else {
            wp_send_json_error('ارسال یکبار رمز با خطا مواجه شد');
        }

    } else {
        wp_send_json_error('EXIST');
    }


    wp_die(); // This is required to terminate immediately and return a proper response
}

function handle_send_otp_ajax() {
    // Verify nonce for security here (if using one)

    // Sanitize and validate the phone number input
    $phone_number = sanitize_text_field($_POST['phone']);
    if (empty($phone_number)) {
        wp_send_json_error('تلفن همراه را وارد کنید');
        wp_die();
    }

    // Generate OTP
    $otp = generate_otp();

    // Store OTP in a transient for verification later. Adjust the expiration time as needed.
    set_transient('otp_' . $phone_number, $otp, 5 * MINUTE_IN_SECONDS);

    // Send the OTP via SMS
    $sent = send_sms_otp($phone_number, $otp);
    if ($sent) {
        wp_send_json_success('SENT');
    } else {
        wp_send_json_error('ارسال یکبار رمز با خطا مواجه شد');
    }

    wp_die(); // This is required to terminate immediately and return a proper response
}

/**
 * Handles verifying the user's OTP during login.
 */
function handle_verify_otp_ajax() {
    // Sanitize and validate inputs
    $phone_number = sanitize_text_field($_POST['phone']);
    $input_otp = sanitize_text_field($_POST['otp']);

    if (empty($phone_number) || empty($input_otp)) {
        wp_send_json_error('تلفن همراه و یکبار رمز را وارد کنید');
        wp_die();
    }

    // Retrieve the correct OTP from the transient
    $correct_otp = get_transient('otp_' . $phone_number);

    // Verify OTP
    $is_valid = verify_otp($input_otp, $correct_otp);

    if ($is_valid) {
        wp_send_json_success('VERIFIED');
        // Here, proceed with login or registration logic as necessary.
    } else {
        wp_send_json_error('یکبار رمز وارد شده اشتباه است');
    }

    wp_die();
}



add_action('wp_ajax_nopriv_register_user_ajax', 'handle_register_user_ajax');
add_action('wp_ajax_register_user_ajax', 'handle_register_user_ajax'); 

function handle_register_user_ajax() {
    $phone = sanitize_text_field($_POST['phone']);
    $fullname = sanitize_text_field($_POST['fullname']);
    $password = $_POST['password']; // Ensure to sanitize

    $user_id = username_exists($phone);
    if (!$user_id && email_exists($phone) == false) {
        $user_id = wp_create_user($phone, $password, $phone . '@example.com'); // Consider generating a unique email or handling this differently
        
        if (!is_wp_error($user_id)) {
            // User creation was successful, now set the display name
            $userdata = array(
                'ID'           => $user_id,
                'display_name' => $fullname,
                // Additional fields can be added here as needed, such as 'first_name', 'last_name', 'nickname'
            );
            
            wp_update_user($userdata);
            
            wp_send_json_success('ثبت نام با موفقیت انجام شد');
        } else {
            // Handle errors (e.g., user couldn't be created)
            wp_send_json_error('ثبت نام با خطا مواجه شد');
        }
    } else {
        wp_send_json_error('تلفن همراه وارد شده از قبل وجود دارد');
    }

    wp_die();
}


// Login user with password
add_action('wp_ajax_nopriv_login_with_password_ajax', 'handle_login_with_password_ajax');
add_action('wp_ajax_login_with_password_ajax', 'handle_login_with_password_ajax');

function handle_login_with_password_ajax() {
    $phone = sanitize_text_field($_POST['phone']);
    $password = $_POST['password']; // Should be sanitized appropriately

    if (empty($phone) || empty($password)) {
        wp_send_json_error('تلفن همراه و رمز عبور را وارد کنید');
        wp_die();
    }
    
    $user = get_user_by('login', $phone);
    if ($user && wp_check_password($password, $user->data->user_pass, $user->ID)) {
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID);

        wp_send_json_success('ورود با موفقیت انجام شد');
    } else {
        wp_send_json_error('رمز عبور وارد شده صحیح نمی باشد');
    }

    wp_die();
}

// Login user with OTP
add_action('wp_ajax_nopriv_login_with_otp_ajax', 'handle_login_with_otp_ajax');
add_action('wp_ajax_login_with_otp_ajax', 'handle_login_with_otp_ajax');

function handle_login_with_otp_ajax() {
    $phone = sanitize_text_field($_POST['phone']);
    $otp = sanitize_text_field($_POST['otp']);

    // Verify OTP
    if (verify_otp_for_login($phone, $otp)) { // You'll need to define this function based on your OTP logic
        $user = get_user_by('login', $phone);
        if ($user) {
            wp_clear_auth_cookie();
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID);

            wp_send_json_success('ورود با موفقیت انجام شد');
        } else {
            wp_send_json_error('تلفن همراه وارد شده وجود ندارد');
        }
    } else {
        wp_send_json_error('یکبار رمز وارد شده اشتباه است');
    }

    wp_die();
}


