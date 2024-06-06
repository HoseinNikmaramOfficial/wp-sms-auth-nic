<div id="wp-sms-auth-loading" class="wp-sms-auth-loading" style="display:none">
    <p>درحال بارگزاری</p>
    <img src="<?=plugin_dir_url(__FILE__);?>images/loading.svg" />
</div>

<!-- Check Form -->
<form class="wp-sms-auth-container" id="wp-sms-auth-check-form" action="" method="post">
    
     <div id="check-section" style="">
        <label for="phone-check">تلفن همراه:</label>
        <input type="text" id="phone-check" name="phone" required>
        <input type="button" id="check-button" value="ثبت نام / ورود">
    </div>

</form>

<!-- Registration Form -->
<form class="wp-sms-auth-container" id="wp-sms-auth-register-form" action="" method="post"  style="display:none">
    
     <div id="register-section" style="">
        <label for="phone">تلفن همراه:</label>
        <input type="text" id="phone" name="phone" required>
        <input type="button" id="send-otp-register" value="ارسال یکبار رمز">
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

<!-- Login Form -->
<form class="wp-sms-auth-container" id="wp-sms-auth-login-form" action="" method="post" style="display:none">
    <label for="phone-login">تلفن همراه:</label>
    <input type="text" id="phone-login" name="phone" required>

    <div id="password-login-section">
        <label for="password-login">رمز عبور:</label>
        <input type="password" id="password-login" name="password">
        <input type="submit" value="ورود" id="login-with-password">
    </div>

    <div id="otp-login-section" style="display:none;">
        <input type="button" id="send-otp-login" value="ارسال یکبار رمز">
        <label id="otp-login-label" for="otp-login" style="display:none;">یکبار رمز:</label>
        <input type="text" id="otp-login" name="otp" style="display:none;">
        <input type="submit" value="ورود" id="login-with-otp" style="display:none;">
    </div>

    <input type="button" value="ورود با یکبار رمز" id="toggle-otp-login">
</form>
