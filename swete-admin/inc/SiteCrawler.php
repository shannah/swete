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
 * @brief Crawls a website from a specified starting point and saves information
 * about the various nodes or pages of the site so that they can be loaded later.
 *
 * It produces a tree of nodes with information about the webpage that resides in
 * the corresponding website structure.
 *
 * <h3>Nodes</h3>
 *
 * This crawler works by building up a tree of nodes (stdClass objects) with 
 * some specific properties.  Each node may contains at least the following 
 * properties:
 *
 * - httpStatus  (e.g. 200, 404, or 302)
 * - contentType (e.g. text/html)
 * - type (will be 'page' for pages or null for other types of nodes)
 * - url (The URL of the node)
 * - content (The HTML content of the node)
 *
 */
class SiteCrawler {
	
	/**
	 * @type SweteSite
	 */
	public $site;
	
	/**
	 * @type string
	 * @brief The URL of the first page to crawl.  
	 */
	public $startingPoint;
	
	/**
	 * @type int
	 * @brief The depth of the crawl (this is how many links from the 
	 * startng point should be traversed.
	 */
	public $depth = 4;
	
	/**
	 * @brief Whether to load page content during crawl
	 * @type boolean
	 */
	public $loadContent = false;
	
	/**
	 * @type stdClass
	 * @brief The root node of the data structure.
	 */
	public $root = null;
	
	
	
	private $delegate = null;
	
	/**
	 * @brief Initializes the crawler with an empty root node
	 */
	public function __construct(){
		$this->root = new stdClass;
		
	}
	
	public function setDelegate(SiteCrawlerDelegate $delegate = null){
		$this->delegate = $delegate;
	}
	
	/**
	 * @brief Adds a node at the specified path. If the node exists already then the specified
	 * properties are added to the node.  If the node doesn't yet exist, then all parent nodes
	 * are created, and this node is created with the specified properties.
	 *
	 * @param string $path The path of the node to add.
	 * @param array $properties The properties to add.
	 * @returns stdClass The node that was added (or already existed).
	 * @see addNodeAtAbsoluteUrl()
	 * @see addNodeAtUrl()
	 */
	public function addNodeAtPath($path, $properties = array()){
		$fragment = '';
		$queryString = '';
		if ( strpos($path,'#') !== false ){
			list($path,$fragment) = explode('#', $path);
		}
		if ( strpos($path, '?') !== false ){
			list($path, $queryString) = explode('?', $path);
		}
		
		if ( $queryString ){
			$properties['queryStrings[]'] = $queryString;
		}
		$parts = explode('/', $path);
		
		$currNode = $this->root;
		$breadCrumbs = array();
		while ( !empty($parts) ){
			if ( !isset($currNode->children) ){
				$currNode->children = array();
			}
			$part = array_shift($parts);
			$breadCrumbs[] = $part;
			if ( !isset($currNode->children[$part]) ){
				$currNode->children[$part] = new stdClass;
				$this->attrs($currNode->children[$part], array('path' => implode('/', $breadCrumbs)));
				
			}
			if ( empty($parts) ){
				$this->attrs($currNode->children[$part], $properties);
			}
			$currNode = $currNode->children[$part];
		}
		return $currNode;
	}
	
	/**
	 * @brief Adds the attributes of the given properties array to the specified
	 * node.
	 * @param stdClass The node to add the properties to.
	 * @param array $properties The properties to add.
	 * @returns void
	 */
	public function attrs(stdClass $node, array $properties){
		foreach ($properties as $k=>$v){
			if ( preg_match('#^(.*)\[\]$#', $k, $matches) ){
				$k = $matches[1];
				if ( !isset($node->{$k}) ) $node->{$k} = array();
				else if ( !is_array($node->{$k}) ) $node->{$k} = array($node->{$k});
				$a =&$node->{$k};
				$a[] = $v;
				unset($a);
			} else {
				$node->{$k} = $v;
			}
		}
	}
	
