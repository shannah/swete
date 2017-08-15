<?php
class actions_refresh_page_snapshot {
    private $snapshot;
    private $status;
    private $statusArr;
    private $page;

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


        $snapshot = df_get_record('snapshots', array('snapshot_id'=>'='.$snapshotId));
        if (!$snapshot) {
            throw new Exception("Snapshot not found");
        }
        $this->snapshot = $snapshot;

        $site = SweteSite::loadSiteById($snapshot->val('website_id'));
        if (!$site) {
            throw new Exception("Site could not be found");
        }

        $status = $snapshot->val('pagestatus');
        if ($status) {
            $status = json_decode($status, true);
        } else {
            $status = array();
        }
        $this->status = $status;

        $statusArr = @$status[$page];
        if (!$statusArr) {
            $statusArr = array(
                'statusCode' => 0,
                'statusString' => 'Queued'
            );
        }
        $this->statusArr = $statusArr;

        $pageId = sha1($page);

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
        $proxyBase = $site->getProxyUrl();
        if ($proxyBase{strlen($proxyBase)-1} != '/') {
            $proxyBase .= '/';
        }
        if (strpos($page, $proxyBase) === 0) {
            $proxyUrl = $page;

        } else {
            if ($path{0} == '/') {
                $path = substr($path, 1);
            }
            $proxyUrl = $proxyBase . $page;
        }

        $opts = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Cookie: --swete-static=false\r\n"
          )
        );
        $context = stream_context_create($opts);
        $contents = file_get_contents($proxyUrl, false, $context);
        if (!$contents) {
            throw new Exception("Failed to get contents of $proxyUrl");
        }
        if (!file_put_contents($path, $contents, LOCK_EX)) {
            throw new Exception("Failed to write contents from $proxyUrl to disk");
        }

        if (isset($this->status) and $this->statusArr) {
            $this->statusArr['statusCode'] = 200;
            $this->statusArr['statusString'] = "Complete @ ".date('Y-m-d H:i:s e');
            $this->statusArr['timestamp'] = time();
            $this->status[$this->page] = $this->statusArr;
            $this->snapshot->setValue('pagestatus', json_encode($this->status));
            $this->snapshot->save();
        }


        header('Content-type: application/json; charset="UTF-8"');
        echo json_encode(array(
          'code' => 200,
          'message' => 'Successfully created snapshot',
          'snapshotStatusCode' => 200,
          'snapshotStatusMessage' => 'Complete'
        ));

    }
}
