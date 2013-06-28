<?php
/**
 * SWeTE Server: Simple Website Translation Engine
 * Copyright (C) 2012  Web Lite Translation Corp.
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
class actions_swete_approve_pages {

	private $sites = array();

	private function getSite($id){
		if ( !isset($this->sites[$id]) ){
			$this->sites[$id] = SweteSite::loadSiteById($id);
		}
		return $this->sites[$id];
	
	}

	function handle($params){
		@session_write_close();
		header('Connection:close');
		import('inc/SweteWebpage.class.php');
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		$records = df_get_selected_records($query);
		$errors = array();
		$failed = 0;
		$success = 0;
		if ( $records ){
			foreach($records as $rec){
				if ( $rec->table()->tablename != 'webpages' ){
					continue;
				}
				if ( !$rec->checkPermission('swete:approve page') ){
					$errors[] = $rec->getTitle().' could not be approved because you don\'t have permission.';
					$failed++;
					continue;
				}
				
				$site = $this->getSite($rec->val('website_id'));
				
				
				$username = '';
				$user = SweteTools::getUser();
				if ( $user ) $username = $user->val('username');
				
				$wp = new SweteWebpage($rec);
				$twp = $wp->getTranslation($site->getDestinationLanguage());
				
				try {
					$res = $twp->setStatus(SweteWebpage::STATUS_APPROVED, $username, @$_POST['--comments']);
					$success++;
				} catch (Exception $ex){
					error_log($ex->getMessage());
					$errors[] = 'Failed to approve page '.$rec->getTitle().'.  See error log for details.';
					$failed++;
				}
				
			}
		}
		
		header('Content-type: text/json; charset="'.$app->_conf['oe'].'"');
		echo json_encode(array(
			'code' => 200,
			'failed' => $failed,
			'success' => $success,
			'errors' => $errors
		));
		
		
	}
}