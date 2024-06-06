1 - sms panel used is ippanel but you can change sms function in function.php file .

2 - create new page and use shortcodes to generate login or register or both forms and then change the default account page from woocommerce settings .

3 - uncheck all checkboxes in privacy and users setting in woocommerce .

4 - replace this line in your template checkout form or woocommerce check from :

// If checkout registration is disabled and not logged in, the user cannot checkout.

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {

	echo do_shortcode('[wp_sms_auth_both]');
 
	return;
 
}

instead of this :

// If checkout registration is disabled and not logged in, the user cannot checkout.

if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {

	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
 
	return;
 
}

