<?php
class tables_snapshots {

    function beforeSave(Dataface_Record $rec) {
      $siteRec = df_get_record('websites', array('website_id'=>'='.$rec->val('website_id')));
      import('inc/SweteSite.class.php');
      $site = $siteRec ? new SweteSite($siteRec) : null;
      $pages = $rec->val('pagelist');
      $lines = preg_split("/\\r\\n|\\r|\\n/", $pages);
      $proxyUrl = $site ? $site->getProxyurl() : null;
      $siteUrl = $site ? $site->getSiteUrl() : null;


      foreach($lines as $k=>$line) {
          $line = trim($line);
          $line = preg_split('/\s+/', $line)[0];
          
          if ($site) {
              $pos = strpos($line, $proxyUrl);
              if ($pos === 0) {
                  $line = substr($line, strlen($proxyUrl));
              }
              $pos = strpos($line, $siteUrl);
              if ($pos === 0) {
                  $line = substr($line, strlen($siteUrl));
              }
          }
          if (!trim($line)) {
              $line = "/";
          }
          $lines[$k] = $line;

      }

      $rec->setValue('pagelist', implode("\n", $lines));
    }

    function pagelist__default() {
        $app = Dataface_Application::getInstance();
        $record = $app->getRecord();
        if ($record and $record->table()->tablename == 'websites') {
            $whitelist = 'sites/'.basename($record->val('website_id')).'/whitelist.txt';
            if (is_readable($whitelist)) {
                return file_get_contents($whitelist);
            }
        }
        return '';
        
    }

    function section__pages(Dataface_Record $record) {
      return array(
          'label' => 'Pages',
          'content' => $this->_section__pages_content($record),
          'order' => 10,
          'class' => 'main'
      );
    }

    function active__htmlValue(Dataface_Record $record) {
        $active = $record->val('active');
        $out = $active;
        if ($active == 'No') {
            Dataface_JavascriptTool::getInstance()->import('swete/actions/set_current_snapshot.js');
            $out .= " <button data-snapshot-id='".htmlspecialchars($record->val('snapshot_id'))."' class='set-current-snapshot-btn'>Set As Current Snapshot</button>";
        }
        return $out;
    }

    function _section__website(Dataface_Record $record) {
        return array(
            'label' => 'Website',
            'content' => $this->_section__website_content($record),
            'order' => 11,
            'class' => 'main'
        );
    }

    function _section__website_content(Dataface_Record $record) {
        $site = df_get_record('websites', array('website_id'=>'='.$record->val('website_id')));
        if (!$site) {
            return '<div class="portalMessage">Cannot find website associated with this snapshot</div>';
        }

        if (!file_exists('snapshots')) {
            return '<div class="portalMessage">Cannot activate this snapshot because the snapshots directory doesn\'t exist.  Please create a snapshots directory and ensure that it is writable by the web server.</div>';

        }
        if (!is_writable('snapshots')) {
            return '<div class="portalMessage">Cannot activate this snapshot because the snapshots directory isn\'t writable by the web server.</div>';
        }

        $out = "<table><tr><td>Website:</td><td><a href='".htmlspecialchars($site->getURL('-action=view'))."'>"
          .htmlspecialchars($site->display('website_name')).'</a></td></tr>';
        if (intval($site->val('current_snapshot_id')) === intval($record->val('snapshot_id'))) {
            $out .= '<tr><td>Current Snapshot</td><td>This snapshot is currently active.</td></tr>';
        } else if (intval($site->val('current_snapshot_id')) !== 0) {
            $out .= '<tr><td>Current Snapshot</td><td><a href="'.htmlspecialchars(DATAFACE_SITE_HREF.'?-table=snapshots&snapshot_id='.$site->val('current_snapshot_id')).'"></td></tr>';
        } else {
            $out .= '<tr><td>Current Snapshot</td><td>This site has no current snapshot</td></tr>';
        }
        $out .= "</table>";
        if (intval($site->val('current_snapshot_id')) !== intval($record->val('snapshot_id'))) {
            Dataface_JavascriptTool::getInstance()->import('swete/actions/set_current_snapshot.js');
            $out .= "<p><button data-snapshot-id='".htmlspecialchars($record->val('snapshot_id'))."' class='set-current-snapshot-btn'>Set As Current Snapshot</button></p>";
        }

        return $out;

    }

    function _section__pages_content(Dataface_Record $record) {

        $snapshotsPath = 'snapshots';
        if (!file_exists($snapshotsPath)) {
            return '<div class="portalMessage">'.htmlspecialchars('Snapshots inactive.  Please create a snapshots directory inside the swete-admin directory such that it is writable by the webserver process.').'</div>';

        }
        if (!is_writable($snapshotsPath)) {
            return '<div class="portalMessage">'.htmlspecialchars('Snapshots inactive because the snapshots directory is not writable by the webserver process.').'</div>';
        }

        $pages = $record->val('pagelist');
        $lines = preg_split ('/$\R?^/m', $pages);
        $out = array();
        $maxLen = 90;

        $status = $record->val('pagestatus');
        if ($status) {
            $status = json_decode($status, true);
        } else {
            $status = array();
        }
        foreach ($lines as $line) {
            $line = trim($line);
            if (strpos($line, ' ') !== false) {
                $line = substr($line, 0, strpos($line, ' '));
            }
            if (strpos($line, "\t") !== false) {
                $line = substr($line, 0, strpos($line, "\t"));
            }
            $label = $line;
            if (strlen($label) > $maxLen) {
                $label = substr($label, 0, $maxLen/3) . '...' . substr($label, 2 * $maxLen / 3);
            }
            $lineStatusArr = @$status[$line];
            $lineStatusStr = "Queued";
            $statusCode = "0";
            if ($lineStatusArr) {
                $lineStatusStr = $lineStatusArr['statusString'];
                $statusCode = $lineStatusArr['statusCode'];
            }
            $out[] = "<tr data-status-code='".$statusCode."'"
                  ." data-snapshot-id='".htmlspecialchars($record->val('snapshot_id'))."'"
                  ." data-status-code='".htmlspecialchars($statusCode)."'"
                  ." data-status-message='".htmlspecialchars($lineStatusStr)."'"
                  ." data-page='".htmlspecialchars($line)."'"
                  .">"
                ."<td><button style='display:none;' class='refresh-snapshot'>Refresh</button>"
                ."<button style='display:none' class='delete-from-snapshot'>Delete</button>"
                ."<td><abbr title='".htmlspecialchars($line)."'>".htmlspecialchars($label)."</abbr></td>"
                ."<td><span class='snapshot-status-message'>".htmlspecialchars($lineStatusStr)."</span></td>"
                ."<td><span class='row-progress' style='display:none'><img src='".DATAFACE_URL."/images/progress.gif'/></span></td>"
                ."</tr>";
        }

        $out = '<table class="swete-snapshots">'.implode("\n", $out).'</table>';
        $progress = '<div style="display:none" id="snapshot-progress"><img src="'.DATAFACE_URL.'/images/progress.gif"/></div>';
        $refreshAll = '<div><button class="refresh-all-snapshots">Force Refresh</button></div>';
        import('Dataface/JavascriptTool.php');
        Dataface_JavascriptTool::getInstance()
            ->import('swete/actions/refresh_page_snapshot.js');
        return $progress.$out.$refreshAll;


    }
}
