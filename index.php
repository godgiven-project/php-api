<?php
	/* set REST API headers for all responses */
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	header("Access-Control-Allow-Methods: *");
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	header("Allow: *");
	/* includes important files and libs */
	global $f3;
	$f3=require('lib/base.php');
	require_once('class/master_db.php');
	require_once('config/ggn_config.php');
	require_once('ggn_permission.php');
	require_once('ggn_load.php');
	require_once('ggn_cloud.php');
	require_once('ggn_routes.php');
	/*  run fatfree */
	$f3->set('ONERROR',
		function($f3) {
			// custom error handler code goes here
			// use this if you want to display errors in a
			// format consistent with your site's theme
			$result['data']		    = $f3->get('ERROR.text');
			$result['error']	    = $f3->get('ERROR.code');
			$result['status']	    = $f3->get('ERROR.status');
			$result['validate']     = 'false';
			die(json_encode($result));
		}
	);
	$f3->run();
?>