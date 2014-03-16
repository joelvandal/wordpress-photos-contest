<?php

define('WP_ADMIN', false);

/** Load WordPress Bootstrap */
require_once( dirname(dirname(dirname( dirname( __FILE__ ))) ) . '/wp-load.php' );

$allowedExts = array("jpg", "jpeg", "gif", "png");
$allowedTypes = array("image/gif", "image/jpeg", "image/png", "image/pjpeg");
$maxSize = 3 * 1024 * 1024;
  
if (!isset($_REQUEST['participant'])) {
    exit;
}

$participant = md5($_REQUEST['participant']);
$targetPath = dirname( __FILE__ ) . '/uploads/';

$files = glob($targetPath . $participant . '*');
foreach($files as $file) unlink($file);

if (!empty($_FILES)) {
    
    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $extension = strtolower($extension);
    
    if (in_array($_FILES["file"]["type"], $allowedTypes) && ($_FILES["file"]["size"] < $maxSize) && in_array($extension, $allowedExts)) {
	$tempFile = $_FILES['file']['tmp_name'];
	$destFile = $participant . '.' . $extension;
	
	$targetFile =  $targetPath . $destFile;
	
	move_uploaded_file($tempFile,$targetFile);

	psc_image($_REQUEST['participant'], true);
	
	echo json_encode(array('status' => $destFile));
	exit;
	
    }
    
}

echo json_encode(array('status' => 'error'));
