<?php
if (!defined('ABSPATH')) exit;

function generate_otp() {
    $otp = rand(1000, 9999);
    return $otp;
}

function SMS($phone,$apikey,$pattern_id,$sender,$pattern,$value){
    return file_get_contents("http://ippanel.com:8080/?apikey={$apikey}=&pid={$pattern_id}&fnum={$sender}&tnum={$phone}&p1={$pattern}&v1={$value}");
}

function send_sms_otp($phone_number, $otp) {
    
    $options = get_option('wp_sms_auth_nic_settings');
    $otp_limitation_time = $options['otp_limitation_time'];

    // Check for OTP send limitation
    if (!empty($options['otp_limitation'])) {
        $last_send_time = get_transient('last_otp_send_time_' . $phone_number);
        if ($last_send_time !== false && (time() - $last_send_time) < $otp_limitation_time) { // 300 seconds or 5 minutes limitation
            // If the last OTP was sent less than 5 minutes ago, do not send a new OTP
            return false;
        }
    }

    // Proceed with sending the OTP
    $apikey = $options['apikey'];
    $pattern_id = $options['pattern_id'];
    $sender = $options['sender'];
    $pattern = $options['pattern'];
    $to = urlencode($phone_number);
    $message = str_replace('%otp%', $otp, $options['otp_message']);
    
    $response = SMS($to,$apikey,$pattern_id,$sender,$pattern,$otp);
    
    if (is_wp_error($response)) {
        return false;
    }

    // OTP sent successfully, update the last send time
    set_transient('last_otp_send_time_' . $phone_number, time(), $otp_limitation_time); // Expire in 5 minutes

    return true;
}


function verify_otp($input_otp, $correct_otp) {
    return $input_otp === $correct_otp;
}

function verify_otp_for_login($phone, $submitted_otp) {
    $stored_otp = get_transient('otp_' . $phone); // Retrieve the stored OTP
    return $submitted_otp === $stored_otp; // Compare the submitted OTP with the stored one
}
