<?php

if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
//    die('Unknown what to do!');
}

define('DOING_AJAX', true);
define('WP_ADMIN', false);

/** Load WordPress Bootstrap */
require_once( dirname(dirname(dirname( dirname( __FILE__ ))) ) . '/wp-load.php' );


/** Allow for cross-domain requests (from the frontend). */
send_origin_headers();

// Require an action parameter
// if (empty($_REQUEST['action'])) die('0');

@header( 'X-Robots-Tag: noindex' );

send_nosniff_header();
nocache_headers();

add_action('send_headers', 'psc_ajax_send_headers');

add_action('wp_ajax_nopriv_register', 'psc_ajax_register');
add_action('wp_ajax_nopriv_details', 'psc_ajax_details');

do_action( 'wp_ajax_nopriv_' . $_REQUEST['action'] ); // Non-admin actions

wp_die();

function psc_ajax_send_headers() {
    header('Content-Type: application/json; charset=' . get_option('blog_charset'));
}

function psc_ajax_details() {

    global $wpdb;
    
    $tbl = $wpdb->prefix . 'psc_participants';

    $sql = "SELECT * FROM " . $tbl . " WHERE id = " . intval($_REQUEST['id']);
    $item = $wpdb->get_row($sql, ARRAY_A);
    
    ob_start();
    include 'views/details.php';
    echo ob_get_clean();
    
}

function psc_ajax_register() {

    $params = array();
    $params['first_name'] = array('desc' => __("First Name"),
				  'required' => true,
				  'type' => 'text',
				  'minlength' => 2);
    
    $params['last_name'] = array('desc' => __("Last Name"),
				 'required' => true,
				 'type' => 'text',
				 'minlength' => 2);

    $params['sex'] = array('desc' => __("Sex"),
			   'required' => true,
			   'type' => 'enum',
			   'params' => array('m', 'f')
			   );
    
    $params['age'] = array('desc' => __("Age"),
			   'required' => true,
			   'type' => 'enum',
			   'params' => range(6, 21)
			   );
    
    $params['school'] = array('desc' => __("School"),
			      'required' => true,
			      'type' => 'text',
			   );

    $params['class_name'] = array('desc' => __("Class Name"),
				  'required' => true,
				  'type' => 'text',
				  );
    
    $params['project_name'] = array('desc' => __("Project Name"),
				  'required' => true,
				  'type' => 'text',
				  );
    
    $params['project_cat'] = array('desc' => __("Project Category"),
				   'required' => true,
				   'type' => 'text',
				   );
    
    $params['project_desc'] = array('desc' => __("Project Description"),
				    'required' => true,
				    'type' => 'text',
				    );
    
    $errors = array();
    
    foreach($params as $param => $arg) {
	$val = $_REQUEST[$param];
	if (isset($arg['required']) && $arg['required'] && empty($val)) {
	    $errors[$param] = sprintf(__("The field '%s' is mandatory"), $arg['desc']);
	    continue;
	}

	if (isset($arg['minlength']) && strlen($val) < $arg['minlength']) {
	    $errors[$param] = sprintf(__("The minimum length of '%s' must be of %d characters"), $arg['desc'], $arg['minlength']);
	    continue;
	}
	
	if ($arg['type'] == 'enum' && !in_array($val, $arg['params'])) {
	    $errors[$param] = sprintf(__("The specified value for '%s' is invalid"), $arg['desc']);
	}
	
    }

    if (!count($errors)) {
	$res = psc_db_register($_REQUEST);
	if (!$res) {
	    $errors['email'] = __("The email is already registered");
	}
    }
    
    $output = array('status' => 'error', 'error' => $errors);

    if (!count($errors)) {
	$output = array('status' => 'ok');
    }
    
    echo json_encode($output);
    wp_die();
    
}

function psc_db_register($data) {

    global $wpdb;
    
    $tbl = $wpdb->prefix . 'psc_participants';
    
    $sql = "SELECT * FROM " . $tbl . " WHERE email = '" . $data['email'] . "'";
    $res = $wpdb->get_results($sql);
    if(!empty($res)) {
	return false;
    } 
    
    $wpdb->query("INSERT INTO " . $tbl . " (email, first_name, last_name, age, sex, project_name, project_category, project_description, mail_site, mail_contest) VALUES ('" . 
		 $data['email'] . "', '" . $data['first_name'] . "', '" . $data['last_name'] . "', '" . $data['age'] . "', '" . $data['sex'] . "', '" .
		 $data['project_name'] . "', '" . $data['project_cat'] . "', '" . $data['project_desc'] . "', '" .
		 ($data['mail_site'] ? 1 : 0) . "', '" . ($data['mail_contest'] ? 1 : 0) . "')");
    
    return true;
    
}
    
