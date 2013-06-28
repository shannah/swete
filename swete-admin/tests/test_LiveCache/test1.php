<?php
function flushCallback(ProxyClient $client){
	exit;
}

$images = array(
	'php'=>'http://ca2.php.net/images/php.gif',
	'canucks' => 'http://1.cdn.nhle.com/canucks/images/upload/2011/06/jun2511_labate_rr.jpg',
	'zope' => 'http://www.zope.org/logo.png'
);

$pages = array(
	'weblite' => 'http://solutions.weblite.ca/index.html',
	'canucks' => 'http://canucks.nhl.com/',
	'weblitecss' => 'http://solutions.weblite.ca/index.css'
);

$ps = PATH_SEPARATOR;
$ds = DIRECTORY_SEPARATOR;
set_include_path(get_include_path().$ps.dirname(__FILE__).$ds.'..'.$ds.'..');
require_once 'inc/ProxyClient.php';
$url = null;
if ( @$_GET['image'] ){
	$key = $_GET['image'];
	if ( !isset($images[$key]) ) die("Could not find image");
	$url = $images[$key];
} else if ( $_GET['page'] ){
	$key = $_GET['page'];
	if ( !isset($pages[$key]) ) die("Could not find page");
	$url = $pages[$key];
} 

if ( $url ){
	//$url = 'http://ca2.php.net/images/php.gif';
	$client = new ProxyClient;
	$client->URL = $url;
	$client->flushableContentTypeRegex = '#html|css#';
	$client->afterFlushCallback = 'flushCallback';
	$client->process();
	foreach ( $client->headers as $h){
		header($h);
		
	}
	echo $client->content;
} else {

	$html = '<div style="overflow:scroll">';
	foreach ($images as $img=>$imgurl){
		$html .= '<img src="?image='.urlencode($img).'"/>';
	}
	$html .= '</div>';
	foreach ($pages as $pg=>$pgurl){
		$html .= '<iframe src="?page='.urlencode($pg).'" width="300" height="200"/>';
	}
	
	$doc = new DOMDocument;
	@$doc->loadHtml($html);
	echo $doc->saveHtml();
}

