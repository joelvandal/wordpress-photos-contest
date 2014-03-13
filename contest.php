<?php
/**
 * @package   Photos_Contest
 * @author    Joel Vandal <joel@vandal.ca>
 * @license   GPL-2.0+
 * @link      https://github.com/joelvandal/wordpress-photos-contest/wiki
 * @copyright 2014 Joel Vandal
 *
 * @wordpress-plugin
 * Plugin Name:		Photos Contest
 * Plugin URI:        	https://github.com/joelvandal/wordpress-photos-contest/wiki
 * Description:       	Create Photos Contest
 * Version:           	1.0.0
 * Author:       	Joel Vandal
 * Author URI:       	http://joel.vandal.ca/
 * Text Domain:       	photoscontest
 * License:           	GPL-2.0+
 * License URI:       	http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: 	https://github.com/joelvandal/wordpress-photos-contest
 */

global $wpdb, $psc_options;

define('PSC_PLUGIN', 'photoscontest');
define('PSC_ABSPATH', dirname(__FILE__) . '/');
define('PSC_PATH', plugin_dir_url(__FILE__));

define('PSC_TABLE_VOTES', $wpdb->prefix . 'psc_votes');
define('PSC_TABLE_PARTICIPANTS', $wpdb->prefix . 'psc_participants');

require PSC_ABSPATH . 'lib/Tables.php';

psc_load_options();

if (defined('WP_DEBUG') && WP_DEBUG) {
    psc_activation_init();
}

add_shortcode( 'contest_register', 'psc_shortcode_register' );

register_activation_hook( __FILE__, 'psc_activation_init' );
register_deactivation_hook( __FILE__, 'psc_deactivate_init' );

add_action( 'wp_enqueue_scripts', 'psc_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'psc_enqueue_admin_scripts');

add_action( 'admin_init', 'psc_admin_init' );
add_action( 'admin_menu', 'psc_admin_menu' );
add_action( 'admin_notices', 'psc_admin_notices' );
add_action( 'admin_head', 'psc_admin_headers' );

function psc_enqueue_scripts() {
    
    wp_enqueue_script('jquery');
    wp_enqueue_style('dropzone', PSC_PATH . '/css/dropzone.css');
    wp_enqueue_script('dropzone', PSC_PATH . '/js/dropzone.js', array('jquery'));
    
    wp_register_script('fancybox', PSC_PATH. '/js/fancybox.js');
    wp_enqueue_script('fancybox', array('jquery'));
    
    wp_register_style('fancybox', PSC_PATH . '/css/fancybox.css');
    wp_enqueue_style('fancybox');
    
}

function psc_enqueue_admin_scripts() {
    
    // using jquery-ui
    wp_enqueue_script('jquery-ui-core');
    
    if ($_GET['page'] == 'psc_settings') {
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_style('jquery-ui', PSC_PATH . '/css/jquery-ui.css');
    }

}

function psc_activation_init() {
    global $wpdb;

    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_VOTES . " (id int(11) not null auto_increment, voter_name varchar(255), voter_email varchar(255), voter_ip varchar(80), vote_date int(11), participant_id int(11), primary key(id), key(participant_id))";
    $wpdb->query($ptbl);
    
    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_PARTICIPANTS . " (id int(11) not null auto_increment, email varchar(128), first_name varchar(80),
									last_name varchar(80), age int(11), sex varchar(1), project_name varchar(80),
									project_category varchar(80), project_description text, mail_site int(1),
									mail_contest int(1), approved int(1) default 0, primary key (id), key(email))";
    $wpdb->query($ptbl);
    
}

function psc_deactivation_init() {
    // TODO
}

function psc_admin_init() {
    
    switch ($_GET['page']) {
     case 'psc_settings':
	psc_save_options();
	break;
    }
    
}

function psc_admin_menu() {
    
    add_menu_page(__('Photos Contest', PSC_PLUGIN), __('Photos Contest', PSC_PLUGIN), 'edit_pages', 'psc_overview', 'psc_admin_menu_item');
    add_submenu_page('psc_overview', __('Overview', PSC_PLUGIN), __('Overview', PSC_PLUGIN), 'edit_pages', 'psc_overview', 'psc_admin_menu_item');
    add_submenu_page('psc_overview', __('Participants', PSC_PLUGIN), __('Participants', PSC_PLUGIN), 'edit_pages', 'psc_participants', 'psc_admin_menu_item');
    add_submenu_page('psc_overview', __('Votes', PSC_PLUGIN), __('Votes', PSC_PLUGIN), 'edit_pages', 'psc_votes', 'psc_admin_menu_item');
    add_submenu_page('psc_overview', __('Configuration', PSC_PLUGIN), __('Configuration', PSC_PLUGIN), 'edit_pages','psc_settings', 'psc_admin_menu_item');
    
}

