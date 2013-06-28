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
require_once 'lib/simple_html_dom.php';

/**
 * @brief A functor class that collects all of the resources required to display 
 * a page and loads them into the database associated with a job.
 */
class SweteJobPageSucker {

	/**
	 * @type SweteJob
	 */
	private $job;
	
	
	
	
	private $_currUrl;
	private $_currBase;
	private $_currPrefix;
	
	
	public function __construct(SweteJob $job ){
		$this->job = $job;

	}
	
	public function getJob(){ return $this->job;}
	public function setJob(SweteJob $job){ $this->job = $job;}
	public function getSite(){
		return $this->job->getSite();
	}
	
	
	/**
	 * @brief Loads a resource from the job's table of loaded resources.  It will return null
	 * if the resource hasn't been loaded yet.
	 * @param string $hash The md5 hash of the Absolute URL of the resource we're checking on.
	 * @returns Dataface_Record A record from the job_content table.
	 */
	public function loadResource($hash){
		$jobid = $this->job->getRecord()->val('job_id');
		return df_get_record('job_content', array('job_id'=>'='.$jobid, 'url_hash'=>'='.$hash));
		
	}
	
	
	public function saveResource($url, $content, $contentType){
		$app = Dataface_Application::getInstance();
		$res = new Dataface_Record('job_content', array());
		$res->setValues(array(	
			'job_id'=> $this->job->getRecord()->val('job_id'),
			'url' => $url,
			'url_hash' => md5($url),
			'content_type' => $contentType,
			'content' => $content
		));
		$old =  $app->_conf['multilingual_content'];
		$app->_conf['multilingual_content'] = 0;
		$result = $res->save();
		$app->_conf['multilingual_content'] = $old;
		if ( PEAR::isError($res) ){
			throw new Exception($res->getMessage());
		}
	}
	
	/**
	 * @brief Processes a resource at a specified URL.  This will first check and see if the 
	 * resource has already been loaded and just return the hash to that resource.  If not
	 * it will perform an HTTP request to load the resource and return the resulting hash.
	 * @param string $url The absolute URL of the resource to proces.
	 * @param boolean $followRedirects Whether to follow HTTP redirects if the resource has moved.
	 * @param string[] $locations Array of locations that have already been followed.  This can help
	 *	prevent redirect loops.
	 * @return string MD5 hash of the absolute URL.  This can be used to look up the resource later
	 *	with loadResource().
	 */
	public function processResource($url, $followRedirects = false, $locations = array()){
	
		// First check to see if it is already loaded.
		$hash = md5($url);
		$resource = $this->loadResource($hash);
		if ( $resource ){
			return $hash;
		}
		
	
		require_once 'inc/ProxyClient.php';
		$client = new ProxyClient;
		$client->clear();
		$client->URL = $url;
		$found = false;
		//$locations = array();
		$client->noBody = false;  // only find out what type of content it is
		$client->process();
		
		if ( $client->status['http_code'] == 200 ){
			// We have success
			
			if ( preg_match('#css#', $client->contentType) ){
				$client->content = $this->processCss($client->content, $client->URL);
			}
			
			$this->saveResource($url, $client->content, $client->contentType);
			return md5($url);
		
			
		} else if ( $client->status['http_code'] >= 300 and $client->status['http_code'] < 400 ){
			if ( !$followRedirects ){
				throw new Exception("Failed to process page ".$url." because the page has moved and followRedirects is set to false in this invocation.");
				
			}
			// We got a redirect status code
			$location = null;
			foreach ($client->headers as $h){
				if ( preg_match('/^Location:(.*)$/i', $h, $matches) ){
					$location = trim($matches[1]);
					break;
				}
			}
			if ( !$location ){
				throw new Exception("Received an http status code of ".$client->status['http_code']." but no location header was found.");
				
			}
			
			$locations[$url] = true;
			if ( isset($locations[$location]) ){
				throw new Exception("Redirect loop found: ".$location);
			}
			return $this->processResource($location, $followRedirects, $locations);
		} else {
			throw new Exception("Failed to process page $url.  Received HTTP response code: ".$client->status['http_code']);
			
		}
		
	
	}
	
	
	
