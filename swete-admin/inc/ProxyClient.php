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
/**
 * @brief A class that facilitates HTTP requests to a particular URL.
 *
 * <h3>Example</h3>
 * <p>An example from the ProxyServer class which uses a ProxyClient object
 * to load the source page that is being requested by a user.
 * @code
 * public function getSourcePage(){
 *		require_once 'inc/ProxyClient.php';
 *		$client = new ProxyClient;
 *		$client->SERVER = $this->SERVER;
 *		$client->REQUEST = $this->REQUEST;
 *		$client->GET = $this->GET;
 *		$client->POST = $this->POST;
 *		$client->COOKIE = $this->COOKIE;
 *		$proxyWriter = $this->site->getProxyWriter();
 *		
 *		$client->URL = $this->site->getProxyWriter()->unproxifyUrl($this->URL);
 *		$logger = $this->logger;
 *		$logger->requestMethod = $client->SERVER['request_method'];
 *		$logger->requestUrl = $client->URL;
 *		$logger->requestPostVars = serialize($client->POST);
 *		
 *		$client->process();
 *		$logger->responseHeaders = serialize($client->headers);
 *		$logger->responseBody = $client->content;
 *		$logger->responseContentType = $client->contentType;
 *		$logger->responseStatusCode = $client->status['http_code'];
 *		
 *		
 *		return $client;
 *		
 *	}
 *
 * // Then later on ....
 * 		$client = $this->getSourcePage();
 *		
 *		//echo "We got the source page.";
 *		//print_r($client->headers);
 *		
 *		$isHtml = preg_match('/html|xml/', $client->contentType);
 *		$isCSS = preg_match('/css/', $client->contentType);
 *		
 *		$headers = $proxyWriter->proxifyHeaders($client->headers, true);
 *		
 *		
 *		// Let's see if this should be a passthru
 *		if ( !$isHtml and !$isCSS ){
 *			//$skip_decoration_phase = true;
 *			foreach ($headers as $h){
 *				$this->header($h, false);
 *			}
 *			$this->header('Content-Length: '.strlen($client->content));
 *			$this->header('Connection: close');
 *			$this->header('Cache-Control: max-age=3600');
 *			$this->output( $client->content );
 *			if ( !$this->buffer ) flush();
 *			return;
 *		}
 * @endcode
 */
class ProxyClient {

	/**
	 * @type string
	 *
	 * @brief Read-only content type that is filled when the HTTP request is made.  This
	 * is the content type that was returned from the HTTP server.  E.g.
	 * text/html or application/pdf
	 */
	public $contentType = null;
	
	/**
	 * @type array
	 *
	 * @brief Read-only parameter with information about the request.  This will be filled
	 * with the output of <a href="http://ca.php.net/manual/en/function.curl-getinfo.php">curl_get_info()</a>
	 * after the request is complete.
	 */
	public $status = null;
	
	/**
	 * @brief Read-only array with the HTTP response headers from the request.  Each element
	 * of this array is a raw header string.
	 *
	 * @type array
	 */
	public $headers = array();
	
	/**
	 * @brief Read-only parameter with the HTTP response content body from the request.
	 * @type string
	 */
	public $content = null;
	
	/**
	 * @type array
	 *
	 * @brief The $_SERVER array environment for the HTTP request.  If this is not
	 * set explicitly, it will default to a copy of the $_SERVER array.
	 *
	 * For example if you want the client to make a POST request, you should
	 * set the $this->SERVER['REQUEST_METHOD'] = 'post'.
	 */
	public $SERVER=array();
	
	/**
	 * @type array
	 *
	 * @brief The $_REQUEST array environment for the HTTP request.  If this is not
	 * set explicitly, it will default to a copy of the $_REQUEST array.
	 */
	public $REQUEST=array();
	
	/**
	 * @type array
	 *
	 * @brief The $_COOKIE array environment for the HTTP request.  If this is not
	 * set explicitly, it will default to a copy of the $_COOKIE array.
	 */
	public $COOKIE=array();
	
	/**
	 * @type array
	 *
	 * @brief The $_GET array environment for the HTTP request.  If this is not
	 * set explicitly, it will default to a copy of the $_GET array.
	 */
	public $GET=array();
	
	/**
	 * @type array
	 *
	 * @brief The $_POST array environment for the HTTP request.  If this is not
	 * set explicitly, it will default to a copy of the $_POST array.
	 */
	public $POST=array();
	
	/**
	 * @type string
	 *
	 * @brief Writable property to specify the URL of the page to request.
	 */
	public $URL=null;
	
