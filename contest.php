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

global $wpdb, $psc_options, $psc_admin_notices;

global $psc_category_types;

define('PSC_PLUGIN', 'photoscontest');
define('PSC_ABSPATH', dirname(__FILE__) . '/');
define('PSC_PATH', plugin_dir_url(__FILE__));

define('PSC_TABLE_VOTES', $wpdb->prefix . 'psc_votes');
define('PSC_TABLE_PARTICIPANTS', $wpdb->prefix . 'psc_participants');
define('PSC_TABLE_CATEGORIES', $wpdb->prefix . 'psc_categories');

$psc_category_types = array('school'     => __('School', PSC_PLUGIN),
			    'class_name' => __('Class Name', PSC_PLUGIN),
			    'project'    => __('Project', PSC_PLUGIN));

require PSC_ABSPATH . 'lib/Tables.php';

psc_load_options();

if (defined('WP_DEBUG') && WP_DEBUG) {
    psc_activation_init();
}

add_shortcode( 'contest_register', 'psc_shortcode_register' );
add_shortcode( 'contest_participants', 'psc_shortcode_participants' );

// add_shortcode( 'contest_register', 'psc_shortcode_register' );

register_activation_hook( __FILE__, 'psc_activation_init' );
register_deactivation_hook( __FILE__, 'psc_deactivate_init' );

add_action( 'wp_enqueue_scripts', 'psc_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'psc_enqueue_admin_scripts' );

add_action( 'admin_init', 'psc_admin_init' );
add_action( 'admin_menu', 'psc_admin_menu', 100 );
add_action( 'admin_head', 'psc_admin_headers' );

add_filter( 'query_vars', 'psc_query_var' );
add_action( 'parse_query','psc_parse_query' );

//add_action( 'admin_notices', 'psc_admin_notices' );

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
    
    wp_enqueue_script('jquery');
    
    // using jquery-ui
    wp_enqueue_script('jquery-ui-core');
    
//    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui', PSC_PATH . '/css/jquery-ui.css');

    wp_enqueue_style('dropzone', PSC_PATH . '/css/dropzone.css');
    wp_enqueue_script('dropzone', PSC_PATH . '/js/dropzone.js', array('jquery'));
    
    wp_register_script('jquery-ui-datetimepicker', PSC_PATH. '/js/jquery.datetimepicker.js');
    wp_enqueue_script('jquery-ui-datetimepicker', array('jquery'));

    wp_register_style('jquery-ui-datetimepicker', PSC_PATH . '/css/jquery.datetimepicker.css');
    wp_enqueue_style('jquery-ui-datetimepicker');
    
    wp_register_script('fancybox', PSC_PATH. '/js/fancybox.js');
    wp_enqueue_script('fancybox', array('jquery'));
    
    wp_register_style('fancybox', PSC_PATH . '/css/fancybox.css');
    wp_enqueue_style('fancybox');
    
}

function psc_activation_init() {
    global $wpdb;

    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_CATEGORIES . " (id int(11) not null auto_increment, category_name varchar(255), category_desc TEXT, category_type varchar(30), primary key(id), key(category_type))";
    $wpdb->query($ptbl);

    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_VOTES . " (id int(11) not null auto_increment, voter_name varchar(255), voter_email varchar(255), voter_ip varchar(80), vote_date int(11), participant_id int(11), primary key(id), key(participant_id))";
    $wpdb->query($ptbl);
    
    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_PARTICIPANTS . " (id int(11) not null auto_increment, email varchar(128), first_name varchar(80),
									last_name varchar(80), age int(11), sex varchar(1), school int(11), class_name varchar(80), 
									project_name varchar(80), project_category varchar(80), project_description text,
									mail_site int(1), mail_contest int(1), approved int(1) default 0, subscribe_date int(11), 
									primary key (id), key(email))";
    $wpdb->query($ptbl);
    
}

function psc_deactivation_init() {
    // TODO
}

