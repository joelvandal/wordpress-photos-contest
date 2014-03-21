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
 * Version:           	1.0.3
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

define('PSC_ABS_IMAGE', dirname(__FILE__) . '/../../uploads/contest/');
define('PSC_IMAGE', plugin_dir_url(__FILE__) . '../../uploads/contest/');
define('PSC_TABLE_VOTES', $wpdb->prefix . 'psc_votes');
define('PSC_TABLE_PARTICIPANTS', $wpdb->prefix . 'psc_participants');
define('PSC_TABLE_CATEGORIES', $wpdb->prefix . 'psc_categories');

define('PSC_VOTE_COOKIE', 'PSC_Contest_Vote');

$psc_category_types = array('school'     => __psc('School'),
			    'class_name' => __psc('Class Name'),
			    'project'    => __psc('Project'));

require PSC_ABSPATH . 'lib/Tables.php';

$image_path = PSC_ABS_IMAGE;
if (!file_exists($image_path)) {
    mkdir($image_path);
}


psc_load_options();

if (defined('WP_DEBUG') && WP_DEBUG) {
    psc_activation_init();
}

add_shortcode( 'contest_register', 'psc_shortcode_register' );
add_shortcode( 'contest_participants', 'psc_shortcode_participants' );

add_shortcode( 'remove_postedby', 'psc_shortcode_remove_postedby' );

// add_shortcode( 'contest_register', 'psc_shortcode_register' );

register_activation_hook( __FILE__, 'psc_activation_init' );
// register_deactivation_hook( __FILE__, 'psc_deactivate_init' );

add_action( 'wp_enqueue_scripts', 'psc_enqueue_scripts' );
add_action( 'admin_enqueue_scripts', 'psc_enqueue_admin_scripts' );

add_action( 'admin_init', 'psc_admin_init' );
add_action( 'admin_head', 'psc_admin_headers' );

add_action( 'admin_menu', 'psc_admin_menu' );

add_filter( 'query_vars', 'psc_query_var' );
add_action( 'parse_query','psc_parse_query' );

// add_action( 'admin_notices', 'psc_admin_notices' );

global $this_file;
$this_file = __FILE__;
$update_check = "http://joel.vandal.ca/plugins/wp-contest.chk";
require_once('lib/Update.php');

function psc_enqueue_scripts() {

    wp_enqueue_script('jquery');
    
    wp_enqueue_style('dropzone', PSC_PATH . '/css/dropzone.css');
    wp_enqueue_script('dropzone', PSC_PATH . '/js/dropzone.js', array('jquery'));

    wp_register_style('bootstrap', PSC_PATH . '/css/bootstrap.prefixed.css');
    wp_enqueue_style('bootstrap');
    
    wp_register_script('bootstrap', PSC_PATH . '/js/bootstrap.prefixed.js');
    wp_enqueue_script('bootstrap');

    wp_register_style('contest', PSC_PATH . '/css/contest.css');
    wp_enqueue_style('contest');

    wp_register_script('contest', PSC_PATH . '/js/contest.js');
    wp_enqueue_script('contest');
    
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

    wp_register_style('bootstrap', PSC_PATH . '/css/bootstrap.prefixed.css');
    wp_enqueue_style('bootstrap');
    
    wp_register_script('bootstrap', PSC_PATH . '/js/bootstrap.prefixed.js');
    wp_enqueue_script('bootstrap');
    
}

function psc_activation_init() {
    global $wpdb;

    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_CATEGORIES . " (id int(11) not null auto_increment, category_name varchar(255), category_desc TEXT, category_type varchar(30), primary key(id), key(category_type))";
    $wpdb->query($ptbl);

    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_VOTES . " (id int(11) not null auto_increment, voter_name varchar(255), voter_email varchar(255), voter_ip varchar(80), vote_date int(11), 
								 participant_id int(11), vvote_code varchar(32), approved int(1) default 0, 
								 primary key(id), key(participant_id))";
    $wpdb->query($ptbl);
    
    $ptbl = "CREATE TABLE IF NOT EXISTS " . PSC_TABLE_PARTICIPANTS . " (id int(11) not null auto_increment, email varchar(128), first_name varchar(80),
									last_name varchar(80), age int(11), sex varchar(1), school int(11), class_name varchar(80), 
									project_name varchar(80), project_category varchar(80), project_description text,
									mail_site int(1), mail_contest int(1), approved int(1) default 0, subscribe_date int(11),
									artist varchar(30), artist_show int(1) default 0,
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

