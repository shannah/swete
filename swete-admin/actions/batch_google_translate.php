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
class actions_batch_google_translate {
	
	var $sites = array();
	var $translationMemories = array();
	
	function compileString($string){
		return preg_replace(array(
				'/<g( [^>]+)>/',
				'/<v( [^>]+)>/',
				'/<x( [^>]+)>/',
				'/<\/g>/',
				'/<\/v>/'
			),
			array(
				'<em\\1>',
				'<b\\1>',
				'<img\\1>',
				'</em>',
				'</b>'
			),
			$string
		);
		
	}
	
	function uncompileString($string){
		return preg_replace(array(
				'/<em( [^>]+)>/',
				'/<b( [^>]+)>/',
				'/<img( [^>]+)>/',
				'/<\/em>/',
				'/<\/b>/'
			),
			array(
				'<g\\1>',
				'<v\\1>',
				'<x\\1>',
				'</g>',
				'</v>'
			),
			$string
		);
	}

	function handle($params){
		session_write_close();
		while ( @ob_end_clean());
		set_time_limit(0);
		header('Connection: Keep-Alive');
		header("Cache-Control: no-cache, must-revalidate");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		flush();		
		echo <<<END
<!doctype html>
<html>
	<head>
		<title>Batch Google Translate</title>
	</head>
	<body>
END;
		flush();
		for ( $i =0; $i<20; $i++){
			echo "                                                               ";
		}
		flush();
		
		
		
		require_once 'modules/tm/lib/googleTranslatePlugin.php';
		require_once 'modules/tm/lib/XFTranslationMemory.php';
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		
		$strings = df_get_selected_records($query);
		$errors = array();
		$translated = array();
		
		$googleCodes = array();
		$res = df_q("select language_code, google_language_code from languages");
		while ($row = mysql_fetch_row($res) ){
			$googleCodes[$row[0]] = $row[1] ? $row[1]:$row[0];
		}
		@mysql_free_result($res);
		
		$i=1;
		foreach ($strings as $string){
			
			$site = $this->getSite($string->val('website_id'));
			$encString = $string->val('encoded_string');
			$compiledString = $this->compileString($encString);
			echo "<script>progressBar.progressbar('option','value',".ceil(floatval($i)/floatval(count($strings))).");
				progressLabel.text('Processing ['+".json_encode($encString).".substring(0,30)+'...]');
				successMarker.text(".count($translated).");
				failedMarker.text(".count($errors).");
			</script>";
			flush();
			if ( !$site ){
				$errors[] = $err = "The string [".$encString."] could not be translated because the site with id ".$string->val('website_id')." could not be found.";
				echo "<script>progressLog.val(progressLog.val()+'\\n-------\\n'+".json_encode($err).");</script>";
				flush();
				continue;
			}
			
			if ( !$site->checkPermission('google translate') ){
				$errors[] = $err = "The string [".$encString."] could not be translated because you don't have permission.";
				echo "<script>progressLog.val(progressLog.val()+'\\n------\\n'+".json_encode($err).");</script>";
				flush();
				continue;
			}
			
			$apiKey = $site->val('google_api_key');
			
			if ( !$apiKey ){
				$errors[] = $err = "The string [".$encString."] could not be translated because the site that it belongs to does not have a Google API key set.";
				echo "<script>progressLog.val(progressLog.val()+'\\n-------\\n'+".json_encode($err).");</script>";
				flush();
				continue;
			}
			$sourceCode = $string->val('source_language');
			$destCode = $string->val('destination_language');
			
			$gtp = new googleTranslatePlugin($googleCodes[$sourceCode], $googleCodes[$destCode]);
			$gtp->setGoogleAPIKey($apiKey);
			//echo $compiledString;exit;
			$gtp->addSourceString($compiledString);
			try {
				$translations = $gtp->getTranslations();
			} catch (Exception $ex){
				$errors[] = $err = "Failed to translate string [".$string->val('encoded_string')."] due to a google translate error: ".$ex->getMessage();
				echo "<script>progressLog.val(progressLog.val()+'\\n-----\\n'+".json_encode($err).");</script>";
				flush();
				continue;
			}
			
			if ( !isset($translations[$compiledString]) ){
				$errors[] = $err = "Failed to translate string [".$encString."].  The string returned null after translation.";
				echo "<script>progressLog.val(progressLog.val()+'\\n-------\\n'+".json_encode($err).");</script>";
				flush();
				continue;
			}
			
			$tm = $this->getTranslationMemory($site->val('translation_memory_id'));
			$trString = $this->uncompileString($translations[$compiledString]);
			//echo $trString;exit;
			$tm->setTranslationStatus($encString, $trString, XFTranslationMemory::TRANSLATION_SUBMITTED, 'Google');
			
			$translated[$encString] = $trString;
			
		}
		
		echo "<script> progressBar.progressbar('option','value',100);
		progressLabel.text(".json_encode('Translated '.count($translated).' strings successfully.  '.count($errors).' errors.').");
		successMarker.text(".count($translated).");
		failedMarker.text(".count($errors).");
		</script>";
		flush();
		/*
		$out = array(
			'translated' => $translated,
			'errors' => $errors,
			'code' => 200,
			'message' => 'Translated '.count($translated).' strings successfully.  '.count($errors).' errors.'
		);
		
		$this->out($out);
		*/
		exit;
	}
	
	function out($out){
		header('Content-type: application/json; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
		echo json_encode($out);
		
	}
	
	function getSite($id){
		if ( !isset($this->sites[$id]) ){
			$this->sites[$id] = df_get_record('websites', array('website_id'=>'='.$id));
		}
		return $this->sites[$id];
	}
	
	function getTranslationMemory($id){
		if ( !isset($this->translationMemories[$id]) ){
			$this->translationMemories[$id] = XFTranslationMemory::loadTranslationMemoryById($id);
		}
		return $this->translationMemories[$id];
	}
}