	/**
	 * @brief Adds a node at an absolute URL.  This URL is first normalized
	 * into a path relative to the root of the site, then it is processed by the addNodeAtPath()
	 * method.
	 * @param string $url The URL of the node to add.  This should be a full URL including http://
	 * @param array $properties The properties to add.
	 * @returns stdClass The node that was added (or already existed).
	 * @throws Exception If no site has been set for this SiteCrawler object.
	 * @see addNodeAtUrl()
	 * @see addNodeAtPath()
	 *
	 */
	public function addNodeAtAbsoluteUrl($url, $properties = array()){
		if ( !$this->site ) throw new Exception("Site not set");
		$path = $this->site->getProxyWriter()->stripBasePath($url, $this->site->getSiteUrl());
			// That will throw an exception if the url is not inside the 
			// src url.
		
		return $this->addNodeAtPath($path, $properties);
	
	}
	/**
	 * @brief Adds a node at a URL that may or may not already be absolute.
	 * @param string $url The URL of the page to add (may or may not be absolute).
	 * @param string $baseUrl The base URL of the site.
	 * @param array $properties The properties to add to the node.
	 * @returns stdClass The node that was added (or already existed).
	 */
	public function addNodeAtUrl($url, $baseUrl, $properties = array()){
		$url = SweteTools::absoluteUrl($url, $baseUrl);
		return $this->addNodeAtAbsoluteUrl($url, $properties);
	}
	
	
	/**
	 * @brief Loads a webpage and processes it.   This will glean the content-type,
	 * response code, and possibly the page content of the webpage and attach
	 * this information to a node and add the node to the tree.
	 *
	 * @param string $url The URL of the webpage to process.
	 * @param boolean $followRedirects If true then this will follow redirects if the server
	 * 	returns a redirect response code (e.g. 302)
	 * @param array $locations An array of locations that have already been visited.  The 
	 * 	keys are URLs and the values are booleans.  This just makes it easier to look
	 * up existence.  This array is used to prevent infinite loops.
	 * @returns boolean True if the page was processed successfully and the node added.
	 * @throws Exception if there was a problem processing the page. This will include
	 * if a 400 error is returned or some other common occurrence so this exception MUST
	 * be caught and handled or you'll regularly get uncaught exception errors.
	 */
	public function processPage($url, $followRedirects = false, $locations = array()){
		error_log('Processing '.$url);
		require_once 'inc/ProxyClient.php';
		$client = new ProxyClient;
		$client->clear();
		$client->URL = $url;
		$found = false;
		//$locations = array();
		$client->noBody = true;  // only find out what type of content it is
		$client->process();
		$this->addNodeAtAbsoluteUrl($url, array(
			'httpStatus'=> $client->status['http_code'],
			'contentType' => $client->contentType
		));
		if ( $client->status['http_code'] == 200 ){
			// We have success
			if ( preg_match('#xml|html|xhtml#', $client->contentType) ){
			
				$client->noBody = false;  // only find out what type of content it is
				$client->process();
				$this->processHtml($client->content, $client->URL);
				return true;
			} else {
				throw new Exception("Failed to process page $url because it is not a parsable content type: ".$client->contentType);
				
			}
			
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
			return $this->processPage($location, $followRedirects, $locations);
		} else {
			throw new Exception("Failed to process page $url.  Received HTTP response code: ".$client->status['http_code']);
			
		}
		
	
	}
	
	/**
	 * @brief Processes the HTML found at a specified URL.  It goes through all of the 
	 * links and adds them to the queue to be processed in later rounds.
	 * @param string $html The HTML to be processed.
	 * @param string $url The URL of the webpage that stored the html in $html
	 * @returns void
	 *
	 */
	public function processHtml($html, $url){
		require_once 'lib/simple_html_dom.php';
		if ( strpos($url, $this->site->getSiteUrl()) !== 0 ){
			throw new Exception("HTML could not be processed because the url $url lies outside the target site: ".$this->site->getSiteUrl());
		}
		
		$base = $url;
		if ( $base{strlen($base)-1} != '/' ) $base = substr($base, 0, strrpos($base, '/'));
		$dom = str_get_html($html);
		$baseTags = $dom->find('base[href]');
		foreach ($baseTags as $baseTag){
			$base = $baseTag->href;
			if ( $base{strlen($base)-1} != '/' ) $base .= '/';
		}
		
		// Now that we have our base tag, we can begin to fire away.
		
		$node = $this->addNodeAtAbsoluteUrl($url, array(
			'type' => 'page',
			'url' => $url
		));
		
		if ( $this->loadContent ){
			$this->addNodeAtAbsoluteUrl($url, array(
				'content' => $html
			));
		}
		if ( isset($this->delegate) and method_exists($this->delegate, 'loadContent') ){
			$this->delegate->loadContent($node, $html);	
		}
		
		// Now let's harvest the links
		$links = $dom->find('a[href]');
		foreach ($links as $link){
			
			$href = SweteTools::absoluteUrl($link->href, $base);
			if ( strpos($href, $this->site->getSiteUrl()) !== 0 ){
				// this link doesn't belong in our tree
				continue;
			} else {
				$this->addNodeAtAbsoluteUrl($href);
			}
		}
		
		
	}
	
