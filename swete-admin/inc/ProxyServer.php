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
require_once 'inc/SweteSite.class.php';
require_once 'inc/SweteRequestLogger.php';
require_once 'modules/tm/lib/XFTranslationMemory.php';
/**
 * @brief Class that handle the actual HTTP requests from users.  This sets
 * the translation in motion for a single page in real-time and returns
 * the correct page.
 *
 * @see actions_swete_handle_request For the actual usage of this class.
 *
 * @code
 * $server = new ProxyServer;
 * $server->logTranslationMisses = true;
 * $site = SweteSite::loadSiteByUrl($url);
 * $server->site = $site;
 * $server->URL = $url;
 * $server->handleRequest();
 * @endcode
 *
 */
class ProxyServer {

    /**
     * @brief Input HTML content to be translated
     * If this is provided then it is used instead of trying to load the source page.
     */
    public $inputContent;

	public $enableProfiling = false;

	/**
	 * @brief Read-write property with the site that the proxy server will run on.
	 * @type SweteSite
	 */
	public $site;
	
	/**
	 * @brief Read/write property with the minimum string status allowed for
	 * translations.  Translations with a lower status than this will not 
	 * be considered for the translation.
	 *
	 * Examples of string statuses:
	 *
	 * - XFTranslationMemory::TRANSLATION_REJECTED
	 * - XFTranslationMemory::TRANSLATION_SUBMITTED
	 * - XFTranslationMemory::TRANSLATION_APPROVED
	 *
	 * @type int
	 */
	public $minStringStatus = null;
	
	/**
	 * @brief Read/write property with the maximum string status allowed for
	 * translations.  Translations with a higher status than this will not 
	 * be considered for the translation.
	 *
	 * Examples of string statuses:
	 *
	 * - XFTranslationMemory::TRANSLATION_REJECTED
	 * - XFTranslationMemory::TRANSLATION_SUBMITTED
	 * - XFTranslationMemory::TRANSLATION_APPROVED
	 *
	 * @type int
	 */
	public $maxStringStatus = null;
	
	/**
	 * @brief The allowable page status for the page to display.  The server
	 * will look for the most recent page matching the requested url with the
	 * specified @e pageStatus value.
	 *
	 * Some statuses include:
	 * 
	 * - SweteWebpage::STATUS_NEW
	 * - SweteWebpage::STATUS_CHANGED
	 * - SweteWebpage::STATUS_PENDING_APPROVAL
	 * - SweteWebpage::STATUS_APPROVED
	 *
	 * @default SweteWebpage::STATUS_APPROVED
	 *
	 * @type int
	 */
	public $pageStatus = 5;
	
	/**
	 * 
	 * @brief A flag to indicate whether translation misses should be logged.
	 * If this is set to true than any string for which there is no valid translation
	 * will be logged to the translation_miss_log table.  These can then
	 * easily be monitored for changes and translated.
	 * @type boolean
	 */
	public $logTranslationMisses = false;
	
	
	/**
	 * @brief A replacement for the $_SERVER array.  Default
	 * just loads @e $_SERVER, but can be overridden to simulate different
	 * environments.
	 *
	 * @type array
	 */
	public $SERVER=array();
	
	/**
	 * @brief A replacement for the @e $_REQUEST array.  Default just loads 
	 * @e $_REQUEST, but can be overridden to simulate different environments.
	 *
	 * @type array
	 */
	public $REQUEST=array();
	
	/**
	 * @brief A replacement for the @e $_GET array.  Default just loads 
	 * @e $_GET, but can be overridden to simulate different environments.
	 *
	 * @type array
	 */
	public $GET=array();
	
	/**
	 * @brief A replacement for the @e $_POST array.  Default just loads 
	 * @e $_POST, but can be overridden to simulate different environments.
	 *
	 * @type array
	 */
	public $POST=array();
	
	/**
	 * @brief A replacement for the @e $_COOKIE array.  Default just loads 
	 * @e $_COOKIE, but can be overridden to simulate different environments.
	 *
	 * @type array
	 */
	public $COOKIE=array();
	
	/**
	 * @brief The URL that is being requested.  This URL will be requested 
	 * as a proxified URL (i.e. the URL for the proxy page not the source page).
	 *
	 * @type string
	 */
	public $URL;
	
	/**
	 * @brief Flag to enable/disable buffering.  If buffering is enabled, then 
	 * output and headers won't be output to the browser.  They will be written 
	 * to a buffer so that they can be further processed.  This is primarily
	 * for testing purposes.
	 *
	 * @type boolean
	 */
	public $buffer = false;
	
