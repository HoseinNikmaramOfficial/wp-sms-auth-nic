<div id="wp-sms-auth-loading" class="wp-sms-auth-loading" style="display:none">
    <p>درحال بارگزاری</p>
    <img src="<?=plugin_dir_url(__FILE__);?>images/loading.svg" />
</div>

<!-- Login Form -->
<form class="wp-sms-auth-container" id="wp-sms-auth-login-form" action="" method="post">
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
