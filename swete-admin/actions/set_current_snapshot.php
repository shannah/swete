<?php
class actions_set_current_snapshot {
    public function handle($params=array()) {
        try {
            $this->_handle($params);
        } catch (Exception $ex) {
            header('Content-type: application/json; charset="UTF-8"');
            error_log('Failed to set current snapshot: '.$ex->getMessage());
            error_log(print_r($ex->getTraceAsString(), true));
            echo json_encode(array(
                'code' => 500,
                'message' => 'Failed to update site.  See error log for details.'
            ));
        }
    }

    public function _handle($params=array()) {


        $snapshotId = @$_POST['snapshot_id'];
        if (!$snapshotId) {
            throw new Exception("No snapshot ID specified");
        }

        $snapshot = df_get_record('snapshots', array('snapshot_id'=>'='.$snapshotId));
        if (!$snapshot) {
            throw new Exception("Snapshot not found");
        }
        $siteId = $snapshot->val('website_id');
        $site = df_get_record('websites', array('website_id'=>'='.$siteId));
        if (!$site) {
            throw new Exception("Cannot find site");
        }

        $site->setValue('current_snapshot_id', $snapshotId);
        if ($site->checkPermission('edit')) {
            $res = $site->save();
            if (PEAR::isError($res)) {
                throw new Exception("Failed to save site changes.  ".$res->getMessage());
            }
        } else {
            throw new Exception("You don't have permission to perform this action.");
        }

        header("Content-type: application/json; charset='UTF-8'");
        echo json_encode(array(
            'code' => 200,
            'message' => 'Site successfully updated'
        ));

    }
}
