<?php
/*
Plugin Name: PayPal Donation Button
Plugin URI: https://wordpress.org/plugins/paypal-donation-button/
Description: A simple PayPal donation button WordPress plugin.
Version: 1.0.1
Author: Sayful Islam
Author URI: http://www.sayful.net
License: GPLv2 or later
*/

// Activation Hook. Check if settings exists, if not register defaults.
function paypal_donation_button_activation()
{
	$options_array = array(
        'paypal_user_id' => get_option('admin_email'), 
        'paypal_button' => 'large', 
      	'currency' => 'USD', 
      	'target' => '_blank', 
    );
	if ( get_option( 'paypal_donation_button_options' ) !== false ) {
		// The option already exists, so we just update it.
      	update_option( 'paypal_donation_button_options', $options_array );
   } else{
   		// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
   		add_option( 'paypal_donation_button_options', $options_array );
   }
}
register_activation_hook( __FILE__, 'paypal_donation_button_activation' );

// Adds  [paypal_donation_button] shortcode
function paypal_donation_button_shortcode() {

	$options = get_option('paypal_donation_button_options');

	// Checks Which Image To Use
	switch ($options['paypal_button']) {

	    case 'small':
	        $url = 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif';
	        break;
	    case 'medium':
	        $url = 'https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif';
	        break;
	    case 'large':
	        $url = 'https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif';
	        break;
	    default:
	    	$url = 'https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif';

	}

	return '<form  target="'.$options['target'].'" action="https://www.paypal.com/cgi-bin/webscr" method="post">
    			<div class="paypal_donation_button">
			        <input type="hidden" name="cmd" value="_donations">
			        <input type="hidden" name="business" value="'.$options['paypal_user_id'].'">
			        <input type="hidden" name="rm" value="0">
			        <input type="hidden" name="currency_code" value="'.$options['currency'].'">
			        <input type="image" src="'.$url.'" name="submit" alt="PayPal - The safer, easier way to pay online.">
			        <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			    </div>
			</form>';
};
add_shortcode('paypal_donation_button','paypal_donation_button_shortcode');

// To add shortcode in text widgets
add_filter('widget_text', 'do_shortcode');

function paypal_donation_button_custom_style() {
	?>
	<style type="text/css">
		.paypal_donation_button:before,
		.paypal_donation_button:after {
		    content: " ";
		    display: table;
		}
		.paypal_donation_button:after {
			clear: both;
		}
		.paypal_donation_button {
			max-width: 147px;
			margin: 0 auto;
			padding: 0;
			display: block;
		}
	</style>
	<?php
}
add_action('wp_head', 'paypal_donation_button_custom_style');


function paypal_donation_button_callback() {
	// Optional Callback
}

// Generate INPUT Field form form settings [EMAIL]
function paypal_donation_button_user_id_callback() {
	$options = get_option('paypal_donation_button_options');
	echo "<input class='regular-text ltr' name='paypal_donation_button_options[paypal_user_id]' id='paypal_user_id' type='email' value='{$options['paypal_user_id']}'/>";
}



// Generate INPUT Field form form settings [RADIO]
function paypal_donation_button_button_callback() {
	$options = get_option('paypal_donation_button_options');
	?>
	<p>
		<label>
			<input type='radio' name='paypal_donation_button_options[paypal_button]' value='small' <?php if($options['paypal_button'] == 'small') { echo 'checked'; }  ?>>
			<img src='https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif' alt='small' style='vertical-align: middle;margin-left: 15px;'>
		</label>
	</p>

	<p>
		<label>
			<input type='radio' name='paypal_donation_button_options[paypal_button]' value='medium' <?php if($options['paypal_button'] == 'medium') { echo 'checked'; } ?>>
			<img src='https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif' alt='medium' style='vertical-align: middle;margin-left: 15px;'>
		</label>
	</p>

	<p>
		<label>
			<input type='radio' name='paypal_donation_button_options[paypal_button]' value='large' <?php if($options['paypal_button'] == 'large') { echo 'checked'; } ?>>
			<img src='https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif' alt='large' style='vertical-align: middle;margin-left: 15px;'></br>
		</label>
	</p>

	<?php
}



