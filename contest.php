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

global $wpdb;
global $psc_options;

if (!defined('PSC_PLUGIN'))  define('PSC_PLUGIN', 'photoscontest');
if (!defined('PSC_ABSPATH'))  define('PSC_ABSPATH', dirname(__FILE__) . '/');
if (!defined('PSC_PATH')) define('PSC_PATH', plugin_dir_url(__FILE__));

add_shortcode( 'contest_register', 'psc_shortcode_register' );

register_activation_hook( __FILE__, 'psc_activation_init' );
register_deactivation_hook( __FILE__, 'psc_deactivate_init' );

add_action( 'wp_enqueue_scripts', 'psc_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'psc_enqueue_admin_scripts');

add_action( 'admin_init', 'psc_admin_init' );
add_action( 'admin_menu', 'psc_admin_menu' );
add_action( 'admin_notices', 'psc_admin_notices' );

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

    $tbl = $wpdb->prefix . 'psc_participants';
    
    $ptbl = "CREATE TABLE IF NOT EXISTS " . $tbl . "(id int(11) not null auto_increment, email varchar(128), first_name varchar(80),
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
    add_submenu_page('psc_overview', __('Votes', PSC_PLUGIN), __('Votes', PSC_PLUGIN), 'edit_pages', 'psc_votes', 'psc_admin_menu_item');
    add_submenu_page('psc_overview', __('Participants', PSC_PLUGIN), __('Participants', PSC_PLUGIN), 'edit_pages', 'psc_participants', 'psc_admin_menu_item');
    add_submenu_page('psc_overview', __('Configuration', PSC_PLUGIN), __('Configuration', PSC_PLUGIN), 'edit_pages','psc_settings', 'psc_admin_menu_item');
    
}

function psc_admin_notices() {
    // TODO
}

function psc_admin_menu_item() {
    global $wpdb;

    switch ($_GET['page']) {
     case 'psc_overview':
     case 'psc_votes':
     case 'psc_participants':
     case 'psc_settings':
	echo $_GET['page'];
	ob_start();
	include 'views/' . $_GET['page'] . '.php';
	echo ob_get_clean();

	break;
    }
    
}

function psc_shortcode_register() {
    ob_start();
    include 'views/register.php';
    return ob_get_clean();
}



function psc_format_date($timestamp) {
    return date("Y-m-d", $timestamp);
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
    if (isset($ps_options[$key])) {
	return $psc_options[$key];
    }
    
    return $default;
}

function psc_save_options() {
    
    if (!isset($_POST['psc_settings_nonce'])) return;

    check_admin_referer('psc_settings', 'psc_settings' . '_nonce');
 
    $options = array();
    
    update_option(PSC_PLUGIN, $options);
    
}
