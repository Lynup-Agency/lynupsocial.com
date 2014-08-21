<?php
/*
Plugin Name: Ninja Kick Contact Form (Share on Theme123.Net)
Plugin URI: http://thegleb.com/ninja-form-demo/
Description: Push Contact Form on every page of your site.
Version: 1.6.5
Authors: Gleb Polesskiy & Petr Gorkiy
Author URI: http://thegleb.com/
License: Commercial License
Text Domain: ninja-contact-form
Domain Path: /lang
*/


global $ncf_options;

load_plugin_textdomain('ninja-contact-form', false, basename( dirname( __FILE__ ) ) . '/lang' );

include_once(dirname(__FILE__) . '/settings.php');

add_action('wp_enqueue_scripts', 'ncf_scripts');

// if user signed in
add_action( 'wp_ajax_ncf_send_message', 'ncf_send_message' );
// if user not signed in
add_action( 'wp_ajax_nopriv_ncf_send_message', 'ncf_send_message' );

add_action( 'admin_menu', 'ncf_menu' );

function ncf_menu() {
    add_options_page( 'Ninja Contact Form Options', 'Ninja Contact Form', 'manage_options', 'ninja-contact-form-options', 'ninja_contact_form_page' );
}

/**
 * Settings page in the WP Admin
 */
function ninja_contact_form_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'ninja-contact-form' ) );
	}
  wp_enqueue_script("jquery");
	wp_enqueue_script( 'tinycolor', plugins_url('/js/tinycolor.js', __FILE__) );
	wp_enqueue_script( 'ninja_contact_form_colorpickersliders', plugins_url('/js/jquery.colorpickersliders.js', __FILE__) );

	//wp_register_style('open-sans-font', 'http://fonts.googleapis.com/css?family=Open+Sans:400,300' );
	wp_register_style('open-sans-font', '//fonts.googleapis.com/css?family=Open+Sans:300normal,400normal,400italic,600normal,600italic&subset=all' );

	wp_enqueue_style( 'open-sans-font' );
  wp_register_style('colorpickersliders-ui-css', plugins_url('/css/jquery.colorpickersliders.css', __FILE__));
	wp_enqueue_style( 'colorpickersliders-ui-css' );

  wp_register_style('admin-css', plugins_url('/css/admin.css', __FILE__));
	wp_enqueue_style( 'admin-css' );

	include_once(dirname(__FILE__) . '/options-page.php');
}


add_filter('plugin_action_links_ninja-contact-form/main.php', 'ncf_plugin_action_links', 10, 1);

function ncf_plugin_action_links($links) {
	$settings_page = add_query_arg(array('page' => 'ninja-contact-form-options'), admin_url('options-general.php'));
	$settings_link = '<a href="'.esc_url($settings_page).'">'.__('Settings', 'ninja-contact-form' ).'</a>';
	array_unshift($links, $settings_link);
	return $links;
}

add_action('wp_footer', 'ncf_main_html');

function ncf_main_html() {
	global $wpdb;
	$options = ncf_get_options();
	include_once(dirname(__FILE__) . '/ninja-contact-form.php');
}

