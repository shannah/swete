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
 * @brief A class that is meant to serve as a smart reverse proxy that respects HTTP
 * caching headers.  This class is set up as a gatekeeper to the system (especially
 * for the swete_handle_request action so that files that don't require processing 
 * can be piped directly through to the client, and files that require processing
 * can be held for further processing.
 *
 * @created June 10, 2012
 * @author Steve Hannah <steve@weblite.ca>
 * Copyright (c) 2012 Web Lite Translation Corp.  All rights reserved.
 *
 */
class LiveCache {

    public $DEBUG = false;
    
    /**
     * @brief The directory where cache files are stored.
     */
    public static $cacheDir = './livecache';
    
    public $useHtml5Parser = false;
    
    /**
     * @brief The singleton instance of the live cache so that it can 
     * be retrieved from anywhere in the application.
     *
     * @see getCurrentPage()
     * @type LiveCache
     */
    private static $currentPage = null;
    
    /**
     * @brief Optional logger object to handle logging.
     * @type SweteLogger
     */
    public $logger = null;
    
    /**
     * @brief The proxified URL for the request.  When requests are made, they are
     * made inside the proxy namespace.  Such urls are considered "proxified".  In 
     * order to actually fetch the resource from the source server, the URLs must
     * be converted to "unproxified" URLs.
     * 
     * @type string
     */
    public $proxifiedUrl = null;
    
    /**
     * @brief The unproxified URL for the request.  This is the URL in the source server's
     * namespace.
     *
     * @type string
     */
    public $unproxifiedUrl = null;

    /**
     * @brief The expiration time for the cached version of this request.  This is
     * stored as a unix timestamp (seconds since epoch), and is calculated by the 
     * calculateExpiry() method based on the headers that have been received.
     *
     * @type int
     */
    public $expires = 0;
    
    /**
     * @brief The creation time of the cache entry.  This is marked every time
     * it is saved.
     * 
     * @type int
     */
    public $created = 0;
    
    /**
     * @brief The response headers that were received from the server for this request.
     * appropriate headers should be passed on each time the resource is returned to 
     * a client.
     *
     * @type array(string)
     */
    public $headers = null;
    
    /**
     * @brief Stores just the cache control response header.  This is handy since
     * we are frequently interested in this value -- saves us from looping through
     * the headers each time we need to access this.  All cache-control headers 
     * are concatenated in a single string for this value.
     * @var type 
     * @see cacheControl()
     * @see resetCacheControl()
     */
    public $cacheControl = null;
    
    /**
     * @brief The content of this resource.  This is populated when the page is loaded
     * from the source.
     */
    public $content = null;
    
    public $client = null;
    
    public $noServerCache = false;
    public $siteId = null;
    public $live = false;
    public $translationMemoryId = null;
    public $_proxyWriter = null;
    public $sourceLanguage = null;
    public $proxyLanguage = null;
    public $proxyUrl = null;
    public $siteUrl = null;
    public $db = null;
    public $skipLiveCache = false;
    public $sourceDateLocale = null;
    public $targetDateLocale = null;
    
    /**
     * If conservative caching is enabled, only pages with Cache-Control = public in the
     * response headers will be saved on the server.  This is the *safest* in multi-user 
     * applications.
     * @var type 
     */
    public $useConservativeCaching = true;
    
    
    /**
     * @brief Returns the path to the cached object for a resource as the given 
     * proxified URL.
     * 
     * @param string $proxifiedUrl The proxified URL of the resource.  I.e. in proxy space.
     * @returns string The path to the cached LiveCache object.
     */
    public static function getCachePathForProxifiedUrl($proxifiedUrl){
        return self::$cacheDir.DIRECTORY_SEPARATOR.sha1($proxifiedUrl);
    }
    
    /**
     * @brief Returns the path to the content of a resource - given by the proxified URL.
     * 
     * @param string $proxifiedUrl The proxified URL of the resource.  I.e. in proxy space.
     * @returns string The path to the actual content of the resource that is cached.
     */
    public static function getCacheContentPathForProxifiedUrl($proxifiedUrl){
        return self::getCachePathForProxifiedUrl($proxifiedUrl).'.content';
    }
    
    /**
     * @brief Returns the path to the cached content for this resource.
     * @returns string The path.
     */
    public function getCacheContentPath(){
        return self::getCacheContentPathForProxifiedUrl($this->proxifiedUrl);
    }
    
    /**
     * @brief Returns the path to the cached serialized LiveCache object for this object.
     * @returns string The path.
     */
    public function getCachePath(){
        return self::getCachePathForProxifiedUrl($this->proxifiedUrl);
    }
    
    public function cacheControl(){
        if ( !isset($this->cacheControl)){
            if ( !is_array($this->headers) ){
                return null;
            }
            $this->cacheControl = '';
            foreach ( $this->headers as $h ){
                if ( stripos($h, 'Cache-control:') === 0 ){
                    $this->cacheControl .= $h;
                }
            }
        }
        return $this->cacheControl;
    }
    
    public function resetCacheControl(){
        $this->cacheControl = null;
    }
    
    
    
    /**
     * @brief Loads a LiveCache object to encapsulate the specified resource.  The resource
     * is specified with a proxified URL (i.e. in proxy space).  If no such object has
     * been cached, then a new LiveCache object is created with its @l $proxifiedUrl attribute
     * set to the given @l $proxifiedUrl parameter.
     *
     * @param $proxifiedUrl The URL to the resource whose descriptor we wish to load.  The 
     * URL is expressed in proxy space.
     * @returns LiveCache A LiveCache object describing the specified resource.
     */
    public static function load($proxifiedUrl){
        $path = self::getCachePathForProxifiedUrl($proxifiedUrl);
        if ( file_exists($path) ){
            $content = file_get_contents($path);
            $obj = unserialize($content);
            return $obj;
            
        } else {
            $obj = new LiveCache;
            $obj->proxifiedUrl = $proxifiedUrl;
            return $obj;
        }
        
        
    }
    
    /**
     * @brief Returns a LiveCache object for the current page as specified by the current 
     * request URL (using MOD_REWRITE).
     * @returns LiveCache The LiveCache object for the currently requested resource.
     */
    public static function getCurrentPage(){
        if ( !isset(self::$currentPage) ){
            $url = implode('/', array_map('rawurlencode', explode('/', $_SERVER['REDIRECT_URL'])));
            if ( isset($_SERVER['REQUEST_URI']) ){
                if ( strpos($_SERVER['REQUEST_URI'], '?') !== false ){
                    list($junk, $_SERVER['REDIRECT_QUERY_STRING']) = explode('?', $_SERVER['REQUEST_URI']);
                } else {
                    $_SERVER['REDIRECT_QUERY_STRING'] = '';
                }
            }
            $sweteDirectives = array();
            if ( @$_SERVER['REDIRECT_QUERY_STRING'] ){
                $qstr = $_SERVER['REDIRECT_QUERY_STRING'];
                $parts = explode('&', $qstr);
                $qstrout = array();
                
                foreach($parts as $pt){
                    if ( preg_match('/^swete\:/', $pt) ){
                        list($d1,$d2) = explode('=', $pt);
                        $sweteDirectives[urldecode($d1)] = urldecode($d2);
                    } else {
                        $qstrout[] = $pt;
                    }
                }
                $url .= '?'.implode('&', $qstrout);
            }
            
            $url = self::df_absolute_url($url);
            
            self::$currentPage = self::load($url);
        }
        return self::$currentPage;
    }
    
    public static function df_absolute_url($url){
        $host = $_SERVER['HTTP_HOST'];
        $port = $_SERVER['SERVER_PORT'];
        $protocol = $_SERVER['SERVER_PROTOCOL'];
        if ( strtolower($protocol) == 'included' ){
            $protocol = 'HTTP/1.1';
        }
        $protocol = substr( $protocol, 0, strpos($protocol, '/'));
        $protocol = ((@$_SERVER['HTTPS']  == 'on' || $port == 443) ? $protocol.'s' : $protocol );
        $protocol = strtolower($protocol);
        $HOST_URI = $protocol.'://'.$host;//.($port != 80 ? ':'.$port : '');
        if ( (strpos($_SERVER['HTTP_HOST'], ':') === false) and !($protocol == 'https' and $port == 443 ) and !($protocol == 'http' and $port == 80) ){
            $HOST_URI .= ':'.$port;
        }
    
        if ( !$url ) return $HOST_URI;
        else if ( $url{0} == '/' ){
            return $HOST_URI.$url;
        } else if ( preg_match('/http(s)?:\/\//', $url) ){
            return $url;
        } else {
            $host_uri = $HOST_URI;
            $site_url = '';
            if ( $site_url ) {
                if ($site_url{0} == '/' ) $host_uri = $host_uri.$site_url;
                else $host_uri = $host_uri.'/'.$site_url;
            }
            
            return $host_uri.'/'.$url;
        }
    }
    
    public static function touchSite($siteId){
        touch(self::$cacheDir.DIRECTORY_SEPARATOR.'site-'.intval($siteId).'-refresh-time');
    }
    
    public static function touchTranslationMemory($translationMemoryId){
        touch(self::$cacheDir.DIRECTORY_SEPARATOR.'translation-memory-'.intval($translationMemoryId).'-refresh-time');
    }
    
    public static function getSiteModificationTime($siteId){
        $path = self::$cacheDir.DIRECTORY_SEPARATOR.'site-'.intval($siteId).'-refresh-time';
        if (!file_exists($path) ) return 0;
        return filemtime($path);
    }
    
    public static function getTranslationMemoryModificationTime($translationMemoryId){
        $path = self::$cacheDir.DIRECTORY_SEPARATOR.'translation-memory-'.intval($translationMemoryId).'-refresh-time';
        if (!file_exists($path) ) return 0;
        return filemtime($path);
    }
    
    /**
     * @brief Saves the LiveCache object as a serialized object in the cache directory.
     * It can be reloaded with the load() method given its proxified URL.
     *
     * @returns void
     */
    public function save(){
        $this->created = time();
        $path = $this->getCachePathForProxifiedUrl($this->proxifiedUrl);
        file_put_contents($path, serialize($this));
    }
    
    
    /**
     * @brief Magic function called when serializing.  It specifies which fields
     * should be serialized.
     * @returns array
     */
    public function __sleep(){
        return array(
            'proxifiedUrl',
            'unproxifiedUrl',
            'expires',
            'created',
            'headers',
            'siteId',
            'live',
            'sourceLanguage',
            'proxyLanguage',
            'skipLiveCache',
            'translationMemoryId',
            'proxyUrl',
            'siteUrl',
            'sourceDateLocale',
            'targetDateLocale'
        );
    }
    
    /**
     * @brief Flushes the cached content out to the browser.   This outputs the headers
     * and streams the content of the resource out to the browse.
     */
    public function flushCache(){
        if ( !$this->headers ) throw new Exception("No headers set when flushing cache.");
        if ( !file_exists($this->getCacheContentPath()) ) throw new Exception("No content cached for page.");
        $headers = preg_grep('/^(Content|Location|ETag|Last|Server|Vary|Expires|Allow|Cache|Pragma)/i', $this->headers);
        while ( @ob_end_clean());
        header("HTTP/1.0 200");
        foreach ($headers as $header){
            header($header, false);
        }
        header('X-SWeTE-Handler: LiveCache Cached-content/'.__LINE__.'/'.basename($this->getCacheContentPath()));
        $fp = fopen($this->getCacheContentPath(),'r');
        $bytesSent = 0;
        while(!feof($fp)) {
            $buf = fread($fp, 4096);
            echo $buf;
            flush();
            $bytesSent+=strlen($buf);    /* We know how many bytes were sent to the user */
        }
        //fpassthru($fh);
        fclose($fp);
        flush();
        exit;
    }
    
    public function q($sql){
        $db = $this->dbConnect();
        $res = mysql_query($sql, $db);
        if ( !$res ) throw new Exception(mysql_error($db));
        return $res;
    }
        
    /**
     * @brief Loads and streams the current resource from its source location.  If the content
     * is html or css, then it won't stream it.  Instead it will save the content in 
     * the @l $content property.
     * <p>If the content is not html or CSS it will flush the content and then exit execution.</p>
     *
     *
     */
    public function flushSource(){
        if ( !$this->unproxifiedUrl ) throw new Exception("No unproxified URL currently set.");
        require_once 'inc/ProxyClient.php';
        //ProxyClient::$db = $this->dbConnect();
        $this->client = $client = new ProxyClient;
        $forwardedFor = @$client->REQUEST_HEADERS['X-Forwarded-For'];
        if ( !$forwardedFor ) $forwardedFor = $_SERVER['REMOTE_ADDR'];
        else $forwardedFor .= ', '.$_SERVER['REMOTE_ADDR'];
        $client->REQUEST_HEADERS['X-Forwarded-For'] = $forwardedFor;
        $client->REQUEST_HEADERS['X-SWeTE-Language'] = $this->proxyLanguage;
        $client->REQUEST_HEADERS['Accept-Language'] = $this->proxyLanguage;
        $client->passThruHeaders[] = 'If-None-Match';
        $client->passThruHeaders[] = 'If-Modified-Since';
        
        $client->URL = $this->unproxifiedUrl;
        $savedCacheContent = false;
        if ( !$this->noServerCache ){
            $client->flushableContentTypeRegex = '#html|css#';
            $client->afterFlushCallback = array($this, 'afterBinaryFlush');
            $client->flushOutputFile = $this->getCacheContentPath();
            $savedCacheContent = true;
        }
        
        if ( isset($this->logger) ){
            $this->logger->requestMethod = $client->SERVER['REQUEST_METHOD'];
            $this->logger->requestUrl = $client->URL;
            $this->logger->requestPostVars = serialize($client->POST);
        }
        
        //echo "About to process ".$client->URL;
        $client->process();
        if ( intval($client->status['http_code']) === 304 ){
            foreach ( $client->headers as $h ){
                header($h, false);
            }
            exit;
        }
        $this->headers = $client->headers;
        $this->resetCacheControl();
        $cacheControl = $this->cacheControl();
        if ( $this->useConservativeCaching ){
            $public = false;
            if ( isset($cacheControl) and stripos($cacheControl, 'public') !== false ){
                $public = true;
            }
            if ( !$public ){
                $this->noServerCache = true;
                if ( $savedCacheContent ){
                    @unlink($this->getCacheContentPath());
                }
            }
        }
        $this->content = $client->content;
        $this->calculateExpires();
        
        
        if ( isset($this->logger) ){
            $this->logger->responseHeaders = serialize($client->headers);
            //$this->logger->responseBody = $client->content;
            $this->logger->responseContentType = $client->contentType;
            $this->logger->responseStatusCode = $client->status['http_code'];
        }
    }
    
    /**
     * @brief Goes through all of the headers in the $headers property to determine
     * what the expiry date of this content should be.  It uses the Cache-control, Pragma,
     * and Expires headers for this.
     *
     * <p>If the content is marked private (in Cache-control) then the expiry date will 
     * be the current time  (i.e. it is already expired).</p>
     */
    public function calculateExpires(){
        $expires = null;
        $private = false;
        $cacheControlFound = false;
        $expiresFound = false;
        $pragmaFound = false;
        foreach ($this->headers as $h){
            if ( !$cacheControlFound and stripos($h, 'Cache-control:') === 0 ){
                $cacheControlFound = true;
                if ( stripos($h, 'private') !== false ){
                    $private = true;
                    $expires = time();
                } else if ( preg_match('#(max-age|s-maxage)=(\d+)#i', $h, $matches) ){
                    $expires = time()+intval($matches[2]);
                }
                if ( stripos($h, 'no-cache') !== false ){
                    $expires = time();
                }
            } else if ( !$expiresFound and stripos($h, 'Expires:') === 0 ){
                $expiresFound = true;
                if ( preg_match('#^Expires:(.*)$#i', $h, $matches) ){
                    $expires = strtotime(trim($matches[1]));
                }
            } else if ( !$pragmaFound and stripos($h, 'Pragma:') === 0 ){
                $pragmaFound = true;
                if ( stripos($h, 'no-cache') !== false ){
                    $expires = time();
                }
            }
        }
        if ( !$private ){
        
            if ( !$expires ) $expires = time() + 3600; // If no expiry was set and this isn't private - then let it persist for 1 hour
            $this->expires = $expires;
            
        } else {
            $this->expires = time();
        }
    }
    
    /**
     * @brief A callback that is called by the ProxyClient immediately after flushing non-html/css content.
     * This performs the cleanup and saves the descriptor to the file system so that it is cached.
     *
     * This method exits execution.
     *
     * The ProxyClient should have already saved the output to the cache directory.
     *
     * @param ProxyClient $client The ProxyClient that performed the request.
     */
    public function afterBinaryFlush(ProxyClient $client){
        // After the flush, we need to save the cache
        $this->headers = array();
        
        $this->headers = preg_grep('/^(Content|Location|ETag|Last|Server|Vary|Expires|Allow|Cache|Pragma)/i', $client->headers);
        $this->calculateExpires();
        $this->save();
        exit;
    }
    
    
    
    /**
     * @brief Flushes the content encapsulated by this resource, but obeying the caching rules.  It 
     * will check both the request headers and the cached response headers to determine if the content
     * needs to be refreshed from the server.  If it needs to be refreshed, then it will pass control
     * to flushSource().  If it doesn't need to be refreshed and a cached version exists, then it 
     * will just return the content directly from the cache.
     *
     * This method will end execution after the flush if:
     * 1. The content was cached and that cache was output.
     * 2. The content was loaded from source but was not html or css (and thus required no further processing).
     *
     * @throws Exception If the properties necessary to load information is not yet set.
     *
     */
    public function flush(){
        $reqHeaders = apache_request_headers();
        foreach ($reqHeaders as $k=>$v) $reqHeaders[strtolower($k)] = strtolower($v);
        $oldestCreated = null;
        if ( @$reqHeaders['cache-control'] ){
            if ( preg_match('/(max-age|s-maxage)=(\d+)/i', $reqHeaders['cache-control'], $matches) ){
                $oldestCreated = time() - intval($matches[2]);
            }
            if ( stripos('/no-cache/i', $reqHeaders['cache-control']) !== false ){
                $oldestCreated = time();
            }
        }
        if ( $this->useConservativeCaching ){
            $cacheControl = $this->cacheControl();
            $public = false;
            if ( isset($cacheControl) ){
                $public = (stripos($cacheControl, 'public') !== false);
            }
            if ( !$public ){
                $this->noServerCache = true;
            }
            
        }
        $this->mark('Oldest created: '.date('Y-m-d H:i:s', $oldestCreated));
        if ( !$this->noServerCache and (!$oldestCreated or $oldestCreated < $this->created) and $this->expires > time() and file_exists($this->getCacheContentPath()) ){
            // We have a local cached version of this page so let's flush that out
            $this->mark('Flushing the cache');
            $this->flushCache();
        }   else if ( $this->unproxifiedUrl ) {
            $this->mark('Flushing the source');
            $this->flushSource();
            
        }   
    }
    
    public function mark($str){
        if ( $this->DEBUG )error_log('[LiveCache]['.$this->unproxifiedUrl.']['.getmypid().'] '.$str);
    }
    
    /**
     * @brief Saves the content of this resource to the cache path.
     */
    public function saveContent(){
        file_put_contents($this->getCacheContentPath(), $this->content);
    }
    
    public function dbConnect(){
        if ( !is_resource($this->db) ){
            $info = parse_ini_file('conf.db.ini', true);
            $this->db = mysql_connect($info['_database']['host'], $info['_database']['user'], $info['_database']['password']);
            mysql_select_db($info['_database']['name'], $this->db);
            mysql_query('set character_set_results = \'utf8\'', $this->db);
            mysql_query("SET NAMES utf8", $this->db);
            mysql_query('set character_set_client = \'utf8\'', $this->db);
        }
        return $this->db;
    }
    
    
    
    public function handleRequest(){
        $this->flush();
        $now = time();
        
        if ( 
            !$this->skipLiveCache and
            isset($this->client) and 
            isset($this->client->content) and 
            isset($this->siteId) and
            self::getSiteModificationTime($this->siteId) < $this->created and
            $this->live and
            $this->translationMemoryId and
            self::getTranslationMemoryModificationTime($this->translationMemoryId) < $this->created
        )
        {
        
            $isHtml = preg_match('/html|xml/', $this->client->contentType);
            $isCSS = preg_match('/css/', $this->client->contentType);
            $isJson = (preg_match('/json/', $this->client->contentType) or $this->client->content{0}=='{');

            $proxyWriter = $this->getProxyWriter();
            $json = null;
            if ( $isJson ){
                $json = json_decode($this->client->content, true);
                if ( isset($json) ){
                    $html = $proxyWriter->jsonToHtml($json);
                    $isHtml = isset($html);
                    if ( $isHtml ){
                        $this->client->content = $html;
                    } else {
                        $isJson = false;
                    }
                } else {
                    $isJson = false;                
                }
            }
            ProxyClientPreprocessor::$db = $this->dbConnect();
            $delegate = new ProxyClientPreprocessor($this->siteId);
            $delegate->preprocessHeaders($this->client->headers);
            $headers = $proxyWriter->proxifyHeaders($this->client->headers, true);
            $locHeaders = preg_grep('/^Location:/i', $headers);
            $translationMode = $delegate->getTranslationMode($this->client);
            if ( !$locHeaders 
                and ($isHtml or $isCSS or ($translationMode === ProxyClient::TRANSLATION_MODE_TRANSLATE) )
                and ( $translationMode !== ProxyClient::TRANSLATION_MODE_NOTRANSLATE)  
            )
            {
                if ( $isHtml ){

                    $this->mark('Preprocessing page content');
                    $this->client->content = $delegate->preprocess($this->client->content);
                    $this->mark('Finished preprocessing');
                    require_once 'modules/tm/lib/XFTranslationMemoryReader.php';
                    XFTranslationMemoryReader::$db = $this->dbConnect();
                    $tm = XFTranslationMemoryReader::loadTranslationMemoryById($this->translationMemoryId);
                    if ( !$tm ) throw new Exception("Translation memory ".$this->translationMemoryId." could not be found.");
                    $proxyWriter->setTranslationMemory($tm);
                    $this->mark('Translating html');
                    $this->client->content = $proxyWriter->translateHtml($this->client->content);
                    $this->mark('Translation complete');

                    $this->mark('PROXIFY HTML START');
                    $this->client->content = $proxyWriter->proxifyHtml($this->client->content);
                    $this->mark('PROXIFY HTML END');

                    if ( $isJson ){
                        $this->client->content = $proxyWriter->htmlToJson($json, $this->client->content);
                    }
                } else if (  $isCSS ){
                    $this->mark('PROXIFY CSS START');
                    $this->client->content = $proxyWriter->proxifyCss($this->client->content);  
                    $this->mark('PROXIFY CSS END');
                }

                foreach ($headers as $h){

                    header($h, false);
                }
                header('Content-Length: '.strlen($this->client->content));
                header('Connection: close');
                header('X-SWeTE-Handler: LiveCache Processed-content/'.__LINE__.'/No-server-cache:'.$this->noServerCache);
                echo $this->client->content;
                flush();
                $this->headers = $headers;
                $this->content = $this->client->content;
                $this->calculateExpires();
                if ( $this->expires > time() and !$this->noServerCache ){
                    $this->saveContent();
                    $this->save();
                }
                exit;

            } else {
                foreach ($headers as $h){

                    header($h, false);
                }
                header('Content-Length: '.strlen($client->content));
                header('Connection: close');
                header('X-SWeTE-Handler: LiveCache Unprocessed-content/'.__LINE__);
                echo $this->client->content;
                flush();

                exit;

            }
        }
    }
        
    
    public function getProxyWriter(){
        if ( !isset($this->_proxyWriter) ){
            require_once 'inc/ProxyWriter.php';
            $proxy = new ProxyWriter;
            $proxy->useHtml5Parser = $this->useHtml5Parser;
            $proxy->sourceDateLocale = $this->sourceDateLocale;
            $proxy->targetDateLocale = $this->targetDateLocale;
            $proxy->setProxyUrl($this->proxyUrl);
            $proxy->setSrcUrl($this->siteUrl);
            $res = $this->q("select `name`,`alias` from path_aliases where website_id='".addslashes($this->siteId)."'");
            while ( $row = mysql_fetch_assoc($res) ){
                $proxy->addAlias($row['name'], $row['alias']);
            }
            @mysql_free_result($res);
            $proxy->setSourceLanguage($this->sourceLanguage);
            $proxy->setProxyLanguage($this->proxyLanguage);
            $this->_proxyWriter = $proxy;
            
        }
        return $this->_proxyWriter;
    }

}