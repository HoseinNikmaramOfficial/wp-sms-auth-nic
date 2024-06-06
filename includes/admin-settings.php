<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


function wp_sms_auth_nic_enqueue_admin_styles($hook_suffix) {
    wp_enqueue_style('wp-sms-auth-style', plugin_dir_url(__FILE__) . '../public/css/style.css');
    wp_enqueue_style('vazirmatn-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css');
}

add_action('admin_enqueue_scripts', 'wp_sms_auth_nic_enqueue_admin_styles');

// Add admin menu
function wp_sms_auth_nic_add_admin_menu() {
    add_menu_page(
        'ثبت نام / ورود',
        'ثبت نام / ورود',
        'manage_options',
        'wp_sms_auth_nic',
        'wp_sms_auth_nic_home_page',
        'dashicons-admin-network',
        81
    );
    
    add_submenu_page('wp_sms_auth_nic', 'خانه', 'خانه', 'manage_options', 'wp_sms_auth_nic', 'wp_sms_auth_nic_home_page');
    add_submenu_page('wp_sms_auth_nic', 'ترجمه', 'ترجمه', 'manage_options', 'wp_sms_auth_nic_form_texts', 'wp_sms_auth_nic_form_texts_page');
    add_submenu_page('wp_sms_auth_nic', 'تنظیمات', 'تنظیمات', 'manage_options', 'wp_sms_auth_nic_settings', 'wp_sms_auth_nic_settings_page');

}
add_action('admin_menu', 'wp_sms_auth_nic_add_admin_menu');



function wp_sms_auth_nic_home_page(){
    ?>
    <div class="wrap wp_sms_auth_nic_admin">
        <h2>خانه</h2>
        <form>
            <h2>جهت استفاده از افزونه میتوانید از کد های کوتاه زیر استفاده کنید</h2>
        </form>
        <p>از <code>[wp_sms_auth_register]</code> برای نمایش فرم ثبت نام استفاده کنید .</p>
        <p>از <code>[wp_sms_auth_login]</code> برای نمایش فرم ورود استفاده کنید .</p>
        <p>از <code>[wp_sms_auth_both]</code> برای نمایش فرم ثبت نام / ورود همزمان استفاده کنید .</p>
    </div>
<?php
}