function ncf_scripts() {
    $options = ncf_get_options();
	wp_enqueue_script(
		'ninja_form_send',
//	  plugins_url('/js/ninja-contact-form.js', __FILE__),
		plugins_url('/js/ninja-contact-form.min.js', __FILE__),
		array('jquery')
	);

	$social = array();
	if (!empty($options['ncf_facebook'])) $social['ncf_facebook'] = $options['ncf_facebook'];
	if (!empty($options['ncf_twitter'])) $social['ncf_twitter'] = $options['ncf_twitter'];
	if (!empty($options['ncf_pinterest'])) $social['ncf_pinterest'] = $options['ncf_pinterest'];
	if (!empty($options['ncf_youtube'])) $social['ncf_youtube'] = $options['ncf_youtube'];
	if (!empty($options['ncf_vimeo'])) $social['ncf_vimeo'] = $options['ncf_vimeo'];
	if (!empty($options['ncf_linkedin'])) $social['ncf_linkedin'] = $options['ncf_linkedin'];
	if (!empty($options['ncf_gplus'])) $social['ncf_gplus'] = $options['ncf_gplus'];
	if (!empty($options['ncf_rss'])) $social['ncf_rss'] = $options['ncf_rss'];

	$errors = array(
		"required" => __("* Please enter %%", 'ninja-contact-form' ),
		"min" => __("* %% must have at least %% characters.", 'ninja-contact-form' ),
		"max" => __("* %% can have at most %% characters.", 'ninja-contact-form' ),
		"matches" => __("* %% must match %%.", 'ninja-contact-form' ),
		"less" => __("* %% must be less than %%", 'ninja-contact-form' ),
		"greater" => __("* %% must be greater than %%.", 'ninja-contact-form' ),
		"numeric" => __("* %% must be numeric.", 'ninja-contact-form' ),
		"email" => __("* %% must be a valid email address.", 'ninja-contact-form' ),
		"ip" => __("* %% must be a valid ip address.", 'ninja-contact-form' ),
		"answer" => __("* Wrong %%", 'ninja-contact-form' )
	);
	wp_localize_script( 'ninja_form_send', 'NinjaContactFormOpts', array(
				'test_mode' => $options['ncf_test_mode'],
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
        'social' => $social,
        'theme' => $options['ncf_theme'],
				'sidebar_pos' => $options['ncf_sidebar_pos'],
        'flat_socialbar' => $options['ncf_flat_socialbar'],
				'base_color' => $options['ncf_base_color'],
				'humantest' => $options['ncf_enable_test'],
				'success' => $options['ncf_success_message'],
				'fade_content' => $options['ncf_fade_content'],
				'label' => $options['ncf_label_style'],
				'label_top' => $options['ncf_label_top'],
				'label_vis' => $options['ncf_label_vis'],
			  'label_scroll_selector' => $options['ncf_label_vis_selector'],
			  'label_mouseover' => $options['ncf_label_mouseover'],
				'bg' => $options['ncf_image_bg'],
				'path' => plugins_url('/img/', __FILE__),
			  'send_more_text' => __( "Send more", 'ninja-contact-form' ),
			  'try_again_text' => __( "Try again", 'ninja-contact-form' ),
			  'close_text' => __( "Close", 'ninja-contact-form' ),
			  'sending_text' => __( "Sending", 'ninja-contact-form' ),
			  'msg_fail_text' => __( "Something went wrong while sending your message", 'ninja-contact-form' ),
			  'errors' => $errors
        )
    );

	wp_register_style('open-sans-font', '//fonts.googleapis.com/css?family=Open+Sans:300normal,400normal,400italic,600normal,600italic&subset=all' );
	wp_enqueue_style( 'open-sans-font' );
	wp_register_style( 'ncf_styles', plugins_url('/css/ninja-contact-form.css', __FILE__) );
	wp_enqueue_style( 'ncf_styles' );
}

function ncf_send_message() {
	global $wpdb;
	header( "Content-Type: application/json" );

	$options = ncf_get_options();

	// get the submitted parameters
	$headers = array();
	$headers[] = 'From: ' .$_POST['ncf_name_field'] . ' <' . str_replace(array("\r", "\n", "\n", "\t", ",", ";"), '', $_POST['ncf_email_field']). ">\r\n";
	$ip = ncf_get_ip_address();
	$server = array_merge(array('HTTP_HOST'=>null, 'REQUEST_URI'=>null, 'HTTP_REFERER'=>null), $_SERVER);

	$result = wp_mail(
        @$options['ncf_email'],
        get_bloginfo('name') . ' ' . $options['ncf_email_title'], //__( " Contact Form Submission", 'ninja-contact-form' ),
        'Name: ' .  $_POST['ncf_name_field']  . "\n" .
        'Email: ' .  $_POST['ncf_email_field']  . "\n" .
	      'Sent from page: ' . ($server['HTTP_REFERER'] ? $server['HTTP_REFERER'] : 'Page not detected') . "\n" .
        'IP: ' . (isset($ip) ? $ip : 'IP not detected') . "\n" .
	      "\n\n----------------Message-----------------\n\n" .
        $_POST['ncf_message_field'] ,
        $headers
    );
	/*"This message was sent by ". esc_attr(  $_POST['ncf_name_field'] ). ' <' .
  str_replace(array("\r", "\n", "\n", "\t", ",", ";"), '', esc_attr( $_POST['ncf_email_field'])).
  "> from IP ". $ip . "\n----------------------------\n" . esc_textarea( $_POST['ncf_message_field'])*/

	if($result) {
        echo json_encode(array('success' => true, 'result' => $result));
       	die();
	}

	echo json_encode(array('success' => false, 'message' => __("Message not sent. An unknown error occurred.", 'ninja-contact-form' ), 'result' => $result));
	die();

}

function ncf_get_ip_address() {
 foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
     if (array_key_exists($key, $_SERVER) === true) {
         foreach (explode(',', $_SERVER[$key]) as $ip) {
            if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
            return $ip;
          }
        }
     }
  }
}