	/**
	 * @brief A buffer to store headers that are output by the server during processing.
	 * This is only used if the @e $buffer flag is set.
	 *
	 * @type array
	 */
	public $headerBuffer = array();
	
	/**
	 * @brief The content buffer.  This is only used if the @e $buffer flag is set.
	 *
	 * @type string
	 */
	public $contentBuffer = '';
	
	/**
	 * @brief The logger that is used to log the activity of this request.  The 
	 * Default logger is the SweteRequestLogger class which saves information about
	 * the request to the @e http_request_log table.
	 *
	 * @type SweteRequestLogger
	 */
	public $logger = null;
	
	/**
	 * @type LiveCache
	 * @brief A reference to the LiveCache object used for caching.
	 */
	public $liveCache = null;
	
	/**
	 * @brief Initializes the server, sets a default request logger.
	 */
	public function __construct(){
		$this->SERVER = $_SERVER;
		$this->REQUEST = $_REQUEST;
		$this->GET = $_GET;
		//$rawPostData =  file_get_contents('php://input');
		//$rawPostPieces = explode('&', $rawPostData);
		$this->POST = array();
		//foreach ( $rawPostPieces as $piece ){
		//    list($k,$v) = explode('=', $piece);
		//    $this->POST[urldecode($k)] = urldecode($v);
		//}
		$this->REQUEST = $_REQUEST;
		$this->COOKIE = $_COOKIE;
		$this->logger = new SweteRequestLogger();
	}
	
	protected function mark($msg){
		if ( $this->enableProfiling ){
			error_log('[SWeTE Profiling][pid='.getmypid().']['.microtime().'] '.$msg);
		}
	}
	