function psc_add_options() {

    /*
    $option = 'per_page';
    $args = array(
		  'label' => 'Items',
		  'default' => 10,
		  'option' => 'items_per_page'
		  );
    add_screen_option( $option, $args );
    */
    
    $page = isset($_GET['page']) ? $_GET['page'] : false;
    $action = isset($_GET['action']) ? $_GET['action'] : false;

    if (in_array($action, array('edit'))) return false;
    
    switch($page) {
     case 'psc_participants':
	$my_table = new PSC_Participants_Table();
	break;
	
     case 'psc_votes':
	$my_table = new PSC_Votes_Table();
	break;
	
     case 'psc_categories':
	$my_table = new PSC_Categories_Table();
	break;
    }
    /*
    $screen = get_current_screen();
    $screen->add_help_tab( array( 
				  'id' => $page,            //unique id for the tab
				  'title' => 'xx',      //unique visible title for the tab
				  'content' => 'xxx',  //actual help text
				  ) );
    */
}

function psc_admin_menu() {
    
    global $wpdb;
    
    add_menu_page(__psc('Photos Contest'), __psc('Photos Contest'), 'edit_pages', 'psc_overview', 'psc_admin_menu_item', 'dashicons-format-gallery', 2);
    
    add_submenu_page('psc_overview', __psc('Overview'), __psc('Overview'), 'edit_pages', 'psc_overview', 'psc_admin_menu_item');
    
    $cnt = $wpdb->get_row("SELECT count(*) as total FROM " . PSC_TABLE_PARTICIPANTS . " WHERE approved=0");
    
    $name = __psc('Participants');
    if ($cnt->total) {
	$name .= sprintf(' <span class="update-plugins" title="%s"><span class="update-count">%d</span></span>', __psc("Unapproved"), $cnt->total);
    }
    
    $hook = add_submenu_page('psc_overview', __psc('Participants'), $name, 'edit_pages', 'psc_participants', 'psc_admin_menu_item');
    add_action( "load-$hook", 'psc_add_options' );
    
    $hook = add_submenu_page('psc_overview', __psc('Votes'), __psc('Votes'), 'edit_pages', 'psc_votes', 'psc_admin_menu_item');
    add_action( "load-$hook", 'psc_add_options' );
    
    $hook = add_submenu_page('psc_overview', __psc('Categories'), __psc('Categories'), 'edit_pages','psc_categories', 'psc_admin_menu_item');
    add_action( "load-$hook", 'psc_add_options' );
    
    add_submenu_page('psc_overview', __psc('Configuration'), __psc('Configuration'), 'edit_pages','psc_settings', 'psc_admin_menu_item');
    
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
	    $psc_admin_notices['updated'][] = sprintf(__psc("The participant '%s' has been approved."), $info['email']);
	    $wpdb->query("UPDATE " . PSC_TABLE_PARTICIPANTS . " SET approved=1 WHERE id=" . $item);
	    break;
	    
	 case 'unapprove':
	    $psc_admin_notices['error'][] = sprintf(__psc("The participant '%s' has been rejected."), $info['email']);
	    $wpdb->query("UPDATE " . PSC_TABLE_PARTICIPANTS . " SET approved=0 WHERE id=" . $item);
	    break;
	    
	 case 'delete':
	    $psc_admin_notices['error'][] = sprintf(__psc("The participant '%s' has been deleted."), $info['email']);
	    $wpdb->query("DELETE FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $item);
	    $wpdb->query("DELETE FROM " . PSC_TABLE_VOTES . " WHERE participant_id=" . $item);
	    break;
	    
	 case 'edit':
	    $page .= '_edit';
	    break;
	    
	 case 'save':

	    $psc_admin_notices['updated'][] = sprintf(__psc("The participant '%s' has been updated successfully."), $info['email']);
	    
	    $fields = array('first_name' => '%s', 'last_name' => '%s', 'artist' => '%s', 'artist_show' => '%b', 'email' => '%s', 'sex' => '%s', 'age' => '%d', 'school' => '%d', 'class_name' => '%s', 
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
	    $psc_admin_notices['error'][] = sprintf(__psc("The category '%s' has been deleted."), $info['category_name']);
	    $wpdb->query("DELETE FROM " . PSC_TABLE_CATEGORIES . " WHERE id=" . $item);
	    psc_unregister_string($item);
	    break;
	    
	 case 'edit':
	    $page .= '_edit';
	    break;
	    
	 case 'save':

	    if ($item) {
		$psc_admin_notices['updated'][] = sprintf(__psc("The category '%s' has been updated successfully."), $info['category_name']);
	    } else {
		$psc_admin_notices['updated'][] = sprintf(__psc("The category '%s' has been added successfully."), $_POST['category_name']);
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


	 case 'approve':
	    $psc_admin_notices['updated'][] = sprintf(__psc("The vote from '%s' has been approved."), $info['voter_email']);
	    $wpdb->query("UPDATE " . PSC_TABLE_VOTES . " SET approved=1 WHERE id=" . $item);
	    break;
	    
	 case 'unapprove':
	    $psc_admin_notices['error'][] = sprintf(__psc("The vote from '%s' has been rejected."), $info['voter_email']);
	    $wpdb->query("UPDATE " . PSC_TABLE_VOTES . " SET approved=0 WHERE id=" . $item);
	    break;
	    
	 case 'delete':
	    $psc_admin_notices['error'][] = sprintf(__psc("The vote from '%s' has been deleted."), $info['voter_email']);
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
	echo '.wp-list-table .column-ip_address { width: 200px; }';
	echo '.wp-list-table .column-vote_date { width: 200px; }';
	echo '.wp-list-table .column-status { width: 10%; }';
	break;
	
     case 'psc_participants':
	echo '.wp-list-table .column-image { width: 160px; }';
	echo '.wp-list-table .column-name { width: 15%; }';
	echo '.wp-list-table .column-email { width: 10%; }';
	
	echo '.wp-list-table .column-project_description { width: 225px; }';
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
	
	if (isset($_REQUEST['page']) && $_REQUEST['page'] == 'psc_settings') {
	    return $psc_options[$key];
	} else {
	    return psc_setting_t($key, $psc_options[$key]);
	}
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

    $params = array('bitly_login', 'bitly_api_key', 'google_api_key', 'facebook_client_id', 'facebook_secret_key',
		    'twitter_text', 'twitter_hash', 'vote_subject', 'vote_message', 'register_subject', 'register_message');
    
    foreach($params as $param) {
	$val = isset($_POST[$param]) ? $_POST[$param] : '';
	if (isset($_POST[$param])) $options[$param] = $val;
	psc_register_setting($param, $val);
    }
    
    update_option(PSC_PLUGIN, $options);

    wp_redirect(admin_url('admin.php?page=psc_settings'));
    
}


function psc_image($email, $force = false) {

    $thumbW = 207;
    $thumbH = 136;
    
    $viewW = 1920;
    $viewH = 1440;

    
    $image_file = PSC_ABS_IMAGE . md5($email) . '.jpg';
    $dest_file = PSC_ABS_IMAGE . md5($email);
    
    if (!file_exists($image_file)) {
	$image_file = PSC_ABS_IMAGE . md5($email) . '.png';
    }
    
    if (!file_exists($image_file)) {
	$image_file = PSC_ABS_IMAGE . md5($email) . '.gif';
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

function psc_shortcode_remove_postedby() {
    ob_start();
    echo '<style>';
    echo '.post-meta { display:none; }';
    echo '</style>';
    return ob_get_clean();
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
	$vars[] = 'vote_confirm';
	return $vars;
}

function psc_parse_query() {
    global $wp_query;

    if(isset($wp_query->query_vars['participant']) && $wp_query->query_vars['participant'] != ''){
	add_filter( 'jetpack_open_graph_tags', 'psc_open_graph' );
	if (wp_is_mobile()) {
	    add_filter( 'the_title', 'psc_show_participant_title');
	}
	add_filter( 'the_content', 'psc_show_participant' );
//	add_filter( 'single_template', 'psc_show_participant_body');
	
    }
    
    if(isset($wp_query->query_vars['vote_confirm']) && $wp_query->query_vars['vote_confirm'] != ''){
	add_filter( 'the_content', 'psc_confirm_vote' );
    }
}

function psc_open_graph( $tags ) {

    global $wpdb;
    
    $id = $_GET['participant'];
    $info = $wpdb->get_row("SELECT * FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id=" . $id, ARRAY_A);

    unset( $tags['og:image'] );
    $tags['og:url'] = psc_longurl($id);
    $tags['og:title'] = esc_attr( $info['project_name'] );
    $tags['og:image'][0] = esc_url( psc_get_image($info['email']));
    $tags['og:description'] = esc_attr( $info['project_description'] );
    $tags['og:type'] = 'website';
    
    return $tags;
}

function psc_confirm_vote() {
    $code = $_GET['vote_confirm'];
    $link =  PSC_PATH . 'ajax.php?action=confirm_vote&code=' . $code;

?>
<script>
jQuery(document).ready(function(e) {

	jQuery('#indicator').show();
	jQuery.ajax({
		url : '<?php echo $link; ?>',
		type: "GET",
		success: function(response) {
			jQuery('<div class="tb-modal tb-modal-wide tb-fade"></div>').html(response).modal(); //.evalScripts();
		}
	});
});


</script>
<?php	
    remove_filter( current_filter(), __FUNCTION__ );
    
}

function psc_show_participant_title() {

    global $wpdb;
    $id = $_GET['participant'];
    
    $sql = "SELECT * FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id = " . intval($id);
    $item = $wpdb->get_row($sql, ARRAY_A);

    if ($item['artist_show'] && !empty($item['artist'])) {
	$item['full_name'] = $item['artist'];
    } else {
	$item['full_name'] = ucwords(strtolower(sprintf("%s %s", $item['first_name'], $item['last_name'])));
    }

    return sprintf(__psc('%s created by %s'), $item['project_name'], $item['full_name']);
}

function psc_show_participant() {
    
//    remove_filter( current_filter(), __FUNCTION__ );
    $id = $_GET['participant'];
    $link =  PSC_PATH . 'ajax.php?action=details&id=' . $id;

    
//    remove_filter('wp_head');
    if (wp_is_mobile()) {
	global $wpdb;
	get_header();

	$sql = "SELECT * FROM " . PSC_TABLE_PARTICIPANTS . " WHERE id = " . intval($id);
	$item = $wpdb->get_row($sql, ARRAY_A);
	
	ob_start();    
	include 'views/details.php';
	
	echo ob_get_clean();
	get_footer();
	exit;
	
    }
    
?>

<script>
jQuery(document).ready(function(e) {

//	jQuery('#indicator').show();
	jQuery.ajax({
		url : '<?php echo $link; ?>',
		type: "GET",
		success: function(response) {
			jQuery('<div class="tb-modal tb-modal-wide tb-fade"></div>').html(response).modal(); //.evalScripts();
		}
	});
});

</script>
<?php
    remove_filter( current_filter(), __FUNCTION__ );
    
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

function psc_get_school($id) {
    $cats = psc_get_category_by_id('school');
    return $cats[$id];
}

function psc_get_class($id) {
    $cats = psc_get_category_by_id('class_name');
    return $cats[$id];
}

function psc_get_project($id) {
    $cats = psc_get_category_by_id('project');
    return $cats[$id];
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
	
function psc_register_setting($id, $title, $desc = '') {
	    
    if (function_exists('icl_register_string') ) {
	$context = 'Contest Setting ' . $id;
	icl_register_string( $context, 'Category Option', $title );
    }
    
}

function psc_setting_t($id, $desc) {
    if (function_exists( 'icl_t' )) {
	$context = 'Contest Setting ' . $id;
	$tran = icl_t( $context, 'Category Option', $desc );
    } else {
	$tran = false;
    }
    return ($tran) ? $tran : $desc;
}

function esc_html_e_psc($str) {
    echo esc_html(psc_t($str));
}

function _e_psc($str) {
    echo psc_t($str);
}

function __psc($str) {
    return psc_t($str);
}

function psc_t($str) {
    if (function_exists( 'icl_t' )) {
	$context = 'Contest String ';
	$tag = md5($str);
	$res = icl_st_is_registered_string($context, $tag);
	if (!$res) {
	    icl_register_string( $context,  $tag, $str );
	}
	$tran = icl_t( $context, $tag, $str );
    } else {
	$tran = false;
    }
    return ($tran) ? $tran : $str;
}

function psc_get_image($email) {
    return PSC_IMAGE . md5($email) . '-thumb.png';
}

function psc_longurl($id) {
    $lurl = site_url('/?participant=' . $id);
    return $lurl;
}

function psc_shorturl($id) {

    $lurl = psc_longurl($id);

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
    return trim($data);
    
}

function psc_trim($input, $length, $ellipses = true, $strip_html = true) {
    //strip tags, if desired
    if ($strip_html) {
	$input = strip_tags($input);
    }
    
    //strip leading and trailing whitespace
    $input = trim($input);
    
    //no need to trim, already shorter than trim length
    if (strlen($input) <= $length) {
	return $input;
    }
    
    //leave space for the ellipses (...)
    if ($ellipses) {
	$length -= 3;
    }
    
    //this would be dumb, but I've seen dumber
    if ($length <= 0) {
	return '';
    }
    
    //find last space within length
    //(add 1 to length to allow space after last character - it may be your lucky day)
    $last_space = strrpos(substr($input, 0, $length + 1), ' ');
    if ($last_space === false) {
	//lame, no spaces - fallback to pure substring
	$trimmed_text = substr($input, 0, $length);
    }
    else {
	//found last space, trim to it
	$trimmed_text = substr($input, 0, $last_space);
    }
    
    //add ellipses (...)
    if ($ellipses) {
	$trimmed_text .= '...';
    }
    
    return $trimmed_text;
}

function psc_get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';
    
    return $ipaddress;
}

function psc_add_vote($id, $name, $email) {
    global $wpdb;

    $ipaddr = psc_get_client_ip();
    
    $data = array('voter_email' => $email,
		  'voter_name' => $name,
		  'voter_ip' => $ipaddr,
		  'vote_date' => time(),
		  'participant_id' => $id);

    $format = array('%s', '%s', '%s', '%d', '%d');
    $wpdb->insert(PSC_TABLE_VOTES, $data, $format);
}

function psc_count_vote($id) {
    global $wpdb;
    $res = $wpdb->get_row("SELECT count(*) as total FROM " . PSC_TABLE_VOTES . " WHERE approved = 1 AND  participant_id = " . intval($id));
    return $res->total;
}

function psc_is_vote_open() {
 
    $open_date = psc_get_option('vote_open_date');
    $close_date = psc_get_option('vote_close_date');
    
    return ($open_date <= time() && $close_date >= time());
    
}

function psc_get_vote_email() {
    
    if (!isset($_COOKIE[PSC_VOTE_COOKIE])) return null;
    
    $c = explode('#', $_COOKIE[PSC_VOTE_COOKIE]);
    
    if (!isset($c[1]) || empty($c[1])) return null;
    
    $h2 = hash_hmac('SHA256', $c[0], AUTH_KEY);
    if ($h2 == $c[1]) return $c[0];
    
    return null;
}

function psc_set_vote_email($email = null) {
    
    if ($email == null) {
	setcookie(PSC_VOTE_COOKIE, null, -1, '/');
    } else {
	$cookie_voter = $email . '#' . hash_hmac('SHA256', $email, AUTH_KEY);
	setcookie(PSC_VOTE_COOKIE, $cookie_voter, 0, '/');
    }
}

function psc_get_vote_status($email, $id) {
    global $wpdb;
    
    $res = $wpdb->get_row("SELECT vote_date,participant_id FROM " . PSC_TABLE_VOTES . " WHERE voter_email = '" . esc_sql($email) . "'");
    if (isset($res->participant_id)) {
	return true;
    }
    return false;
}


function psc_generate_signature($data) {
    return md5(hash('SHA256', AUTH_KEY . $data));
}

function psc_verify_signature($data, $signature) {
    $generated_signature = psc_generate_signature($data);
    if ($generated_signature == $signature) {
	return true;
    }
    return false;
}

function psc_parse_email($text, $vars) {
    foreach($vars as $var => $val) {
	$str = '[' . $var . ']';
	$text = str_replace($str, $val, $text);
    }
    return $text;
}

function psc_email_register($email) {
    
    if (!is_email($email)) {
	return false;
    }

    add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
    
    $admin_email = get_bloginfo('admin_email');
    $blog_name = get_bloginfo('name');
    $blog_url = get_bloginfo('url');
    $headers = 'From: ' . $blog_name . ' <' . $admin_email . '>' . "\r\n";

    $vars = array();
    $vars['blog_name'] = $blog_name;
    $vars['blog_url'] = $blog_url;
    
    $subject = psc_parse_email(psc_get_option('register_subject'), $vars);
    $message = psc_parse_email(psc_get_option('register_message'), $vars);
    
    wp_mail($email, $subject, $message, $headers);
    
}

function psc_email_vote($email) {
    
    global $wpdb;
    
    if (!is_email($email)) {
	return false;
    }
    
    $pincode = rand(1, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
    $signature = psc_generate_signature($email . '#' . $pincode);
    
    $wpdb->update(PSC_TABLE_VOTES, array('vote_code' => $signature, 'approved' => 0), array('voter_email' => $email), array('%s', '%d'), array('%s'));
    
    add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));
    
    $admin_email = get_bloginfo('admin_email');
    $blog_name = get_bloginfo('name');
    $blog_url = get_bloginfo('url');
    $headers = 'From: ' . $blog_name . ' <' . $admin_email . '>' . "\r\n";

    $vars = array();
    $vars['blog_name'] = $blog_name;
    $vars['blog_url'] = $blog_url;
    $vars['vote_link'] = site_url("?vote_confirm=$signature");
    
    $subject = psc_parse_email(psc_get_option('vote_subject'), $vars);
    $message = psc_parse_email(psc_get_option('vote_message'), $vars);
    
    wp_mail($email, $subject, $message, $headers);
    
}
