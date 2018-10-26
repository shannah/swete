<?php
class actions_swete_capture_strings {


    private function loadUrlsFromFile(SweteSite $site, $path) {
        if (is_readable($path)) {
            $urls = preg_split("/\\r\\n|\\r|\\n/", file_get_contents($path));
            $proxyWriter = $site->getProxyWriter();
            foreach ($urls as $k=>$v) {
                $spacePos = strpos($v, ' ');
                if ($spacePos !== false) {
                    $urls[$k] = substr($v, 0, $spacePos);
                }
                $urls[$k] = $proxyWriter->proxifyUrl($urls[$k]);
            }
            return $urls;
        }
        return false;
    }

    function parseHeaders( $headers ) {
        $head = array();
        foreach( $headers as $k=>$v ) {
            $t = explode( ':', $v, 2 );
            if( isset( $t[1] ) ) {
                $head[ trim($t[0]) ] = trim( $t[1] );
            } else  {
                $head[] = $v;
                if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) ) {
                    $head['response_code'] = intval($out[1]);
                }
            }
        }
        return $head;
    }


    function capture_url(SweteSite $site, $jobId, $url) {
        $opts = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Cookie: --swete-capture=1\r\n",
            'ignore_errors' => true
          )
        );
        $context = stream_context_create($opts);
        $contents = file_get_contents($url, false, $context);
        $h = $this->parseHeaders($http_response_header);
        
        $rec = new Dataface_Record('page_refresh_log', array());
        
        $rec->setValues(array(
            'page_refresh_job_id' => $jobId,
            'website_id' => $site->getRecord()->val('website_id'),
            'page_url' => $url,
            'response_code' => $h['response_code'],
            'response_body' => $contents,
            'refresh_time' => date('Y-m-d H:i:s')
        ));
        
        $res = $rec->save();
        if (PEAR::isError($res)) {
            throw new Exception($res->getMessage());
        }
        
        $iResponseCode = intval($h['response_code']);
        if ($iResponseCode >= 200 && $iResponseCode < 400) {
            df_q("update page_refresh_jobs set num_pages_completed=num_pages_completed+1, num_pages_successful=num_pages_successful+1 , last_modified=NOW()
                where page_refresh_job_id='".intval($jobId)."'");
        } else {
            df_q("update page_refresh_jobs set num_pages_completed=num_pages_completed+1 , last_modified=NOW()
                where page_refresh_job_id='".intval($jobId)."'");
        }
    }

    function handleGetJobProgress(SweteSite $site, $jobId) {
        $rec = df_get_record('page_refresh_jobs', array('job_id' => '='.$jobId));
        header('Content-type: application/json');
        echo json_encode($rec->vals());
    }
    
    function jsonHeaders() {
        header('Content-Type: application/json; charset=UTF-8');
    }

    function handleCreateJob(SweteSite $site) {
        /*
        `page_refresh_job_id` int(11) NOT NULL AUTO_INCREMENT,
	        `start_time` datetime,
	        `end_time` datetime,
	        `num_pages_scheduled` INT(11),
	        `num_pages_completed` INT(11),
	        `num_pages_successful` INT(11)
	    ) ENGINE=InnoDB";
        */
        if (!$site->getRecord()->checkPermission('edit')) {
            $this->jsonHeaders();
            echo json_encode(array('error' => 'You don\'t have permission to make changes to this site.'));
            //die("You don't have permission to make changes to this site.");
            exit;
        }
        $urls = $this->get_urls($site);
        if (!$urls) {
            $this->jsonHeaders();
            echo json_encode(array('error' => 'No whitelist provided'));
            exit;
            //die("No whitelist was provided");
        }
        $rec = new Dataface_Record('page_refresh_jobs', array());
        $rec->setValues(array(
            'website_id' => $site->getRecord()->val('website_id'),
            'start_time' => date('Y-m-d H:i:s'),
            'last_modified' => date('Y-m-d H:i:s'),
            'num_pages_scheduled' => count($urls),
            'num_pages_completed' => 0,
            'num_pages_successful' => 0
            
        ));
        $res = $rec->save();
        
        $this->jsonHeaders();
        if (PEAR::isError($res)) {
            echo json_encode(array('error' => $res->getMessage()));
            exit;
        }
        echo json_encode($rec->strvals());
        
        

    }  
    
    function get_urls(SweteSite $site) {
        $whiteListPath = 'sites/'.basename($site->getRecord()->val('website_id')).'/whitelist.txt';
        $urls = $this->loadUrlsFromFile($site, $whiteListPath);
        return $urls;
    } 
    
    function handleGetCurrentJob(SweteSite $site) {
        $job = df_get_record('page_refresh_jobs', array(
            'website_id' => $site->getRecord()->val('website_id'),
            'cancelled' => '=',
            'complete' => '=',
            '-sort' => 'last_modified desc'
        ));
        
        header('Content-Type: application/json; charset="UTF-8"');
        echo json_encode($job ? $job->strvals() : null);
        
    }

    function handleGet($params=array()) {
        //echo sys_get_temp_dir();exit;
        import('inc/SweteSite.class.php');
        $siteRec = Dataface_Application::getInstance()->getRecord();
        if (!$siteRec) die("No site");
        $siteId = $siteRec->val('website_id');
        if (!$siteId) {
            die("No site ID provided");
        }
        $site = SweteSite::loadSiteById($siteId);
        if (!$site) {
            die("Site not found");
        }
        if (!$site->getRecord()->checkPermission('edit')) {
            die("You don't have permission to perform this function");
        }
        if (@$_GET['--progress']) {
            $jobId = @$_GET['job_id'];
            if (!@$jobId) {
               $this->handleGetCurrentJob($site);
               exit;
            }
           
            $this->handleGetJobProgress($site, $jobId);
            exit;
            
        }
        Dataface_JavascriptTool::getInstance()->import('swete/actions/capture_strings.js');
        df_display(array('websiteID'=>$site->getRecord()->val('website_id')), 'swete/actions/capture_strings.html');
    }
    
    function cancelJob($jobID) {
        df_q("update page_refresh_jobs set cancelled=1 where page_refresh_job_id='".addslashes($jobID)."'");
    }

    function handle($params=array()) {
        
        if (!@$_POST) {
            $this->handleGet($params);
            exit;
        }
    
        import('inc/SweteSite.class.php');
        $delaySecs = 1;
        $siteId = @$_POST['website_id'];
        if (!$siteId) {
            die("No site_id provided");
        }
        $site = SweteSite::loadSiteById($siteId);
        if (!$site) {
            die("Site not found");
        }
        if (!$site->getRecord()->checkPermission('edit')) {
            die("You don't have permission to make changes to this site.");
        }
        
        $jobId = @$_POST['job_id'];
        if (!$jobId) {
            $this->handleCreateJob($site);
            exit;
        }
        
        if (@$_POST['--cancel']) {
            $this->cancelJob($jobId);
            header('Content-type: text/plain');
            echo "OK";
            exit;
        }
        session_write_close();
        
        set_time_limit(0);
        ignore_user_abort(true);
        
        $urls = $this->get_urls($site);
        if (!$urls) {
            die("No whitelist was provided");
        }
        //$filename = '/home/swete/logs/php_errors.log';
        //$handle = fopen($filename, 'r+');
        //ftruncate($handle, 0);
        //rewind($handle);
        //echo fread($handle, filesize($filename));
        fclose($handle);
        $tmp = sys_get_temp_dir();
        @mkdir($tmp.'/swete');
        $logFile = $tmp.'/swete/swete_capture_strings_'.basename($jobId).'.log';
        touch($logFile);
        ini_set('error_log', $logFile);
        foreach ($urls as $url) {
            $job = df_get_record('page_refresh_jobs', array('page_refresh_job_id' => '='.$jobId));
            if ($job->val('cancelled')) {
                header('Content-type:text/plain');
                echo "CANCELLED";
                exit;
            }
            $log = df_get_record('page_refresh_log', array('page_refresh_job_id' => '='.$jobId, 'page_url' => '='.$url));
            if ($log) {
                // Already processed this page for this url.
                continue;
            }
            $this->capture_url($site, $jobId, $url);
            sleep($delaySecs);
        }
        
        $job = df_get_record('page_refresh_jobs', array('page_refresh_job_id' => '='.$jobId));
        $job->setValue('complete', 1);
        $job->save();
        
        header('Content-type: text/plain');
        echo "OK";
        
    }
}