	/**
	 * @type array
	 *
	 * @brief An array of HTTP request headers to send with the request.  If this 
	 * is not set, it defaults to the result of <a href="http://ca3.php.net/apache_request_headers">apache_request_headers</a>.
	 */
	public $REQUEST_HEADERS=null;
	
	/**
	 * @type boolean
	 *
	 * @brief Writable property that allows you to specify not to retrieve the body.  If this is 
	 * set to true, then the request will be sent as a HEAD request and retrieve no body.
	 */
	public $noBody = false;
	
	/**
	 * @type boolean
	 *
	 * @brief Writable property that allows you to specify whether cookies (in the COOKIE array) 
	 * should be passed on to the request.
	 */
	public $send_cookies = true;
	 
	/**
	 * @type boolean
	 * @brief Writable property that allows you to specify whether the SID should be 
	 * passed along with the request.
	 *
	 */ 
	public $send_session = true;
	
	/**
	 * @type string
	 *
	 * @brief The writable User-Agent string that should be passed with the request.
	 */
	public $user_agent = //'Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8.1a2) Gecko/20060512 BonEcho/2.0a2 sweteloader';
		'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.79 Safari/535.11';
	
	/**
	 * @brief A delegate object that contains callback functions to handle certain situations.
	 * @type ProxyClientDelegate
	 */
	public $delegate = null;

	/**
	 * @brief A regular expression to match content types that should be just flushed (as opposed to returned).
	 *   Often it is desirable to pass content directly through to the browser for performance reasons.  If 
	 *  this regex is set, then that is how the request will be handled.  It will relay the headers out 
	 *  to the browser and flush the body also as it is received.  It will not try to modify any information
	 *  in the middle.
	 *
	 * @type string
	 */
	public $flushableContentTypeRegex = null;
	
	public $clearOutputBufferBeforeFlush = true;
	
	/**
	 * @brief Optional path to a file to store the contents of the request.  If this is set
	 * then the curl_exec method will not return the page contents.  The contents will
	 * be instead piped directly to a file.  This is definitely preferred for large 
	 * responses so that we don't fill up memory.
	 *
	 * @type string
	 */
	public $outputFile = null;
	
	/**
	 * @brief The output file handle that is used by curl_exec when writing to an output file.
	 * It is opened and closed internally.
	 *
	 * @type resource
	 */
	private $outputFileHandle = null;
	
	/**
	 * @brief An output file that is only used for flushable content.  It provides a backup/caching
	 * mechanism so that while the output for a request is flushed directly to browser, it can
	 * also be piped into an output file simulataneously.
	 *
	 * @type string
	 */
	public $flushOutputFile = null;
	
	/**
	 * @brief An output stream for the $flushOutputFile when flushing the content.  This is opened
	 * when flushing content is enabled and it is determined that the returned content
	 * should just be flushed.
	 * @type resource
	 */
	private $fileFlushStream = null;

	/**
	 * @brief A buffer used to store headers when we are checking for flushable header content.
	 * @type array
	 */
	private $headerBuff = array();
	
	/**
	 * @brief A flag that is set when it is determined that the output should just be 
	 * flushed and the execution ended.  This is related to the $flushableContentTypeRegex
	 * parameter.
	 * @type boolean
	 */
	private $flushAndExit = false;
	
	public $afterFlushCallback = null;
	
	/**
	 * @brief Initializes the client object, setting default values for most properties.
	 *
	 * @param string $url The URL to request.
	 */
	public function __construct($url=null){
	
		$this->SERVER = $_SERVER;
		$this->REQUEST = $_REQUEST;
		$this->GET = $_GET;
		$this->COOKIE = $_COOKIE;
		//$rawPostData =  file_get_contents('php://input');
		//$rawPostPieces = explode('&', $rawPostData);
		$this->POST = array();
		//foreach ( $rawPostPieces as $piece ){
		//    list($k,$v) = explode('=', $piece);
		//    $this->POST[urldecode($k)] = urldecode($v);
		//}
		$this->URL = $url;
		$this->REQUEST_HEADERS = apache_request_headers();
	}
	
	/*
	 * @brief Clears all of the evironment array (e.g. $POST, $GET, etc..).  This does
	 * not clear the $URL parameter.
	 */
	public function clear(){
		$this->SERVER = array();
		$this->REQUEST = array();
		$this->GET = array();
		$this->COOKIE = array();
		$this->POST = array();
		$this->REQUEST_HEADERS = array();
	
	}
	
	
	/**
	 * @brief Adds a response header.  This is called when processing the response to the 
	 * request.
	 */
	private function header($h){
		$this->headers[] = $h;
	}
	