function psc_admin_menu_item() {

    switch ($_GET['page']) {
     case 'psc_overview':
     case 'psc_votes':
     case 'psc_participants':
     case 'psc_settings':
	ob_start();
	include 'views/' . $_GET['page'] . '.php';
	echo ob_get_clean();
	break;
	
     default:
	echo 'unknown section';
	break;
    }
    
}

function psc_admin_notices() {
    // TODO
}

function psc_admin_headers() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;

    echo '<style type="text/css">';
    
    switch($page) {
	
     case 'psc_votes':
	echo '.wp-list-table .column-name { width: 30%; }';
	echo '.wp-list-table .column-ip_address { width: 200px; }';
	echo '.wp-list-table .column-vote_date { width: 200px; }';
	break;
	
     case 'psc_participants':
	echo '.wp-list-table .column-image { width: 160px; }';
	echo '.wp-list-table .column-name { width: 15%; }';
	echo '.wp-list-table .column-email { width: 10%; }';
	echo '.wp-list-table .column-views { width: 5%; text-align: center; }';
	echo '.wp-list-table .column-votes { width: 5%; text-align: center; }';
	echo '.wp-list-table .column-status { width: 10%; text-align: center; }';
	break;
    }
    
    echo '</style>';
    
}

function psc_format_date($timestamp) {
    return date("Y-m-d", $timestamp);
}

function psc_format_datetime($timestamp) {
    return date("Y-m-d H:i:s", $timestamp);
}


function psc_load_options() {
    
    global $psc_options;
    $defaults = array();
    
    $psc_options = get_option(PSC_PLUGIN);
    
    foreach ($defaults as $k => $v) {
	if (!isset($psc_options[$k])) $psc_options[$k] = $v;
    }
}

function psc_get_option($key, $default = null) {

    global $psc_options;
    if (isset($psc_options[$key])) {
	return $psc_options[$key];
    }
    
    return $default;
}

function psc_save_options() {
    
    if (!isset($_POST['psc_settings_nonce'])) return;

    check_admin_referer('psc_settings', 'psc_settings' . '_nonce');

    $options = array();
    
    if (isset($_POST['vote_open_date'])) {
	$date = trim($_POST['vote_open_date']);
	
	if (!empty($date)) {
	    $options['vote_open_date'] = $date;
	}
    }
    
    if (isset($_POST['vote_close_date'])) {
	$date = trim($_POST['vote_close_date']);
	
	if (!empty($date)) {
	    $options['vote_close_date'] = $date;
	}
    }

    if (isset($_POST['google_api_key'])) {
	$options['google_api_key'] = $_POST['google_api_key'];
    }
    
    if (isset($_POST['facebook_client_id'])) {
	$options['facebook_client_id'] = $_POST['facebook_client_id'];
    }
    if (isset($_POST['facebook_secret_key'])) {
	$options['facebook_secret_key'] = $_POST['facebook_secret_key'];
    }
    
    update_option(PSC_PLUGIN, $options);

    wp_redirect(admin_url('admin.php?page=psc_settings'));
    
}


function psc_image($email) {
    
    $thumbW = 150;
    $thumbH = 150;
    
    $viewW = 1920;
    $viewH = 1440;

    $image_file = PSC_ABSPATH . '/uploads/' . md5($email) . '.jpg';
    $dest_file = PSC_ABSPATH . '/uploads/' . md5($email);
    
    if (!file_exists($image_file)) {
	$image_file = PSC_ABSPATH . '/uploads/' . md5($email) . '.png';
    }
    
    if (!file_exists($image_file)) {
	$image_file = PSC_ABSPATH . '/uploads/' . md5($email) . '.gif';
    }
    
    if (!file_exists($image_file)) {
	return false;
    }
    
    $thumbFile = $dest_file . '-thumb.png';
    
    if (!file_exists($thumbFile)) {
	$image_thumbs = wp_get_image_editor($image_file);
	if (!is_wp_error($image_thumbs)) {
	    
	    $size = $image_thumbs->get_size();
	    $w0 = $size['width'];
	    $h0 = $size['height'];
	    
	    $w = round($w0 * ( $thumbW / $h0));
	    $h = $thumbH;
	    
	    $image_thumbs->resize($w, $h, false);
	    $image_thumbs->save($thumbFile);
	}
    }
    
    $viewFile = $dest_file . '-view.png';
    if (!file_exists($viewFile)) {
	$image_view = wp_get_image_editor($image_file); // WP_Image_Editor
	if (!is_wp_error($image_view)) {
	    $image_view->resize($viewW, $viewH, false);
	    $image_view->save($viewFile);
	}
    }
}

function psc_shortcode_register() {
    ob_start();
    include 'views/register.php';
    return ob_get_clean();
}


