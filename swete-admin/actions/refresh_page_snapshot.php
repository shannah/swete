<?php
/**
 * Custom action.  Expects to receive 2 parameters:
 * 1.  snapshot_id : the ID of the snapshot to update.
 * 2.  page : The page to update within the snapshot.  This should either be the full proxy URL
 *          to the page or a relative path to the page - without leading slash.  Exception, is the 
 *          root page which can contain just a slash.
 */
class actions_refresh_page_snapshot {
    private $snapshot;
    private $status;
    private $statusArr;
    private $page;

    private function getHttpCode($http_response_header)
    {
        if(is_array($http_response_header))
        {
            $parts=explode(' ',$http_response_header[0]);
            if(count($parts)>1) //HTTP/1.0 <code> <text>
                return intval($parts[1]); //Get code
        }
        return 0;
    }

    public function handle($params=array()) {
        try {
            $this->handleImpl();
        } catch (Exception $ex) {
            if ($this->snapshot) {

                if (isset($this->status) and $this->statusArr) {
                    $this->statusArr['statusCode'] = 500;
                    $this->statusArr['statusString'] = "Failed @ ".date('Y-m-d H:i:s e');
                    $this->status[$this->page] = $this->statusArr;
                    $this->snapshot->setValue('pagestatus', json_encode($this->status));
                    $this->snapshot->save();
                }
            }
            error_log("Failed to create snapshot.  ".$ex->getMessage());
            header('Content-type: application/json; charset="UTF-8"');
            echo json_encode(array(
                'code' => 500,
                'message' => 'Failed to refresh page snapshot.',
                'snapshotStatusCode' => 500,
                'snapshotStatusMessage' => 'Failed'
            ));
        }
    }

    private static function addQueryString($url, $qstr) {
        if (strpos($url, '?') === false) {
            return $url . '?' . $qstr;
        } else {
            return $url . '&' . $qstr;
        }
    }
	