	public function read_header($ch, $string){
		//echo "in read_header";
		if ( !$this->flushAndExit ){
			$this->headerBuff[] = $string;
			if ( stripos($string, 'Content-type:') === 0 ){
				if ( !preg_match($this->flushableContentTypeRegex, $string) ){
					//echo "foo";
					$this->flushAndExit = true;
					if ( $this->flushOutputFile ){
						$this->fileFlushStream = fopen($this->flushOutputFile, 'w');
					}
					if ( class_exists('Dataface_Application') ){
						Dataface_Application::getInstance()->_conf['nocache'] = 1;
					}
					if ( $this->clearOutputBufferBeforeFlush ) while ( @ob_end_clean() );
					foreach ($this->headerBuff as $h){
						$this->headers[] = $h;
						header($h, false);
						//echo $h;
					}
					//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
				}
			}
		} else {
			//print_r($this->headerBuff);
			header($string, false);
			$this->header[] = $string;
			//echo $string;
		}
		return strlen($string);
	}
	
	private $bodyStart = false;
	//private $content = '';
	public function read_body($ch, $string){
		//echo "in readbody";
		if ( !$this->bodyStart ){
			if ( $this->outputFile ){
				fwrite($this->outputFileHandle, $string);
			} else {
				$this->content .= $string;
			}
			if ( !trim($string) ){
				$this->bodyStart = true;
			}
		} else {
			if ( $this->flushAndExit ){
				if ( $this->outputFile ){
					fwrite($this->outputFileHandle, $string);
				} else {
					echo $string;
					flush();
				}
				if ( $this->fileFlushStream ){
					fwrite($this->fileFlushStream, $string);
				}
			} else {
				if ( $this->outputFile ){
					fwrite($this->outputFileHandle, $string);
				} else {
					//echo "Appending to content....";
					$this->content .= $string;
				}
			}
		}
		return strlen($string);
	}
	

