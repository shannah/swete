<?php
class actions_swete_whitelist {
	function handle($params=array()) {
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		header('Content-type: text/plain; charset=utf-8');
		if (!@$query['site_id']) {
			
			exit;
		}
		
		$siteId = basename($query['site_id']);
		$path = SWETE_DATA_ROOT . DIRECTORY_SEPARATOR .'sites' .DIRECTORY_SEPARATOR . $siteId . DIRECTORY_SEPARATOR . 'whitelist.txt';
		if (file_exists($path)) {
			echo file_get_contents($path);
			
		}
		exit;
	}
}