// Generate INPUT Field form form settings [DROPDOWN]
function paypal_donation_button_currency_callback() {
	$options = get_option('paypal_donation_button_options');

	$currency = array(
					'AUD' => 'Australian Dollars (A $)',
					'BRL' => 'Brazilian Real',
					'CAD' => 'Canadian Dollars (C $)',
					'CZK' => 'Czech Koruna',
					'DKK' => 'Danish Krone',
					'EUR' => 'Euros (€)',
					'HKD' => 'Hong Kong Dollar ($)',
					'HUF' => 'Hungarian Forint',
					'ILS' => 'Israeli New Shekel',
					'JPY' => 'Yen (¥)',
					'MYR' => 'Malaysian Ringgit',
					'MXN' => 'Mexican Peso',
					'NOK' => 'Norwegian Krone',
					'NZD' => 'New Zealand Dollar ($)',
					'PHP' => 'Philippine Peso',
					'PLN' => 'Polish Zloty',
					'GBP' => 'Pounds Sterling (£)',
					'RUB' => 'Russian Ruble',
					'SGD' => 'Singapore Dollar ($)',
					'SEK' => 'Swedish Krona',
					'CHF' => 'Swiss Franc',
					'TWD' => 'Taiwan New Dollar',
					'THB' => 'Thai Baht',
					'TRY' => 'Turkish Lira',
					'USD' => 'U.S. Dollars ($)',
	 			);
	?>
	<select id='currency_code' name='paypal_donation_button_options[currency]'>
		<?php
			foreach($currency as $code => $label) :
				if( $code == $options['currency'] ) { $selected = "selected='selected'"; } else { $selected = ''; }
				echo "<option {$selected} value='{$code}'>{$label}</option>";
			endforeach;	
		?>
	</select>
	<?php
}


function paypal_donation_button_target_callback() {
	$options = get_option('paypal_donation_button_options');
	$target = array(
		'_blank' => 'Blank',
		'_self' => 'Self'
	);
	?>
	<select id='target' name='paypal_donation_button_options[target]'>
		<?php
			foreach($target as $key => $label) :
				if( $key == $options['target'] ) { $selected = "selected='selected'"; } else { $selected = ''; }
				echo "<option {$selected} value='{$key}'>{$label}</option>";
			endforeach;
		?>
	</select>
	<p class="description"><?php _e('Select "Blank" to open the PayPal window in a new window or tab (this is default). Selcet "Self" to open the PayPal window in the same frame as it was clicked.') ?></p>
	<?php
}


// Register All Settings And And Setting Fields as Used in wordpress
function paypal_donation_button_settings_and_fields() {

	// $option_group, $option_name, $sanitize_callback
	register_setting(
		'paypal_donation_button_options',
		'paypal_donation_button_options'
	);

	// $id, $title, $callback, $page
	add_settings_section(
		'donate_plugin_main_section',
		__('Main Settings'),
		'paypal_donation_button_callback',
		__FILE__
	);

	// $id, $title, $callback, $page, $section, $args
	add_settings_field(
		'paypal_user_id', 
		__('PayPal ID:'), 
		'paypal_donation_button_user_id_callback', 
		__FILE__, 
		'donate_plugin_main_section',
		array( 'label_for' => 'paypal_user_id' )
	);

	// $id, $title, $callback, $page, $section, $args
	add_settings_field(
		'paypal_button', 
		__('Choose Donate Button:'), 
		'paypal_donation_button_button_callback', 
		__FILE__, 
		'donate_plugin_main_section',
		array( 'label_for' => 'paypal_button' )
	);

	// $id, $title, $callback, $page, $section, $args
	add_settings_field(
		'currency', 
		__('Select Currency:'), 
		'paypal_donation_button_currency_callback', 
		__FILE__, 
		'donate_plugin_main_section',
		array( 'label_for' => 'currency' )
	);

	// $id, $title, $callback, $page, $section, $args
	add_settings_field(
		'target', 
		__('Open PayPal:'), 
		'paypal_donation_button_target_callback', 
		__FILE__, 
		'donate_plugin_main_section',
		array( 'label_for' => 'target' )
	);
}

add_action('admin_init', 'paypal_donation_button_settings_and_fields');


// Admin Menu Action Hook
function paypal_donation_button_options_init() {

	// page_title,  menu_title, capability, menu_slug, function
	add_options_page(
		__('PayPal Donation'),
		__('PayPal Donation'),
		'administrator',
		__FILE__,
		'paypal_donation_button_options_page'
	);
}
add_action('admin_menu', 'paypal_donation_button_options_init');

// Generate HTML of main options page
function paypal_donation_button_options_page() {

	?>
		<div class="wrap">
    		<h2><?php _e('PayPal Donation Button Settings') ?></h2>
			<form method="post" action="options.php" enctype="multipart/form-data">
			<?php 
				// $option_group
				settings_fields( 'paypal_donation_button_options' );

				// $page 
				do_settings_sections( __FILE__ );

				// Submit Button
				submit_button();
			?>
			</form>
		</div>
	<?php
}