function psc_admin_init() {
    
    $page = isset($_GET['page']) ? $_GET['page'] : false;
    switch ($page) {
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
    add_submenu_page('psc_overview', __('Categories', PSC_PLUGIN), __('Categories', PSC_PLUGIN), 'edit_pages','psc_categories', 'psc_admin_menu_item');
    
}

function psc_admin_menu_item() {

    global $wpdb, $psc_admin_notices;
    $item = isset($_GET['item']) ? intval($_GET['item']) : false;
    $page = isset($_GET['page']) ? $_GET['page'] : false;
    $action = isset($_GET['action']) ? $_GET['action'] : false;
    
    switch ($_GET['page']) {
     case 'psc_participants':
	
	if ($item) {
	    $info = $wpdb->get_row("SELECT email FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $item, ARRAY_A);
	}
	
	switch($action) {

	 case 'approve':
	    $psc_admin_notices['updated'][] = sprintf(__("The participant '%s' has been approved.", PSC_PLUGIN), $info['email']);
	    $wpdb->query("UPDATE " . PSC_TABLE_PARTICIPANTS . " SET approved=1 WHERE id=" . $item);
	    break;
	    
	 case 'unapprove':
	    $psc_admin_notices['error'][] = sprintf(__("The participant '%s' has been rejected.", PSC_PLUGIN), $info['email']);
	    $wpdb->query("UPDATE " . PSC_TABLE_PARTICIPANTS . " SET approved=0 WHERE id=" . $item);
	    break;
	    
	 case 'delete':
	    $psc_admin_notices['error'][] = sprintf(__("The participant '%s' has been deleted.", PSC_PLUGIN), $info['email']);
	    $wpdb->query("DELETE FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $item);
	    $wpdb->query("DELETE FROM " . PSC_TABLE_VOTES . " WHERE participant_id=" . $item);
	    break;
	    
	 case 'edit':
	    $page .= '_edit';
	    break;
	    
	 case 'save':

	    $psc_admin_notices['updated'][] = sprintf(__("The participant '%s' has been updated successfully.", PSC_PLUGIN), $info['email']);
	    
	    $fields = array('first_name' => '%s', 'last_name' => '%s', 'email' => '%s', 'sex' => '%s', 'age' => '%d', 'school' => '%d', 'class_name' => '%s', 
			    'project_name' => '%s', 'project_category' => '%s', 'project_description' => '%s', 
			    'approved' => '%b', 'mail_site' => '%b', 'mail_contest' => '%b', 'subscribe_date' => '%T');
	    
	    $data = array();
	    $format = array();
	    foreach($fields as $field => $fmt) {
		if (isset($_POST[$field])) {
		    $val = $_POST[$field];
		    if ($fmt == '%b' && $val == 'on') { $val = 1; }
		    if ($fmt == '%T') { $val = strtotime($val); }
		    $data[$field] = $val;
		} else {
		    if ($fmt == '%b' && $val != 'on') { $val = 0; }
		    if ($fmt == '%T' && !$val) { $val = time(); }
		    $data[$field] = '';
		}
		
		if ($fmt == '%b') $fmt = '%d';
		if ($fmt == '%T') $fmt = '%d';
		
		$format[] = $fmt;
	    }
	    
	    $wpdb->update(PSC_TABLE_PARTICIPANTS, $data, array('id' => $item), $format, array('%d'));
	    
	    break;
	    
	}
	break;

     case 'psc_categories':
	
	if ($item) {
	    $info = $wpdb->get_row("SELECT category_name FROM " . PSC_TABLE_CATEGORIES . " WHERE id=" . $item, ARRAY_A);
	}
	
	switch($action) {
	    
	 case 'delete':
	    $psc_admin_notices['error'][] = sprintf(__("The category '%s' has been deleted.", PSC_PLUGIN), $info['category_name']);
	    $wpdb->query("DELETE FROM " . PSC_TABLE_CATEGORIES . " WHERE id=" . $item);
	    psc_unregister_string($item);
	    break;
	    
	 case 'edit':
	    $page .= '_edit';
	    break;
	    
	 case 'save':

	    if ($item) {
		$psc_admin_notices['updated'][] = sprintf(__("The category '%s' has been updated successfully.", PSC_PLUGIN), $info['category_name']);
	    } else {
		$psc_admin_notices['updated'][] = sprintf(__("The category '%s' has been added successfully.", PSC_PLUGIN), $_POST['category_name']);
	    }
	    
	    $fields = array('category_name' => '%s', 'category_desc' => '%s', 'category_type' => '%s');
	    
	    $data = array();
	    $format = array();
	    foreach($fields as $field => $fmt) {
		if (isset($_POST[$field])) {
		    $val = $_POST[$field];
		    if ($fmt == '%b' && $val == 'on') { $val = 1; }
		    if ($fmt == '%T') { $val = strtotime($val); }
		    $data[$field] = $val;
		} else {
		    if ($fmt == '%b' && $val != 'on') { $val = 0; }
		    if ($fmt == '%T' && !$val) { $val = time(); }
		    $data[$field] = '';
		}
		
		if ($fmt == '%b') $fmt = '%d';
		if ($fmt == '%T') $fmt = '%d';
		
		$format[] = $fmt;
	    }
	    
	    if ($item) {
		$wpdb->update(PSC_TABLE_CATEGORIES, $data, array('id' => $item), $format, array('%d'));
	    } else {
		$wpdb->insert(PSC_TABLE_CATEGORIES, $data, $format);
	    }
	    
	    psc_register_string($item, $data['category_name'], $data['category_desc']);

	    break;
	    
	    
	}
	break;
	
     case 'psc_votes':

	if ($item) {
	    $info = $wpdb->get_row("SELECT voter_email FROM " . PSC_TABLE_VOTES . " WHERE id=" . $item, ARRAY_A);
	}
	
	switch($action) {
	    
	 case 'delete':
	    $psc_admin_notices['error'][] = sprintf(__("The vote from '%s' has been deleted.", PSC_PLUGIN), $info['voter_email']);
	    $wpdb->query("DELETE FROM " . PSC_TABLE_VOTES . " WHERE id=" . $item);
	    break;
	}
	
	break;
    }

    echo psc_admin_notices(true);
    ob_start();
    include 'views/' . $page . '.php';
    echo ob_get_clean();
    
}

function psc_admin_notices($return = false) {
    global $psc_admin_notices;
    
    
    if( ! empty( $psc_admin_notices ) ){    
	// Remove an empty and then sort
	array_filter( $psc_admin_notices );
	ksort( $psc_admin_notices );
	
	$output = '';
	foreach( $psc_admin_notices as $key => $value ){
	    // Probably an array but best to check
	    if( is_array( $value ) ) {
		foreach( $value as $v ) {
		    $output .= '<div class="' . esc_attr( $key ) . '"><p>' . esc_html( $v ) . '</p></div>';
		}
	    } else {
		$output .= '<div class="' . esc_attr( $key ) . '"><p>' . esc_html( $value ) . '</p></div>';
	    }
	}
	if ($return) return $output;
	echo $output;
    }
}

function psc_admin_headers() {
    $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;

    echo '<style type="text/css">';
    
    switch($page) {

     case 'psc_categories':
	echo '.wp-list-table .column-category_type { width: 10%; }';
	break;
	
     case 'psc_votes':
	echo '.wp-list-table .column-name { width: 30%; }';
	echo '.wp-list-table .column-ip_address { width: 200px; }';
	echo '.wp-list-table .column-vote_date { width: 200px; }';
	break;
	
     case 'psc_participants':
	echo '.wp-list-table .column-image { width: 160px; }';
	echo '.wp-list-table .column-name { width: 15%; }';
	echo '.wp-list-table .column-email { width: 10%; }';
	echo '.wp-list-table .column-votes { width: 5%; text-align: center; }';
	echo '.wp-list-table .column-subscribe_date { width: 130px; }';
	echo '.wp-list-table .column-status { width: 10%; }';
	break;
    }
    
    echo '</style>';
    
}

function psc_format_date($timestamp) {
    return date("Y-m-d", $timestamp);
}

function psc_format_datetime($timestamp, $seconds = false) {
    $format = ($seconds) ? "Y-m-d H:i:s" : "Y-m-d H:i";
    return date($format, $timestamp);
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
	    $options['vote_open_date'] = strtotime($date);
	}
    }
    
    if (isset($_POST['vote_close_date'])) {
	$date = trim($_POST['vote_close_date']);
	
	if (!empty($date)) {
	    $options['vote_close_date'] = strtotime($date);
	}
    }

    if (isset($_POST['bitly_login'])) {
	$options['bitly_login'] = $_POST['bitly_login'];
    }

    if (isset($_POST['bitly_api_key'])) {
	$options['bitly_api_key'] = $_POST['bitly_api_key'];
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


function psc_image($email, $force = false) {

    $thumbW = 207;
    $thumbH = 136;
    
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
    
    if ($force || !file_exists($thumbFile)) {
	$image_thumbs = wp_get_image_editor($image_file);
	if (!is_wp_error($image_thumbs)) {
	    $image_thumbs->resize($thumbW, $thumbH, true);
	    $image_thumbs->save($thumbFile);
	}
    }

    $viewFile = $dest_file . '-view.png';
    if ($force || !file_exists($viewFile)) {
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

function psc_shortcode_participants() {
    ob_start();
    include 'views/participants.php';
    return ob_get_clean();
    
}
			      
function psc_query_var($vars) {
	$vars[] = 'participant';
	return $vars;
}

function psc_parse_query() {
    global $wp_query;

    wp_enqueue_script('jquery');
    wp_register_script('fancybox', PSC_PATH. '/js/fancybox.js');
    wp_enqueue_script('fancybox', array('jquery'));
    
    if(isset($wp_query->query_vars['participant']) && $wp_query->query_vars['participant'] != ''){
	add_filter( 'the_content', 'psc_show_participant' );
    }
}

function psc_show_participant() {
    $id = $_GET['participant'];
    $link =  PSC_PATH . 'ajax.php?action=details&id=' . $id;
?>
<script>
jQuery(document).ready(function() {
    jQuery.fancybox({
        href	    : '<?php echo $link; ?>',
        type        : 'ajax',
	margin	    : [20, 60, 20, 60],
        fitToView   : false,
        width       : '80%',
        height      : '80%',
        autoSize    : false,
        closeClick  : false,
        openEffect  : 'none',
        closeEffect : 'none'
    });
});
</script>
<?php	
}

function psc_get_category($type) {
    global $wpdb;
    $sql = "SELECT id, category_name, category_desc FROM " . PSC_TABLE_CATEGORIES . " WHERE category_type = '" . $type . "'";
    $rows = $wpdb->get_results($sql, ARRAY_A);
    foreach($rows as &$row) {
	$row['category_name'] = psc_name_t($row['id'], $row['category_name']);
	$row['category_desc'] = psc_desc_t($row['id'], $row['category_desc']);
    }
    return $rows;
}

function psc_get_category_by_id($type) {
    $res = array();
    $cats = psc_get_category($type);
    foreach($cats as $cat) {
	$res[$cat['id']] = $cat['category_name'];
    }
    return $res;
}

function psc_is_vote_open() {
 
    $open_date = psc_get_option('vote_open_date');
    $close_date = psc_get_option('vote_close_date');
    
    return ($open_date <= time() && $close_date >= time());
    
}

function psc_register_string($id, $title, $desc = '') {

    
    if (function_exists('icl_register_string') ) {
	$context = 'Contest Category ' . $id;
	icl_register_string( $context, 'Category Name', $title );
	icl_register_string( $context, 'Category Description', $desc );
    }
    
}

function psc_unregister_string($id) {
    if (function_exists( 'icl_unregister_string' ) ) {
	$context = 'Contest Category ' . $id;
	icl_unregister_string( $context, 'Category Name' );
	icl_unregister_string( $context, 'Category Description' );
    }
}

function psc_name_t($id, $title) {
    if (function_exists( 'icl_t' )) {
	$context = 'Contest Category ' . $id;
	$tran = icl_t( $context, 'Category Name', $title );
    } else {
	$tran = false;
    }
    return ($tran) ? $tran : $title;
}

function psc_desc_t($id, $desc) {
    if (function_exists( 'icl_t' )) {
	$context = 'Contest Category ' . $id;
	$tran = icl_t( $context, 'Category Description', $desc );
    } else {
	$tran = false;
    }
    return ($tran) ? $tran : $desc;
}

function psc_shorturl($id) {

    $lurl = site_url('/?participant=' . $id);

    $login = psc_get_option('bitly_login');
    $appkey = psc_get_option('bitly_api_key');
    
    if (!$login) {
	return $lurl;
    }
    
    $url = 'http://api.bit.ly/v3/shorten?login='.$login.'&apiKey='.$appkey.'&uri='.urlencode($lurl).'&format=txt';
    
    $ch = curl_init();
    $timeout = 5;
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
    
}
