<?php
if (preg_match('#/swete-admin/#', $_SERVER['SCRIPT_NAME']) || preg_match('#/swete-admin$#', $_SERVER['SCRIPT_NAME'])) {
	return false;
}

$_SERVER['REDIRECT_URL'] = $_SERVER['REQUEST_URI'];
$_SERVER['REDIRECT_QUERY_STRING'] = 
	(strpos($_SERVER['REQUEST_URI'], '?') !== false) 
	? substr($_SERVER['REQUEST_URI'], strpos($_SERVER['REQUEST_URI'], '?'))
	: '';
$_SERVER['REQUEST_URI'] = '/swete-admin/index.php?-action=swete_handle_request';

$_GET['-action'] = 'swete_handle_request';
$_REQUEST['-action'] = 'swete_handle_request';
chdir('swete-admin');
include 'index.php';