	/**
	 * @brief Handles an HTTP request.  Processes the inputs and returns the 
	 * correct output.
	 */
	public function handleRequest(){
		$this->mark("handleRequest: ".$this->URL);
		$url = $this->URL;
		
		
		
		$proxyWriter = $this->site->getProxyWriter();
		$logger = $this->logger;
		$logger->proxyRequestUrl = $url;
		$isPost = (strtolower($this->SERVER['REQUEST_METHOD']) === 'post');
		if ( $isPost ) {
			// We cannot cache post requests.
			// The cacher knows this, but let's make doubly sure.
			Dataface_Application::getInstance()->_conf['nocache'] = 1;
		} 
		if ( !$isPost and !preg_match('#\.(ico|ICO|gif|GIF|jpg|JPG|jpeg|JPEG|SWF|swf|css|CSS|png|PNG|pdf|PDF|doc|DOC|svg|SVG|fla|FLA|zip|ZIP|js|JS)$#', $url)){
			
			
			$logger->proxyRequestHeaders = serialize(apache_request_headers());
			$logger->proxyRequestPostVars = serialize($this->POST);
			$logger->proxyRequestMethod = $this->SERVER['REQUEST_METHOD'];
			$logger->settingsSiteId = $this->site->getRecord()->val('settings_site_id');
			$this->mark('Loading webpage');
			$page = $this->site->loadWebpageByProxifiedUrl($url);
			$this->mark('Webpage loaded');
			
			if ( $page ){
				$logger->webpageId = $page->getRecord()->val('webpage_id');
				if ( !$page->getRecord()->val('active') ){
					$logger->webpageNotUsedReason = 'Not active';
				
				} else {
					$this->mark('Loading latest version with status '.$this->pageStatus);
					$version = $page->getLastVersionWithStatus($this->pageStatus, $this->site->getDestinationLanguage());
					$this->mark('Version loaded');
					if ( $version ){
						$logger->webpageVersionId = $version->val('webpage_version_id');
						//print_r($version->vals());exit;
						$this->mark('Proxifying html');
						$out = $this->site->getProxyWriter()->proxifyHtml(
							$version->val('page_content')
						);
						$this->mark('Finished proxifying html');
						
						$logger->outputContent = $out;
						
						
						$this->header('Content-Length: '.strlen($out));
						$this->header('Connection: close');
						$this->header('Cache-Control: max-age=36000');
						$this->header('X-SWeTE-Handler: ProxyServer/Static page/v'.$logger->webpageVersionId.'/'.__LINE__);
						$this->output($out);
						while ( @ob_end_flush());
						flush();
						$this->mark('Flushed contents to browser');
						$logger->outputResponseHeaders = serialize(headers_list());
						$logger->save();
						return;
						
					}
				}
			}
		}
		
		
		
		//Wasn't found so we try to load the 
		$this->mark('Getting the source page');
		if ( @$this->inputContent ){
		    // The content was provided.. we don't need to try to load it from 
		    // the source site
		    $client = $this->createClientForInputContent();
		} else if ( $this->liveCache and $this->liveCache->client ){
			$client = $this->liveCache->client;
		} else {
			$client = $this->getSourcePage();
		}
		$this->mark('Source page loaded');
		
		//echo "We got the source page.";
		//print_r($client->headers);
		
		$isHtml = preg_match('/html|xml/', $client->contentType);
		$isCSS = preg_match('/css/', $client->contentType);
		$headers = $proxyWriter->proxifyHeaders($client->headers, true);
		$locHeaders = preg_grep('/^Location:/i', $headers);
		// Let's see if this should be a passthru
		if ( !$isHtml and !$isCSS ){
			//$skip_decoration_phase = true;
			$cacheControlSet = false;
			foreach ($headers as $h){
				if ( preg_match('/^Cache-control:(.*)$/i', $h, $matches) ){
					// We need to respect caching rules.
					// If this content is private then we cannot cache it
					$cacheControlSet = true;
					if ( preg_match('/private|no-store|max-age=0|s-maxage=0/', $matches[1]) ){
						Dataface_Application::getInstance()->_conf['nocache'];
					}
				}
				$this->header($h);
			}
			$this->header('Content-Length: '.strlen($client->content));
			$this->header('Connection: close');
			$this->header('X-SWeTE-Handler: ProxyServer Unprocessed/Non-HTML/Non-CSS/'.__LINE__);
			//if ( !$cacheControlSet ) $this->header('Cache-Control: max-age=3600, public');
			$this->output( $client->content );
			if ( !$this->buffer ){
				while ( @ob_end_flush());
				flush();
			}
			$this->mark('Flushed non-html content');
			return;
		}
		$stats = array();
		if ( $isHtml and !$locHeaders ){
			$delegate = new ProxyClientPreprocessor($this->site->getRecord()->val('website_id'));
			$this->mark('Preprocessing page content');
			$client->content = $delegate->preprocess($client->content);
			$this->mark('Finished preprocessing');
			$logger->requestDate = date('Y-m-d H:i:s');
			$logger->proxyRequestUrl = $url;
			$logger->proxyRequestHeaders = serialize(apache_request_headers());
			$logger->proxyRequestPostVars = serialize($this->POST);
			$logger->proxyRequestMethod = $this->SERVER['REQUEST_METHOD'];
			$logger->websiteId = $this->site->getRecord()->val('website_id');
			
			$this->mark('Getting the profile for this page');
			$profile = $this->site->getProfile($proxyWriter->stripBasePath($url));
			$this->mark('Profile retrieved');
			if ( $profile and  $profile->val('enable_live_translation') ){
				if ( isset($this->liveCache) ){
					$this->liveCache->live = true;
				}
				try {
					$translation_memory_id = $profile->val('translation_memory_id');
					if ( !$translation_memory_id ){
						throw new Exception("No translation memory is set up for this page: ".$url);
					}
					if ( isset($this->liveCache) ){
						$this->liveCache->translationMemoryId = $translation_memory_id;
					}
					require_once 'modules/tm/lib/XFTranslationMemory.php';
					//$minApprovalLevel = $profile->val('live_translation_min_approval_level');
					$minApprovalLevel = 3;//XFTranslationMemory::TRANSLATION_APPROVED;
					
					
					$logger->liveTranslationEnabled = 1;
					$logger->liveTranslationMinStatus = $minApprovalLevel;
					
					$this->mark('Loading translation memory: '.$translation_memory_id);
					$tm = XFTranslationMemory::loadTranslationMemoryById($translation_memory_id);
					$this->mark('Translation memory loaded');
					
					$logger->translationMemoryId = $translation_memory_id;
					$proxyWriter->setTranslationMemory($tm);
					$proxyWriter->setMinTranslationStatus($minApprovalLevel);
					
					$this->mark('Translating html');
					$client->content = $proxyWriter->translateHtml($client->content, $stats, $this->logTranslationMisses);
					$this->mark('Translation complete');
					//print_r($stats);exit;
					$logger->liveTranslationHits = $stats['matches'];
					$logger->liveTranslationMisses = $stats['misses'];
				} catch (Exception $ex){
					error_log($ex->getMessage());
				}
				
			
			}
			
			$this->mark('PROXIFY HTML START');
			$client->content = $proxyWriter->proxifyHtml($client->content);
			$this->mark('PROXIFY HTML END');
			//$client->content = preg_replace('#</head>#', '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script><script src="http://localhost/sfutheme/js/newclf.js"></script><link rel="stylesheet" type="text/css" href="http://localhost/sfutheme/css/newclf.css"/></head>', $client->content);
			
		} else if ( $isCSS ){
			if ( isset($this->liveCache) ){
				$this->liveCache->live = true;
			}
			$logger->requestLoggingEnabled = false;
			$this->mark('PROXIFY CSS START');
			$client->content = $proxyWriter->proxifyCss($client->content);	
			$this->mark('PROXIFY CSS END');
		} else {
			$logger->requestLoggingEnabled = false;
		}
		$cacheControlSet = false;
		foreach ($headers as $h){
			if ( preg_match('/^Cache-control:(.*)$/i', $h, $matches) ){
				// We need to respect caching rules.
				// If this content is private then we cannot cache it
				$cacheControlSet = true;
				if ( preg_match('/private|no-store|max-age=0|s-maxage=0/', $matches[1]) ){
					Dataface_Application::getInstance()->_conf['nocache'] = 1;
				}
			}
			//error_log("Setting header: $h");
			$this->header($h);
		}
		$this->header('Content-Length: '.strlen($client->content));
		$this->header('X-SWeTE-Handler: ProxyServer Live/Processed/'.__LINE__);
		$this->header('Connection: close');
		
		// We won't add our own cache-control.  We'll let the source site decide this and send
		// their own headers.
		if ( !$cacheControlSet and  class_exists('Xataface_Scaler') and !@Dataface_Application::getInstance()->_conf['nocache'] ){
			//$this->header('Cache-Control: max-age=3600');
		}
		
		$this->output( $client->content );
		if ( !$this->buffer ){
			while (@ob_end_flush());
			flush();
			
			if ( isset($this->liveCache) ){
				$this->mark('The live cache is enabled.  Lets set the content');
				$this->liveCache->siteId = $this->site->getRecord()->val('website_id');
				$this->liveCache->sourceLanguage = $this->site->getSourceLanguage();
				$this->liveCache->proxyLanguage = $this->site->getDestinationLanguage();
				$this->liveCache->proxyUrl = $this->site->getProxyUrl();
				$this->liveCache->siteUrl = $this->site->getSiteUrl();
				$this->liveCache->sourceDateLocale = $this->site->getRecord()->val('source_date_locale');
				$this->liveCache->targetDateLocale = $this->site->getRecord()->val('target_date_locale');
				$this->liveCache->content  = $client->content;
				$this->liveCache->headers = headers_list();
				$this->liveCache->calculateExpires();
				if ( $this->logTranslationMisses ){
					$this->liveCache->skipLiveCache = true;
				} else {
					$this->liveCache->skipLiveCache = false;
				}
				$this->mark('About to check if resource can be cached.');
				if ( $this->liveCache->expires > time() ){
					$this->mark('Caching resource for live cache');
					$this->liveCache->save();
					$this->liveCache->saveContent();
					$this->mark('Finished cashing resource for live cache.');
				} else {
					if ( $this->enableProfiling ){
						$this->mark('Resource cannot be cached with live cache.  Expiry is '.date($this->liveCache->expires).' but now is '.time().'.');
					}
					$this->mark('Saving just the cache info entry');
					$this->liveCache->save();
					
				}
			}
		}
		$this->mark('Content flushed');
		
		$logger->outputContent = $client->content;
		
		$logger->outputResponseHeaders = serialize(headers_list());
		$logger->save();
		
		$this->mark('Loading the translation miss log');
		$tlogEntry = new Dataface_Record('translation_miss_log', array());
		
		if ( $this->logTranslationMisses and @$stats['log']){
			$this->mark('ITERATING TRANSLATION MISSES START ('.count($stats['log']).')');
			foreach ($stats['log'] as $str){
				
				$tlogEntry = new Dataface_Record('translation_miss_log', array());
				$nstr = TMTools::normalize($str);
				$trimStripped = trim(strip_tags($nstr));
				if ( !$trimStripped ) continue;
				if ( preg_match('/^[0-9 \.,%\$#@\(\)\!\?\'":\+=\-\/><]*$/', $trimStripped))  continue;
					// If the string is just a number or non-word we just skip it.
				$estr = TMTools::normalize(TMTools::encode($nstr, $junk));
				$strRec = XFTranslationMemory::addString($estr, $this->site->getSourceLanguage());
				
				$hstr = md5($estr);
				
				$tlogEntry->setValues(array(
					'http_request_log_id' => $logger->getRecord()->val('http_request_log_id'),
					'string' => $str,
					'normalized_string' => $nstr,
					'encoded_string' => $estr,
					'string_hash' => $hstr,
					'date_inserted'=> date('Y-m-d H:i:s'),
					//'webpage_id'=>$page->val('webpage_id'),
					'website_id'=>$this->site->getRecord()->val('website_id'),
					'source_language' => $this->site->getSourceLanguage(),
					'destination_language' => $this->site->getDestinationLanguage(),
					'translation_memory_id' => @$translation_memory_id,
					'string_id' => $strRec->val('string_id')
					
					
				
				));
				
				$res = $tlogEntry->save();
				if ( PEAR::isError($res) ){
					//throw new Exception($res->getMessage());
					// This will throw an error if there is a duplicate... we don't care... we're not interested in duplicates
				}
				
			
			}
			$this->mark('ITERATING TRANSLATION MISSES END');
		}
		return;
		
		
		
		
		
		
		
		
		
		
		
		
		
	}
	