    public function handleImpl() {
    
        
    
        import('inc/SweteSite.class.php');
        $page = trim($_POST['page']);

        if (!$page) {
            throw new Exception("No page specified");
        }
        $this->page = $page;
        $snapshotId = $_POST['snapshot_id'];
        if (!$snapshotId) {
            throw new Exception("No snapshot specified");
        }
        $maxSleep = 15;
        while  (!$this->mutex('refresh_page_snapshot'.basename($snapshotId)) ){
			sleep(2);
			$maxSleep -= 2;
			if ($maxSleep < 0) {
			    throw new Exception("Timed out waiting for the mutex.  There must be another client performing an update.  Try again later.");
			}
		}

        $snapshot = df_get_record('snapshots', array('snapshot_id'=>'='.$snapshotId));
        if (!$snapshot) {
            throw new Exception("Snapshot not found");
        }
        $this->snapshot = $snapshot;

        $site = SweteSite::loadSiteById($snapshot->val('website_id'));
        if (!$site) {
            throw new Exception("Site could not be found");
        }
        
        $proxyBase = $site->getProxyUrl();
        if ($proxyBase{strlen($proxyBase)-1} != '/') {
            $proxyBase .= '/';
        }
        $followLocation = false;
        $isBlockPage = false;
        if (strpos($page, '/swete-block?id=') !== false) {
        	$isBlockPage = true;
        	/*
        	$blockId = urldecode(substr($page, strlen('swete-block?id=')));
        	$blockRecord = df_get_record('global_blocks', array(
        		'website_id' => '='. $snapshot->val('website_id'),
        		'block_id' => '='.$blockId
        	));
        	if (!$blockRecord) {
        		throw new Exception("Block with id ".$blockId." not found", 404);
        	}
        	$origPage = $page;
        	$page = $blockRecord->val('page_url');
        	if (strpos($page, $proxyBase) === 0) {
                $page = substr($page, strlen($proxyBase));
            }
            if ($page == '') {
                $page = '/';
            }
        	if (strpos($page, 'http://') === 0 or strpos($page, 'https://')) {
                // We want the page URL to get either a full URL in the proxy site,
                // or just the path.
                throw new Exception("Illegal page URL passed to refresh");
            }
        	$isBlockPage = true;
        	$toAppend = $page;
            if ($toAppend{0} == '/') {
                $toAppend = substr($toAppend, 1);
            }
            $proxyUrl = $proxyBase . $toAppend;
        	$page = $origPage;
        	*/
        	
        } 
        if (strpos($page, $proxyBase) === 0) {
            $page = substr($page, strlen($proxyBase));
        }
        if ($page == '') {
            $page = '/';
        }
        if (strpos($page, 'http://') === 0 or strpos($page, 'https://')) {
            // We want the page URL to get either a full URL in the proxy site,
            // or just the path.
            throw new Exception("Illegal page URL passed to refresh");
        }
    
        if (strpos($page, $proxyBase) === 0) {
            $proxyUrl = $page;

        } else {
            $toAppend = $page;
            if ($toAppend{0} == '/') {
                $toAppend = substr($toAppend, 1);
            }
            $proxyUrl = $proxyBase . $toAppend;
        }
        
        
        
        $this->page = $page;
        $pageId = sha1($page);
        
        
        

        $status = $snapshot->val('pagestatus');
        if ($status) {
            $status = json_decode($status, true);
        } else {
            $status = array();
        }
        $this->status = $status;

        $statusArr = @$status[$page];
        if (!$statusArr) {
            if (@$status[$proxyUrl]) {
                $status[$page] = $status[$proxyUrl];
                unset($status[$proxyUrl]);
                $statusArr = $status[$page];
            } else {
                $statusArr = array(
                    'statusCode' => 0,
                    'statusString' => 'Queued'
                );
            }
        }
        $this->statusArr = $statusArr;

        

        $siteSnapshotsPath = 'snapshots/'.$snapshot->val('website_id');
        if (!file_exists($siteSnapshotsPath)) {
            if (!@mkdir($siteSnapshotsPath)) {
                throw new Exception("Failed to create directory ".$siteSnapshotsPath);
            }
        }
        $snapshotPath = $siteSnapshotsPath.'/'.$snapshotId;
        if (!file_exists($snapshotPath)) {
            if (!@mkdir($snapshotPath)) {
                throw new Exception("Failed to create directory ".$snapshotPath);
            }
        }

        $path = $snapshotPath.'/'.$pageId;
       
        $opts = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Cookie: --swete-static=false\r\n",
            'follow_location' => $followLocation
          )
        );
        $context = stream_context_create($opts);
        $contents = file_get_contents($proxyUrl, false, $context);
        
        $responseCode = $this->getHttpCode($http_response_header);
        
        //if (!$contents) {
        //    throw new Exception("Failed to get contents of $proxyUrl");
        //}
        if ($responseCode < 300 || $responseCode >= 400) {
            // We don't bother keeping pages that are just 
            // redirects
            if (!$isBlockPage) {
            	$dom = SweteTools::loadHtml($contents);
				if ($dom) {
					// Look for blocks in this page.
					$xpath = new DOMXPath($dom);
					$blocks = $xpath->query('//swete-block[@id]');
					$pagelist = $snapshot->val('pagelist');
					$blocksAdded = false;
					import('xf/db/Database.php');
					$db = new xf\db\Database(df_db());
					foreach ($blocks as $block) {
						$bid = $block->getAttribute('id');
						$blockUrl = 'swete-block?id='.urlencode($bid);
						if (!preg_match('/^'.preg_quote($blockUrl, '/').'$/m', $pagelist)) {
							$pagelist = trim($pagelist) . "\n" . $blockUrl;
							$blocksAdded = true;
						}
						// Update the page URL for the block
						$expandedBlockUrl = self::addQueryString($proxyUrl, 'swete:block='.urlencode($bid));
						$db->query('replace into global_blocks (website_id, block_id, page_url) values (:website_id, :block_id, :page_url)', array(
							'website_id' => $snapshot->val('website_id'),
							'block_id' => $bid,
							'page_url' => $expandedBlockUrl
						));
						
					}
					$snapshot->setValue('pagelist', $pagelist);
					$snapshot->save();
				
				}
            }
			
            if (!file_put_contents($path, $contents, LOCK_EX)) {
                throw new Exception("Failed to write contents from $proxyUrl to disk");
            }
            
        }

        
        if (isset($this->status) and $this->statusArr) {
            $wpStatus = df_get_record('webpage_status', array(
                    'website_id' => '='.$snapshot->val('website_id'),
                    'page_url' => '='.$proxyUrl
            ));
            $this->statusArr['statusCode'] = $responseCode;
            $this->statusArr['statusString'] = "Complete @ ".date('Y-m-d H:i:s e');
            if ($wpStatus) {
                $this->statusArr['translations_checksum'] = $wpStatus->val('translations_checksum');
            }
            
            $this->statusArr['timestamp'] = time();
            $this->status[$this->page] = $this->statusArr;
            //echo $this->page." ";
            //print_r($this->statusArr);exit;
            // Reload the snapshot to try to avoid race conditions
            //$this->snapshot = df_get_record('snapshots', array('snapshot_id'=>'='.$snapshotId));
            
            

            if ($wpStatus) {
                $pageSnapshot = df_get_record('webpage_snapshots', array(
                        'website_id' => '='.$snapshotId,
                        'page_url' => '='.$proxyUrl
                ));
                if (!$pageSnapshot) {
                    $pageSnapshot = new Dataface_Record('webpage_snapshots', array());
                    $pageSnapshot->setValues(array(
                        'snapshot_id' => $snapshotId,
                        'page_url' => $proxyUrl
                    ));
                }
            
                $pageSnapshot->setValue('translations_checksum', $wpStatus->val('translations_checksum'));
                $pageSnapshot->setValue('translations', $wpStatus->val('translations'));
                $pageSnapshot->save();
            }
            $this->snapshot->setValue('pagestatus', json_encode($this->status));
            //echo "Saving status";
            $res = $this->snapshot->save();
            if (PEAR::isError($res)) {
                throw new Exception("Failed to save:".$res->getMessage());
            }
        }


        header('Content-type: application/json; charset="UTF-8"');
        echo json_encode(array(
          'code' => 200,
          'message' => 'Successfully created snapshot',
          'snapshotStatusCode' => 200,
          'snapshotStatusMessage' => 'Complete'
        ));

    }
    
    static function getMutexType(){
		$app = Dataface_Application::getInstance();
		$mutexType = 'mkdir';
		
		if ( @$app->_conf['mutex_type']){
			$mutexType = $conf['mutex_type'];
		}
		return $mutexType;
	}
    
    
    private $mutex;
    function mutex($name){

		$dir = is_writable(sys_get_temp_dir()) ? sys_get_temp_dir() : 'templates_c';
		$this->mutex = $dir.'/'.basename($name).'.mutex';
		error_log("Acquiring mutex" . $this->mutex);
		$mt = self::getMutexType();
		if ( $mt === 'flock' ){
			$this->mutex = fopen($path, 'w');
			if ( flock($this->mutex, LOCK_EX | LOCK_NB) ){
				register_shutdown_function(array($this,'clear_mutex'));
				return true;
			} else {
				error_log("Failed to acquire mutex");
				return false;
			}
		} else {
			if ( @mkdir($this->mutex, 0777) ){
				register_shutdown_function(array($this,'clear_mutex'));
				return true;
			} else {
				if (!file_exists($this->mutex)) {
					// If we failed to create the mutex, but the file
					// actually doesn't exist, then maybe we just don't have
					// permission to create that directory.

				}
				error_log("Failed to acquire mutex using mkdir");
				return false;
			}
		}

	}
	
	/**
	 * Clears the most recently acquired mutex.
	 */
	function clear_mutex(){

		if ( $this->mutex ){
			if ( self::getMutexType() == 'flock' ){
				fclose($this->mutex);
			} else {
				@rmdir($this->mutex);
			}
			$this->mutex = null;
		}
	}
	
}
