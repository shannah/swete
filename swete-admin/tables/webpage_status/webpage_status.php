<?php
class tables_webpage_status {
/*
    private $_snapshot;
    private $_snapshotWebsite;
    
    function field__snapshot_id($record) {
        $app = Dataface_Application::getInstance();
        $query = $app->getQuery();
        return @$query['-snapshot-id'] ? intval($query['-snapshot-id']) : null;
    }
    
    function snapshotWebsite($record) {
        if (!isset($this->_snapshotWebsite)) {
            $this->_snapshotWebsite = SweteSite::loadSiteById($record->val('website_id'));
        }
        return $this->_snapshotWebsite;
    }
    
    function snapshot($record) {
        if (!isset($this->_snapshot)) {
            $snapId = $record->val('snapshot_id');
            if (!isset($snapId)) {
                return null;
            }
            $snap = df_get_record('snapshots', array('snapshot_id' => '='. $snapId));
            $status = $snap->val('pagestatus');
            if ($status) {
                $status = json_decode($status, true);
            } else {
                $status = array();
            }
            $this->_snapshot = $status;
        }
        return $this->_snapshot;
    }
    
    function field__snapshot_status_array($record) {
        $snap = $this->snapshot($record);
        if (!$snap) {
            return null;
        }
        $ws = $this->snapshotWebsite($record);
        if (!$ws) {
            return null;
        }
        $path = $record->val('pageurl
        
    }
*/
}