	/**
	 * @brief Initiates the crawl to the specified dept.
	 */
	public function crawl(){
		
		$depthRemaining = $this->depth;
		$urls = array($this->startingPoint);
		while ( $depthRemaining > 0 ){
			
			foreach ($urls as $url){
				
				try {
					$this->processPage($url, true);
				} catch (Exception $ex){
					$this->addNodeAtAbsoluteUrl($url, array('error'=>$ex->getMessage()));
					
					error_log("Failed to process page: ".$url.". ".$ex->getMessage());
				}
			}
			
			$urls = array();
			foreach ($this->getUnprocessedNodes() as $node){
				if ( isset($node->url) ){
					$urls[] = $node->url;
				} else if ( isset($node->path) ){
					$urls[] = $this->site->getSiteUrl().$node->path;
				}
			}
			
			$depthRemaining--;
			
		}
	
	}
	
	/**
	 * @brief Gets the unprocessed nodes below the specified root node.  
	 * An unprocessed node generally corresponds to a webpage that was run across
	 * as a link in another webpage but hasn't yet been processed itself.
	 * @param stdClass $root The root of the subtree to search.  If this is 
	 * left null, then the root node of the crawler will be used as this local root.
	 * @returns array($node:stdClass) Array of nodes that haven't been processed yet.
	 *
	 */
	public function getUnprocessedNodes($root=null){
		if ( !isset($root) ) $root = $this->root;
		$out = array();
		$this->_getUnprocessedNodes($root, $out);
		return $out;
	}
	
	private function _getUnprocessedNodes(stdClass $root, &$out){
		if ( !isset($root->type) and !isset($root->error) ) $out[] = $root;
		if ( isset($root->children) ){
			foreach ($root->children as $child){
				$this->_getUnprocessedNodes($child, $out);
			}
		}
	}
	
	/**
	 * @brief Gets array of nodes that correspond to webpages.
	 * @param stdClass $root The root of the subtree to search.  If this is 
	 * left null, then the root node of the crawler will be used as this local root.
	 * @returns array($node:stdClass) Array of nodes that represent pages.
	 */
	public function getPageNodes($root=null){
		if ( !isset($root) ) $root = $this->root;
		$out = array();
		$this->_getPageNodes($root, $out);
		return $out;
	
	}
	
	private function _getPageNodes(stdClass $root, &$out){
		if ( !isset($root->type) and $root->type == 'page' ) $out[] = $root;
		if ( isset($root->children) ){
			foreach ($root->children as $child){
				$this->_getPageNodes($child, $out);
			}
		}
	}
	
	/**
	 * @brief Gets all nodes in an array.
	 * @param stdClass $root The root of the subtree to search.  If this is 
	 * left null, then the root node of the crawler will be used as this local root.
	 * @returns array($node:stdClass) Array of nodes
	 */
	public function getAllNodes($root=null){
		if ( !isset($root) ) $root = $this->root;
		$out = array();
		$this->_getAllNodes($root, $out);
		return $out;
	
	}
	
	private function _getAllNodes(stdClass $root, &$out){
		$out[] = $root;
		if ( isset($root->children) ){
			foreach ($root->children as $child){
				$this->_getAllNodes($child, $out);
			}
		}
	}
	
	
	
	
	
	
}

interface SiteCrawlerDelegate {
	/**
	 * @brief A callback function that is called when content can be loaded 
	 * into a node.  This allows you to process the content yourself.
	 */
	public function loadContent(stdClass $node, $content);
}