	/**
	 * @brief Actually performs a request.  This is more or less a wrapper around
	 * the <a href="http://ca3.php.net/manual/en/function.curl-exec.php">curl_exec</a> function
	 * but it performs some content conversion (e.g. converts all content to UTF-8).
	 * @param resource $ch The cURL resource.
	 * @returns string The content body of the response.
	 *
	 * @see process()
	 */
	public function curl_exec($ch){
		//$skip_decoration_phase = false;
		if ( $this->outputFile ){
			$this->outputFileHandle = fopen($this->outputFile, 'w');
			if ( !$this->outputFileHandle ) throw new Exception("Failed to open file ".$this->outputFile." for writing.");
		}
		if ( isset($this->flushableContentTypeRegex) ){
			//echo "Setting opts for readheader";
			curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this,'read_header'));
			curl_setopt($ch, CURLOPT_WRITEFUNCTION, array($this, 'read_body'));
			//curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			$this->flushAndExit = false;
			$this->content = '';
			curl_exec($ch);
			if ( $this->flushAndExit ){
				flush();
				if ( $this->flushOutputFile ){
					fclose($this->fileFlushStream);
				}
				if ( isset($this->afterFlushCallback) ){
					call_user_func($this->afterFlushCallback, $this);
				}
				return;
			} else {
				if ( $this->outputFile ){
					throw new Exception("Cannot use outputFile option with the flushableContentTypeRegex option");
				}
				$content = $this->content;
			}
		} else {
			if ( $this->outputFile ){
				
				curl_setopt($ch, CURLOPT_FILE, $this->outputFileHandle);
				//curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
			} else {
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			}
			$content = curl_exec($ch);
			error_log("Finished curl_exec");
			
			if ( $this->outputFile ){
				
				
				return;
			}
			
		}
		
		
		
		$encoding = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		$this->contentType = $encoding;
		
		if ( !preg_match('/text|html|xml/', $encoding) ){
			//$skip_decoration_phase = true;
	
			return $content;
		}
		if ( $encoding and preg_match('/charset=[\'"]?([a-zA-Z0-9\-]+)[\'"]?/i', $encoding, $matches )){
			$encoding = $matches[1];
		} else {
			$encoding = null;
		}
		
		//if ( !$encoding ){
			//echo "No encoding in header";
			//if ( preg_match('/<meta[^>]+http-equiv=[\'"]?content-type[\'"]?[^>]*>/i', $content, $matches) ){
		$regex = '/(<\?xml[^>]+encoding=[\'"]?)([a-zA-Z0-9\-]+)([\'"]?)/i';
		if ( preg_match($regex, $content, $matches) ){
			$encoding = $matches[2];
			$content = preg_replace($regex, '\\1UTF-8\\3', $content);
		}
		
		$regex = '/(<meta[^>]+charset=[\'"]?)([a-zA-Z0-9\-]+)([\'"]?)/i';
		if ( preg_match($regex, $content, $matches) ){
			$encoding = $matches[2];
			$content = preg_replace($regex, '\\1UTF-8\\3', $content);
			
		}
			
			
		//}
		
		if ( $encoding ){
			//echo "Converting encoding to utf-8 from $encoding";exit;
			return mb_convert_encoding($content, 'utf-8', $encoding);
		} else {
			
			return $content;
		}
		//return array('content'=>$content, 'encoding'=>$encoding);
	}
	
	/**
	 * @brief Performs the HTTP request.  This sets up the cURL query, executes it
	 * and processes it.
	 *
	 * @returns string The body from the response.
	 */
	public function process(){
		$url = $this->URL;
		
		if ( !$url ) throw new Exception("No URL specified to retrieve");
		
		$ch = curl_init( $url );
        if ( strtolower(@$this->SERVER['REQUEST_METHOD']) == 'post' ) {
            //print_r($this->POST);exit;
            $postStr = '';
            if ( !$this->POST ){
                $postStr = file_get_contents('php://input');
            } else {
                $postStr = http_build_query($this->POST);
            }
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $postStr );
			
		}
		
		if ( @$this->send_cookies and @$this->REQUEST_HEADERS['Cookie']) {
		    curl_setopt( $ch, CURLOPT_COOKIE, $this->REQUEST_HEADERS['Cookie']);
		}
		  
		curl_setopt( $ch, CURLOPT_HEADER, true );
		if ( $this->noBody ) curl_setopt( $ch, CURLOPT_NOBODY, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, @$this->user_agent ? @$this->user_agent : @$this->SERVER['HTTP_USER_AGENT'] );
		$headers = $this->REQUEST_HEADERS;
		if ( @$headers['Referer'] ){
            curl_setopt( $ch, CURLOPT_REFERER, @$headers['Referer']);
		}
		$reqHeaders = array();
		$reqHeaderCandidates = array(
			'X-SWeTE-Language'
		);
		foreach ($reqHeaderCandidates as $h){
			if ( isset($headers[$h]) ){
				$reqHeaders[] = $h.': '.$headers[$h];
			}
		}
		if ( $reqHeaders ){
			curl_setopt($ch, CURLOPT_HTTPHEADER, $reqHeaders);
		}
		
		
		$contents = $this->curl_exec( $ch );
		if ( $this->outputFile ){
			curl_close($ch);
			fclose($this->outputFileHandle);
			return;
		}
		$headerLen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($contents, 0, $headerLen);
		$contents = substr($contents, $headerLen);
		
		$status = curl_getinfo( $ch );
		$this->status = $status;
		//echo "Status: ";print_r($this->status);exit;
		
		curl_close( $ch );
	  
		if ( !$this->headers ){
			// Split header text into an array.
			$header_text = preg_split( '/[\r\n]+/', $header );
			
			
			
			// Propagate headers to response.
			foreach ( $header_text as $header ) {
			  //if ( preg_match( '/^(?:Content-Type|Content-Language|Set-Cookie|Location|Etag):/i', $header ) ) {
				//echo $header;
				
				//if ( preg_match('/^Location:/i', $header) ){
				//	  $header = str_replace($baseHost, $this->SERVER['HTTP_HOST'], $header);
				//}
				
				$this->header( $header, false );
			  //}
			}
		}
		
		//$contents = decorateContents($contents);
		//$len = strlen($contents);
		//header('Content-Length: '.$len);
		//if (!$this->POST){
		//  save_cache($url, $contents, headers_list());
		//}
		//header('Content-Length: '.strlen($contents));
		//header('Connection: close');
		//echo $contents;
		$method = 'preprocess';
		if ( isset($this->delegate) and method_exists($this->delegate, $method) ){
			$contents = $this->delegate->$method($contents);
		}
		
		$this->content = $contents;
		return $contents;
	
	}
	
	
	

}

interface ProxyClientDelegate {
	/**
	 * @brief Preprocesses HTML that is loaded from a destination.
	 * @param String $html The input html.
	 * @returns String The output html after preprocessing is complete.
	 */
	public function preprocess($html);
}

class ProxyClientPreprocessor implements ProxyClientDelegate {

	private $siteId;
	private $prefilters = null;
	public static $db = null;
	
	
	