	/**
	 * @brief Processes HTML and converts all embedded resources into internal links.
	 * @param string $html The HTML to process.
	 * @param string $url The URL where the HTML was accessed.  This allows us to correctly convert
	 *	relative links into absolute ones.
	 * @return simple_html_dom The converted and parsed DOM.  This can easily be turned back into 
	 *	and HTML string at will or further processed.
	 */
	public function processHtml($html, $url){
		if ( strpos($url, $this->getSite()->getSiteUrl()) !== 0 ){
			throw new Exception("HTML could not be processed because the url $url lies outside the target site: ".$this->getSite()->getSiteUrl());
		}
		
		$base = $url;
		if ( $base{strlen($base)-1} != '/' ) $base = substr($base, 0, strrpos($base, '/'));
		$dom = str_get_html($html);
		$baseTags = $dom->find('base[href]');
		foreach ($baseTags as $baseTag){
			$base = $baseTag->href;
			if ( $base{strlen($base)-1} != '/' ) $base .= '/';
		}
		
		// Images
		$imgs = $dom->find('[src]');
		foreach ($imgs as $img){
			$href = SweteTools::absoluteUrl($img->src, $base);
			try {
				$img->src = $this->processResource($href, true);
			} catch (Exception $ex){
				error_log("Error while trying to process resource $href for an image source: ".$ex->getMessage());
				
			}
			//$this->processImgTag($img, $base);
		}
		
		
		
		$links = $dom->find('link[href]');
		foreach ($links as $link){
			$href = SweteTools::absoluteUrl($link->href, $base);
			try {
				$link->href = $this->processResource($href, true);
			} catch (Exception $ex){
				error_log("Error while trying to process resource $href for an stylesheet source: ".$ex->getMessage());
			}
		}
		
		
		
		return $dom;
		
		
	}
	
	/**
	 * @brief Adds a prefix to all images and links
	 * @param string $html
	 * @param string $prefix
	 * @return string html with prefix added to all images and links
	 */
	public function renderHtml($html, $prefix){
		$dom = str_get_html($html);
		
		
		// Images
		$imgs = $dom->find('[src]');
		foreach ($imgs as $img){
			$img->src = $prefix.$img->src;
			
		}
		
		
		
		$links = $dom->find('link[href]');
		foreach ($links as $link){
			$link->href = $prefix.$link->href;
			
		}
		
		return $dom->save();
		
	}
	
	
	
	
	/**
	 * @brief Converts URLs in CSS.
	 * @param string $css The CSS to convert.
	 * @return string $css The converted CSS.
	 */
	public function processCss($css, $url){
		$this->_currUrl = $url;
		$parts = explode('/', $url);
		array_pop($parts);
		$this->_currBase = implode('/', $parts).'/';
		return preg_replace_callback('/url\((["\']?)([^)]+)(["\']?)\)/', array($this, '_cssCallback'), $css);
	
	}
	
	/**
	 * @private
	 *
	 * @brief Callback used by preg_replace when converting CSS.
	 */
	public function _cssCallback($match){
		$absUrl = SweteTools::absoluteUrl($match[2], $this->_currBase);
		try {
			$hash = $this->processResource($absUrl, true);
			
		
			return 'url('.$match[1].$hash.$match[3].')';
		} catch (Exception $ex){
			return $match[0];
		}
	}
	
	
	
	
	
	/**
	 * @brief Converts URLs in CSS.
	 * @param string $css The CSS to convert.
	 * @return string $css The converted CSS.
	 */
	public function renderCss($css, $prefix){
		$old = $this->_currPrefix;
		$this->_currPrefix = $prefix;
		$out = preg_replace_callback('/url\((["\']?)([^)]+)(["\']?)\)/', array($this, '_cssRenderCallback'), $css);
		$this->_currPrefix = $old;
		return $out;
	
	}
	
	/**
	 * @private
	 *
	 * @brief Callback used by preg_replace when converting CSS.
	 */
	public function _cssRenderCallback($match){
		
		return 'url('.$match[1].$this->_currPrefix.$match[2].$match[3].')';
	}
	
}