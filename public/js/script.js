jQuery(document).ready(function($) {
    
    let login_otp_toggle = 0;
        
    $('#otp-section, #password-section').hide();
    
    
     // Handle Send OTP button click
    $('#check-button').click(function() {
        
        set_loading(true);
        
        var phone = $('#phone-check').val();

        // AJAX request to send OTP
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'send_register_otp_ajax',
                phone: phone
            },
            success: function(response) {
                if(response['data'] == 'SENT'){
                    
                    $("#phone").val($("#phone-check").val());
                    $("#wp-sms-auth-register-form").show();
                    $("#wp-sms-auth-check-form").hide();
                    $('#otp-section').show();
                    $('#register-section').hide();
                    
                    notify(true,'یکبار رمز به تلفن همراه شما ارسال شد');
                }
                else if(response['data'] == 'EXIST'){
                    
                    $("#phone-login").val($("#phone-check").val());
                    $("#wp-sms-auth-login-form").show();
                    $("#wp-sms-auth-check-form").hide();
                   
                }
                else{
                    notify(response['success'],response['data']);
                }
                
                set_loading(false);
            }
        });
        
        
    });
    
    
    // Handle Send OTP button click
    $('#send-otp-register').click(function() {
        
        set_loading(true);
        
        var phone = $('#phone').val();

        // AJAX request to send OTP
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'send_register_otp_ajax',
                phone: phone
            },
            success: function(response) {
                if(response['data'] == 'SENT'){
                    $('#otp-section').show(); // Show the OTP input section
                    $('#register-section').hide();
                    notify(true,'یکبار رمز به تلفن همراه شما ارسال شد');
                }
                else{
                    notify(response['success'],response['data']);
                }
                
                set_loading(false);
            }
        });
        
        
    });

    // Handle Verify OTP button click
    $('#verify-otp').click(function() {
        
        set_loading(true);
        
        var phone = $('#phone').val();
        var otp = $('#otp').val();

        // AJAX request to verify OTP
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'verify_otp_ajax',
                phone: phone,
                otp: otp
            },
            success: function(response) {
                if(response['data'] == 'VERIFIED'){
                    $('#otp-section').hide();
                    $('#password-section').show();
                    notify(true,'یکبار رمز وارد شده مورد تایید می باشد');
                }
                else{
                    notify(response['success'],response['data']);
                }
                
                set_loading(false);
                
            }
        });
        
        
        
    });

    // Handle the final Registration form submission
    $('#wp-sms-auth-register-form').submit(function(e) {
        
        set_loading(true);
        
        e.preventDefault();

        var phone = $('#phone').val();
        var fullname = $('#fullname').val();
        var password = $('#password').val();

        // AJAX request to register the user
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'register_user_ajax',
                phone: phone,
                fullname:fullname,
                password: password
            },
            success: function(response) {
                if (response.success) {
                    notify(response['success'],response['data']);
                    setTimeout(GoBackWithRefresh(),1000);
                    
                } else {
                    notify(response['success'],response['data']);
                }
                
                set_loading(false);
                
            }
        });
    });
    
    
     // Toggle OTP login section
    $('#toggle-otp-login').click(function() {
        if(login_otp_toggle == 0){
            $('#otp-login-section').show();
            $('#password-login-section').hide();
            $('#toggle-otp-login').val("ورود با رمز عبور");
            login_otp_toggle = 1;
        }
        else{
            $('#otp-login-section').hide();
            $('#password-login-section').show();
            $('#toggle-otp-login').val("ورود با یکبار رمز");
            login_otp_toggle = 0;
        }
    });

    // Handle Send OTP button click for login
    $('#send-otp-login').click(function() {
        
        set_loading(true);
        
        var phone = $('#phone-login').val();

        // AJAX request to send OTP
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'send_otp_ajax', // Reusing the send OTP action
                phone: phone
            },
            success: function(response) {
                if(response['data'] == 'SENT'){
                    $('#send-otp-login').hide();
                    $('#login-with-password').hide();
                    $('#otp-login').show(); // Show the OTP input
                    $('#otp-login-label').show();
                    $('#login-with-otp').show(); // Show the login with OTP button
                    notify(true,'یکبار رمز به تلفن همراه شما ارسال شد');
                }
                else{
                   notify(response['success'],response['data']);
                }
                
                set_loading(false);
                
            }
        });
    });

    // Handle Login with Password button click
    $('#login-with-password').click(function(e) {
        
        set_loading(true);
        
        e.preventDefault();
        var phone = $('#phone-login').val();
        var password = $('#password-login').val();

        // AJAX request to login with password
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'login_with_password_ajax',
                phone: phone,
                password: password
            },
            success: function(response) {
                if (response.success) {
                    notify(response['success'],response['data']);
                    setTimeout(GoBackWithRefresh(),1000);
                    
                } else {
                    notify(response['success'],response['data']);
                }
                
                set_loading(false);
                
            }
        });
    });

    // Handle Login with OTP button click
    $('#login-with-otp').click(function(e) {
        
        set_loading(true);
        
        e.preventDefault();
        var phone = $('#phone-login').val();
        var otp = $('#otp-login').val();

        // AJAX request to login with OTP
        $.ajax({
            type: 'POST',
            url: myAjax.ajaxurl,
            data: {
                action: 'login_with_otp_ajax',
                phone: phone,
                otp: otp
            },
            success: function(response) {
                if (response.success) {
                    notify(response['success'],response['data']);
                    setTimeout(GoBackWithRefresh(),1000);
                    
                } else {
                    notify(response['success'],response['data']);
                }
                
                set_loading(false);
                
            }
        });
    });
    
    let notify = function(type,message){
        let notify_type;
        if(type == true)
            notify_type = 'success';
        else
            notify_type = 'error';
        
        const notyf = new Notyf();
        
        setTimeout(function(){
            notyf.open({
            type:notify_type,
            message:message,
            dismissible: true,
            icon: false,
        });
        },500);
        
    }

    let set_loading = function(mode){
        if(mode == true){
            $("#wp-sms-auth-check-form input").attr('disabled',true);
            $("#wp-sms-auth-register-form input").attr('disabled',true);
            $("#wp-sms-auth-login-form input").attr('disabled',true);
            $("#wp-sms-auth-loading").fadeIn();
        }
        else{
            setTimeout(function(){
                $("#wp-sms-auth-check-form input").attr('disabled',false);
                $("#wp-sms-auth-register-form input").attr('disabled',false);
                $("#wp-sms-auth-login-form input").attr('disabled',false);
                $("#wp-sms-auth-loading").fadeOut();
            },500);
        }
    }
    
    let GoBackWithRefresh = function (event) {
        if ('referrer' in document) {
            window.location = document.referrer;
            /* OR */
            //location.replace(document.referrer);
        } else {
            window.history.back();
        }
    }

    
});
