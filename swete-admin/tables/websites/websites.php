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
class tables_websites {

    var $system_locales = null;
    function valuelist__system_locales(){
        if ( !isset($this->system_locales) ){
            exec('locale -a', $out);
            $this->system_locales = array_flip($out);
            //$this->system_locales = array_flip(array_map('trim',explode("\n", $out)));
            foreach ( $this->system_locales as $k=>$v){
                $this->system_locales[$k] = $k;
            }

        }
        return $this->system_locales;
    }

	function init(Dataface_Table $table){
		if ( !@Dataface_Application::getInstance()->_conf['enable_static'] ){
			$efl =& $table->getField('enable_live_translation');
			$efl['Default'] = 1;
			$efl['widget']['type'] = 'hidden';
		}
	}

	function beforeDelete($record){

		// We need to delete all associated webpages
		$pages = df_get_records_array('webpages', array('website_id'=>'='.$record->val('website_id')));
		while ( $pages ){

			foreach ($pages as $page ){

				$res = $page->delete(true);
				if ( PEAR::isError($res) ){
					return PEAR::raiseError("Failed to delete page ".$page->getTitle(), DATAFACE_E_NOTICE);
				}
			}

			$pages = df_get_records_array('webpages', array('website_id'=>'='.$record->val('website_id')));
		}

		if ( class_exists('LiveCache') ){
			LiveCache::touchSite($record->val('website_id'));
		}





	}

	function translation_parser_version__default(){
	    return 2;
	}


	function beforeInsert($record){
		if ( !$record->val('translation_memory_id') ){
			require_once 'modules/tm/lib/XFTranslationMemory.php';
			$tm = XFTranslationMemory::createTranslationMemory($record->val('website_name').' Dictionary', $record->val('source_language'), $record->val('target_language'));
			$record->setValue('translation_memory_id', $tm->getRecord()->val('translation_memory_id'));

		}
	}

	function beforeSave($record){
		//if base_path doesn't start with // then add it on
		$base_path = $record->val('base_path');
		$changedBasePath = false;
		if ( strpos($base_path, '//') !== false ){
			$base_path = preg_replace('#//#', '/', $base_path);
			$changedBasePath = true;
		}
		if ( substr($base_path, 0, 1) !== '/' ){
			$base_path = '/'.$base_path;

			$changedBasePath = true;
		}
		if ( !preg_match('/\/$/', $base_path) ){
			$base_path .= '/';
			$changedBasePath = true;
		}
		if ( $changedBasePath ){
			$record->setValue('base_path', $base_path);
		}

		if ( class_exists('LiveCache') ){
			LiveCache::touchSite($record->val('website_id'));
		}

    if ($record->valueChanged('current_snapshot_id')) {
        $record->pouch['current_snapshot_id'] = $record->val('current_snapshot_id');
    }
	}

	function host__default(){
		return $_SERVER['HTTP_HOST'];
	}

	function base_path__default(){
		//add a trailing slash if the site url doesn't already have one
		if (substr(DATAFACE_SITE_URL, 0, 1) == '/'){
			return dirname(DATAFACE_SITE_URL);
		}else{
			return dirname(DATAFACE_SITE_URL).'/';
		}

	}