	private function &getPrefilters(){
		if ( !isset($this->prefilters) ){
			$this->prefilters = array();
			$sql = "select tf.pattern, tf.replacement 
				from 
					text_filters tf 
					inner join site_text_filters stf on stf.filter_id=tf.filter_id
				where 
					stf.website_id='".addslashes($this->siteId)."' 
					and
					stf.filter_type='Prefilter'
				order by stf.filter_order, tf.default_order";
				
			$res = self::q($sql);
			while ($row = mysql_fetch_assoc($res) ){
				$this->prefilters[] = $row;
			}
			@mysql_free_result($res);			
		}
		//print_r($this->prefilters);exit;
		return $this->prefilters;
	}
	
	public function __construct($siteId){
		$this->siteId = $siteId;
		
	}
	
	public static function q($sql){
		$db = self::$db;
		if ( !is_resource($db) and function_exists('df_db') ) $db = df_db();
		$res = mysql_query($sql, $db);
		if ( !$res ) throw new Exception(mysql_error($db));
		return $res;
	}
	
	
	
	protected function _processText($string, $index, &$count){
		$filters =& $this->getPrefilters();
		if ( $index>count($filters)-1 ) return $string;
		$filter = $filters[$index];
		//echo 'before {'.$filter['pattern'].'}: ['.$string.']';
		$numReplaced = 0;
		$string = preg_replace($filter['pattern'], $filter['replacement'], $string, -1, $numReplaced);
		//echo 'after {'.$filter['pattern'].'}: ['.$string.']';
		//print_r($filter);
		if ( $numReplaced > 0 ){
			$count += $numReplaced;
			$parts = preg_split('/(<span[^>]+>.*<\/span>)/', $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$numParts = count($parts);
			for ( $i=0; $i<$numParts; $i+=2){
				$parts[$i] = $this->_processText($parts[$i], $index+1, $count);
			}
			return implode('', $parts);
		} else {
			return $this->_processText($string, $index+1, $count);
		}
		
		
		//return preg_replace('/\b(?<!&\#)([0-9]+[\/\.\-,]?)+/', '<span data-swete-translate="0">$0</span>', $string);
	}
	
	
	
	public function preprocess($html){
		$s = DIRECTORY_SEPARATOR;
		$base = defined('DATAFACE_SITE_PATH') ? DATAFACE_SITE_PATH:'.';
		$path = $base.$s.'sites'.$s.basename($this->siteId).$s.'Delegate.php';
		$class = 'sites_'.intval($this->siteId).'_Delegate';
		if ( !class_exists($class) ){
			if ( file_exists($path) ){
				require_once($path);
				if ( !class_exists($class) ){
					error_log('Loaded '.$path.' but no class '.$class.' was found... skipping preprocessing.');
					return $html;
				}	
			}
		}
		$obj = null;
		if ( class_exists($class) ){
			$obj = new $class;
			if ( method_exists($obj, 'fixHtml') ){
				$html = $obj->fixHtml($html);
			}
			
		}
		require_once 'inc/SweteTools.php';
		try {
			$doc = SweteTools::loadHtml($html);
		} catch (Exception $ex){
			return $html;
		}
		
		
		if ( isset($obj) and method_exists($obj, 'preprocess') ){
			//$obj = new $class;
			$obj->preprocess($doc);
		}
		$xpath = new DOMXPath($doc);
		$txt = $xpath->query('//text()');
		if ( $txt->length > 0 ){
			foreach ( $txt as $txtEl){
				if ( in_array(strtolower($txtEl->parentNode->tagName), array('style','title','script')) ) continue; 
				if ( !trim($txtEl->nodeValue) ) continue;
				
				$count = 0;
				$nodeValue = $this->_processText(htmlspecialchars($txtEl->nodeValue), 0, $count);
				
				// $nodeValue co
				/*
				$pieces = explode('<', $txtEl->nodeValue);
				foreach ($pieces as $i=>$piece ){
					if ( $i === 0 ){
						$pieces[$i] = $this->_processText($piece, 0, $count);
					} else {
						$pieceParts = explode('>', $piece);
						$numPieceParts = count($pieceParts);
						if ( $numPieceParts > 1 ){
							$pieceParts[$numPieceParts-1] = $this->_processText($pieceParts[$numPieceParts-1], 0, $count);
							$pieces[$i] = implode('>', $pieceParts);
						} else {
							$pieces[$i] = $this->_processText($piece, 0, $count );
						}
						
					}
				}
				*/
				if ( $count > 0 ){
					
					$f = $doc->createDocumentFragment();
					//$impl = implode('<', $pieces);
					//error_log($impl);
					$fres = $f->appendXML($nodeValue);
						
					
					
					$txtEl->parentNode->replaceChild($f, $txtEl);
				}
				
				
			}
		}	
			//return (string)$dom;
		//echo $doc->saveHtml();echo "here";exit;
		return $doc->saveHtml();
		
		
		
	}
}