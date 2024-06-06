<?php
$options = get_option('wp_sms_auth_nic_translate');
$register_button_text = $options['register_button_text'] ?? 'ثبت نام'; // Default to 'Register' if not set
?>
<div id="wp-sms-auth-loading" class="wp-sms-auth-loading" style="display:none">
    <p>درحال بارگزاری</p>
    <img src="<?=plugin_dir_url(__FILE__);?>images/loading.svg" />
</div>

<!-- Registration Form -->
<form class="wp-sms-auth-container" id="wp-sms-auth-register-form" action="" method="post">
    
     <div id="register-section" style="">
        <label for="phone">تلفن همراه:</label>
        <input type="text" id="phone" name="phone" required>
        <input type="button" id="send-otp-register" value="<?=$register_button_text;?>">
    </div>

    <!-- Initially hidden OTP field -->
    <div id="otp-section" style="display:none;">
        <label for="otp">یکبار رمز:</label>
        <input type="text" id="otp" name="otp" required>
        <input type="button" id="verify-otp" value="تایید یکبار رمز">
    </div>

    <!-- Initially hidden Password field -->
    <div id="password-section" style="display:none;">
        <label for="fullname">نام نمایشی :</label>
        <input type="text" id="fullname" name="fullname" required>
        <label for="password">رمز عبور:</label>
        <input type="password" id="password" name="password" required>
        <input id="final-register-button" type="submit" value="ثبت نام">
    </div>
</form>