function wp_sms_auth_nic_form_texts_page() {
    ?>
    <div class="wrap wp_sms_auth_nic_admin">
        <h2>ترجمه</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('wp_sms_auth_nic_translate');
            do_settings_sections('wp_sms_auth_nic_translate_form');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Admin page callback
function wp_sms_auth_nic_settings_page() { ?>
    <div class="wrap wp_sms_auth_nic_admin">
        <h2>تنظیمات</h2>
        <form action="options.php" method="post">
            <?php
            settings_fields('wp_sms_auth_nic');
            do_settings_sections('wp_sms_auth_nic_setting_form');
            submit_button('ذخیره تنظیمات');
            ?>
        </form>
    </div>
<?php }


function wp_sms_auth_nic_translate_init() {
    // Register a setting for form texts
    register_setting('wp_sms_auth_nic_translate', 'wp_sms_auth_nic_translate');

    add_settings_section(
        'wp_sms_auth_nic_translate_section', 
        'جهت تغییر متن های مورد استفاده در افزونه میتوانید از گزینه های زیر استفاده کنید .',
         null, 
        'wp_sms_auth_nic_translate_form'
    );

    // Register settings fields for each text you want customizable
    add_settings_field(
        'wp_sms_auth_nic_register_text', 
        __('متن دکمه ثبت نام', 'wp-sms-auth-nic'), 
        'wp_sms_auth_nic_translate_register_button_text_render', 
        'wp_sms_auth_nic_translate_form', 
        'wp_sms_auth_nic_translate_section'
    );
    // Repeat for other texts...
}
add_action('admin_init', 'wp_sms_auth_nic_translate_init');


function wp_sms_auth_nic_translate_register_button_text_render() {
    $options = get_option('wp_sms_auth_nic_translate');
    ?>
    <input type="text" name="wp_sms_auth_nic_translate[register_button_text]" value="<?php echo esc_attr($options['register_button_text'] ?? 'ثبت نام'); ?>" />
    <?php
}



// Initialize setting
function wp_sms_auth_nic_settings_init() {
    register_setting('wp_sms_auth_nic', 'wp_sms_auth_nic_settings');
    
    add_settings_section(
        'wp_sms_auth_nic_setting_section',
        'جهت دریافت کلید دسترسی ، شناسه پترن ، ارسال کننده و متغیر پترن از طریق پشتیبانی پنل پیامک خود اقدام نمایید .',
         null,
        'wp_sms_auth_nic_setting_form'
    );

    // Web Service api key
    add_settings_field(
        'wp_sms_auth_nic_apikey', 
        __( 'کلید دسترسی', 'wp-sms-auth-nic' ), 
        'wp_sms_auth_nic_apikey_render', 
        'wp_sms_auth_nic_setting_form', 
        'wp_sms_auth_nic_setting_section'
    );
    
    // Web Service pattern id
    add_settings_field(
        'wp_sms_auth_nic_pattern_id', 
        __( 'شناسه پترن', 'wp-sms-auth-nic' ), 
        'wp_sms_auth_nic_pattern_id_render', 
        'wp_sms_auth_nic_setting_form', 
        'wp_sms_auth_nic_setting_section'
    );
    
    // Web Service sender
    add_settings_field(
        'wp_sms_auth_nic_sender', 
        __( 'ارسال کننده', 'wp-sms-auth-nic' ), 
        'wp_sms_auth_nic_sender_render', 
        'wp_sms_auth_nic_setting_form', 
        'wp_sms_auth_nic_setting_section'
    );
    
    // Web Service pattern
    add_settings_field(
        'wp_sms_auth_nic_pattern', 
        __( 'متغیر پترن', 'wp-sms-auth-nic' ), 
        'wp_sms_auth_nic_pattern_render', 
        'wp_sms_auth_nic_setting_form', 
        'wp_sms_auth_nic_setting_section'
    );


    // Enable/Disable OTP Send Limitation
    add_settings_field(
        'wp_sms_auth_nic_otp_limitation', 
        __( 'محدودیت ارسال یکبار رمز', 'wp-sms-auth-nic' ), 
        'wp_sms_auth_nic_otp_limitation_render', 
        'wp_sms_auth_nic_setting_form', 
        'wp_sms_auth_nic_setting_section'
    );
    
     // Enable/Disable OTP Send Limitation
    add_settings_field(
        'wp_sms_auth_nic_otp_limitation_time', 
        __( 'محدودیت زمان ارسال یک بار رمز', 'wp-sms-auth-nic' ), 
        'wp_sms_auth_nic_otp_limitation_time_render', 
        'wp_sms_auth_nic_setting_form', 
        'wp_sms_auth_nic_setting_section'
    );
    
}


function wp_sms_auth_nic_field_phone_render() {
    // Fetch the options, providing a default array as a fallback
    $options = get_option('wp_sms_auth_nic_settings', array('wp_sms_auth_nic_field_phone' => ''));
    // Ensure the 'wp_sms_auth_nic_field_phone' index is available in the options array
    $phone = isset($options['wp_sms_auth_nic_field_phone']) ? $options['wp_sms_auth_nic_field_phone'] : '';
    ?>
    <input type='text' name='wp_sms_auth_nic_settings[wp_sms_auth_nic_field_phone]' value='<?php echo esc_attr($phone); ?>'>
    <?php
}

// Render function for api key
function wp_sms_auth_nic_apikey_render() {
    $options = get_option('wp_sms_auth_nic_settings');
    ?>
    <input type='text' name='wp_sms_auth_nic_settings[apikey]' value='<?php echo $options['apikey'] ?? ''; ?>'>
    <?php
}


// Render function for pattern id
function wp_sms_auth_nic_pattern_id_render() {
    $options = get_option('wp_sms_auth_nic_settings');
    ?>
    <input type='text' name='wp_sms_auth_nic_settings[pattern_id]' value='<?php echo $options['pattern_id'] ?? ''; ?>'>
    <?php
}


// Render function for sender
function wp_sms_auth_nic_sender_render() {
    $options = get_option('wp_sms_auth_nic_settings');
    ?>
    <input type='text' name='wp_sms_auth_nic_settings[sender]' value='<?php echo $options['sender'] ?? '3000505'; ?>'>
    <?php
}

// Render function for pattern
function wp_sms_auth_nic_pattern_render() {
    $options = get_option('wp_sms_auth_nic_settings');
    ?>
    <input type='text' name='wp_sms_auth_nic_settings[pattern]' value='<?php echo $options['pattern'] ?? ''; ?>'>
    <?php
}


// Render function for Default OTP Message
function wp_sms_auth_nic_otp_message_render() {
    $options = get_option('wp_sms_auth_nic_settings');
    ?>
    <input type='text' name='wp_sms_auth_nic_settings[otp_message]' value='<?php echo $options['otp_message'] ?? ''; ?>'>
    <p class="description"><?php _e('از %otp% برای جایگزاری یکبار رمز استفاده کنید', 'wp-sms-auth-nic'); ?></p>
    <?php
}

// Render function for OTP Send Limitation
function wp_sms_auth_nic_otp_limitation_render() {
    $options = get_option('wp_sms_auth_nic_settings');
    ?>
    <input type='checkbox' name='wp_sms_auth_nic_settings[otp_limitation]' <?php checked($options['otp_limitation'] ?? '', 'on'); ?>>
    <label for="otp_limitation"><?php _e('فعال سازی محدودیت ارسال یک بار رمز در بازه زمانی', 'wp-sms-auth-nic'); ?></label>
    <?php
}

// Render function for OTP Send Limitation
function wp_sms_auth_nic_otp_limitation_time_render() {
    $options = get_option('wp_sms_auth_nic_settings');
    ?>
    <input type='text' name='wp_sms_auth_nic_settings[otp_limitation_time]' value='<?php echo $options['otp_limitation_time'] ?? '0'; ?>'>
    <p class="description"><?php _e('مدت زمان محدودیت ارسال مجدد یکبار رمز به ثانیه', 'wp-sms-auth-nic'); ?></p>
    <?php
}


add_action('admin_init', 'wp_sms_auth_nic_settings_init');