	/**
	 * @brief Builds a @ref ProxyClient object to load a page from the source site.
	 * The @ref ProxyClient object is returned.
	 *
	 * @returns ProxyClient The proxy client that has loaded the source page
	 */
	public function getSourcePage(){
		
		require_once 'inc/ProxyClient.php';
		
		$client = new ProxyClient;
		$forwardedFor = @$client->REQUEST_HEADERS['X-Forwarded-For'];
		if ( !$forwardedFor ) $forwardedFor = $_SERVER['REMOTE_ADDR'];
		else $forwardedFor .= ', '.$_SERVER['REMOTE_ADDR'];
		
		$client->REQUEST_HEADERS['X-Forwarded-For'] = $forwardedFor;
		if ( @$client->REQUEST_HEADERS['Referer'] ){
		    $client->REQUEST_HEADERS['Referer'] = $this->site->getProxyWriter()->unproxifyUrl($client->REQUEST_HEADERS['Referer']);
		}
		$client->REQUEST_HEADERS['X-SWeTE-Language'] = $this->site->getDestinationLanguage();
		$client->REQUEST_HEADERS['Accept-Language'] = $this->site->getDestinationLanguage();
		$client->SERVER = $this->SERVER;
		$client->REQUEST = $this->REQUEST;
		$client->GET = $this->GET;
		$client->POST = $this->POST;
		$client->COOKIE = $this->COOKIE;
		//echo "Preprocess: [".$this->URL.']';
		$proxyWriter = $this->site->getProxyWriter();
		
		$client->URL = $this->site->getProxyWriter()->unproxifyUrl($this->URL);
		$logger = $this->logger;
		if ( !isset($client->SERVER['REQUEST_METHOD']) ){
			print_r($client->SERVER);
			exit;
		}
		$logger->requestMethod = $client->SERVER['REQUEST_METHOD'];
		$logger->requestUrl = $client->URL;
		$logger->requestPostVars = serialize($client->POST);
		
		//echo "About to process ".$client->URL;
		$this->mark('About to process page request.');
		$client->process();
		$this->mark('Page request processed');
		$logger->responseHeaders = serialize($client->headers);
		$logger->responseBody = $client->content;
		$logger->responseContentType = $client->contentType;
		$logger->responseStatusCode = $client->status['http_code'];
		
		
		
		
		return $client;
		
	}
	
	
	private function createClientForInputContent(){
	    require_once 'inc/ProxyClient.php';
		
		$client = new ProxyClient;
		$client->contentType = 'text/html';
		$client->status = 200;
		$client->headers = array(
		    'HTTP/1.1 200 OK',
		    'Content-type: text/html; charset=UTF8'
		);
		$client->content = $this->inputContent;
	    return $client;
	}
	
	/**
	 * @brief A wrapper for the PHP header() function that respects the 
	 * buffer settigns of the server.  If the server has @e buffer enabled
	 * then it won't put out any HTTP headers.  It will just buffer them to be 
	 * retrieved later.
	 */
	public function header($h){
		if ( $this->buffer ) $this->headerBuffer[] = $h;
		else header($h, false);
	}
	
	
	/**
	 * @brief A wrapper for the @e echo function that supports buffering.
	 */
	public function output($content){
		if ( $this->buffer ) $this->contentBuffer .= $content;
		else echo $content;
	}
	
	
	
	

	

}