	function afterInsert($record){
		require_once 'inc/SweteTools.php';
		SweteTools::updateDb();

		// Add default text filters.
		df_q("insert into site_text_filters (website_id, filter_id, filter_type, filter_order)
			select ".intval($record->val('website_id')).", filter_id, 'Prefilter', default_order
			from text_filters where is_default_prefilter=1 and
			(`language` IS NULL or `language`='".addslashes($record->val('source_language'))."')");

		df_q("insert into site_text_filters (website_id, filter_id, filter_type, filter_order)
			select ".intval($record->val('website_id')).", filter_id, 'Postfilter', default_order
			from text_filters where is_default_postfilter=1 and
			(`language` IS NULL or `language`='".addslashes($record->val('source_language'))."')");


	}

  function afterSave(Dataface_Record $record) {
      if (isset($record->pouch['current_snapshot_id'])) {
          $snapshotId = intval($record->pouch['current_snapshot_id']);
          $snapshotsDir = SWETE_DATA_ROOT . DIRECTORY_SEPARATOR . 'snapshots';
          if (!file_exists($snapshotsDir)) {
              throw new Exception("Failed to save current snapshot because the snapshots directory doesn't exist");
          }
          if (!is_writable($snapshotsDir)) {
              throw new Exception("Failed to save current snapshot because the snapshots directory is not writable.");
          }
          $snapshotDir = $snapshotsDir.DIRECTORY_SEPARATOR.intval($record->val('website_id'));
          if (!file_exists($snapshotDir)) {
              if (!@mkdir($snapshotDir)) {
                  throw new Exception("Failed to create snapshot directory for site");
              }
          }
          $snapshotFile = $snapshotDir.DIRECTORY_SEPARATOR."index.txt";
          file_put_contents($snapshotFile, "".$snapshotId, LOCK_EX);

      }
  }

  function section__capturing_strings(Dataface_Record $rec) {
      return array(
          'label' => 'Capturing Strings',
          'order' => 10,
          'class' => 'main',
          'content' => $this->_section__capturing_strings_content($rec)

      );
  }

  function _section__capturing_strings_content(Dataface_Record $rec) {
      $out = "";
      if ($rec->val('log_translation_misses')) {
          $out .= "<p>String capture is currently <b>ENABLED</b> globally.</p>";
          $out .= "<p>While string capture is enabled, pages will be slower to load and
          the server will experience higher than normal load because it is more taxing
          on the database.</p>";
          $out .= "<p>It is recommended to enable string capturing only as necessary to capture new strings.</p>";
          $out .= "<p>To disable string capturing, go to the <a href='".htmlspecialchars($rec->getURL('-action=edit'))."'>edit form</a>
                  , expand the 'More' section, and uncheck 'Log Translation Misses'.  Then click 'Save'.</p>";
      } else {
          $out .= "<p>String capturing is currently DISABLED globally.  This is the recommended state
            for a production site, but it also means that your translation memory will not be populated automatically.</p>";
          $out .= "<p>It is recommended to enable string capturing only as necessary to capture new strings.</p>";
          $out .= "<p>To enable string capturing, go to the <a href='".htmlspecialchars($rec->getURL('-action=edit'))."'>edit form</a>
                  , expand the 'More' section, and check 'Log Translation Misses'.  Then click 'Save'.</p>";

      }

      $out .= "<h3>Session-Based String Capturing</h3>";
      $out .= "<p>SWeTE supports session-based string capturing, which is recommended over
          global capturing as it allows you to enable/disable capturing on a per-session, per-client basis.
          It works by setting a cookie in the browser that SWeTE knows to look for.  If the cookie is present,
          then SWeTE will log misses for that request.  If it is not present, then SWeTE will fall back to
          the global default.</p>";
      import('inc/SweteSite.class.php');
      $site = new SweteSite($rec);
      $enableUrl = $site->getProxyUrl().'!swete:start-capture';
      $out .= "<p>Copy and Paste the following URLs and provide them to users who you want to have capturing enabled for.</p>";
      $out .= "<p><a class='xf-button' target='_blank' href='".htmlspecialchars($enableUrl)."'>Start Capturing</a></p>";
      $disableUrl = $site->getProxyUrl().'!swete:stop-capture';
      $out .= "<p><a class='xf-button' target='_blank' href='".htmlspecialchars($disableUrl)."'>Stop Capturing</a></p>";

      $out .= "<h4>The Specifics</h4>";
      $out .= "<p>SWeTE looks for the cookie named '--swete-capture' with a value of '1'.  If it is present, then SWeTE will capture strings.</p>";
      $out .= "<p>If you are using a site crawler as your mechanism for capturing strings, just have it send that cookie with its requests.  Most site crawlers support
      adding cookies in this way.</p>";
      $out .= "<p>You can also simply add the string '!swete:start-capture' to the end of add this cookie to your browser.</p>";
      $out .= "<p>To stop capturing, just quit your browser, or add '!swete:stop-capture' to the end of a URL.</p>";



      return $out;
  }
}
