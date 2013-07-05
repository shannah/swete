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
require_once 'inc/ProxyServer.php';
require_once 'inc/SweteSite.class.php';
require_once 'inc/ProxyWriter.php';
class actions_swete_handle_request{

	function handle($params){
		global $ORIG_POST, $ORIG_REQUEST, $ORIG_GET;
		$_GET = $ORIG_GET;
		$_POST = $ORIG_POST;
		$_REQUEST = $ORIG_REQUEST;
		@session_write_close();
		$app = Dataface_Application::getInstance();
		$query = $app->getQuery();
		//print_r($_SERVER);
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
		$url = df_absolute_url($url);
		//echo "The URL: ".$url;
		//echo "$url";exit;
		$site = SweteSite::loadSiteByUrl($url);
		
		if ( !$site and $url and $url{strlen($url)-1} != '/' ){
			$url .= '/';
			$site = SweteSite::loadSiteByUrl($url);
			if ( $site ){
				header('Location: '.$url);
				exit;
			
			}
		}
		
	
		if ( !$site ){
			die("[ERROR] No site found");
		}
		
		$server = new ProxyServer;
		
		if ( @$_POST['swete:input'] ){
		    if ( @$_POST['swete:key'] and @$_POST['swete:salt'] ){
		        if ( is_numeric($_POST['swete:salt']) ){
		            $salt = intval($_POST['swete:salt']);
		            if ( abs($salt-time()) < 3600 ){
		                $password = $site->getRecord()->val('webservice_secret_key');
		                if ( $password ){
		                    $key = sha1($_POST['swete:salt'].$password);
		                    //if ( $key === $_POST['swete:key'] ){
                                    if ( strcasecmp($key, $_POST['swete:key']) === 0 ){
		                        $server->inputContent = $_POST['swete:input'];
                                        if ( @$_POST['swete:content-type'] ){
                                            $server->inputContentType = $_POST['swete:content-type'];
                                        }
		                    } else {
		                        die("[ERROR] Incorrect Key");
		                    }
		                } else {
		                    die("[ERROR] No secret key set in the website settings.");
		                }
		            } else {
		                die("[ERROR] Invalid salt value");
		            }
		        } else {
		            die("[ERROR] Invalid salt value.  Salt must be an integer");
		        }
		    } else {
		        die("[ERROR] Both swete:key and swete:salt must be provided");
		    }
		    
		}
		
		
		//$server->buffer = true;
		$server->logTranslationMisses = true;
		$server->site = $site;
		
		if ( $site->getRecord()->val('log_requests') ){
			if ( isset($server->logger) ) $server->logger->requestLoggingEnabled = true;
		} else {
			if ( isset($server->logger) ) $server->logger->requestLoggingEnabled = false;
		}
		
		if ( $site->getRecord()->val('log_translation_misses') ){
			$server->logTranslationMisses = true;
			if ( isset($server->logger) ){
				// If we are logging translation misses we also need to log requests
				$server->logger->requestLoggingEnabled = true;
			}
		} else {
			$server->logTranslationMisses = false;
		}
		$server->URL = $url;
		
		// Deal with live cache
		// The first time a page is requested, it won't yet have a livecache
		// descriptor, so we needed to wait until we had loaded the 
		// site so we can calculate the unproxified url.
		// Then we will try to flush it again.
		$isPost = (strtolower($server->SERVER['REQUEST_METHOD']) === 'post');
		if ( class_exists('LiveCache') ){
			$server->liveCache = LiveCache::getCurrentPage();
			if ( !$isPost and !isset($server->liveCache->unproxifiedUrl) ){
				$server->liveCache->unproxifiedUrl = $server->site->getProxyWriter()->unproxifyUrl($server->URL);
				$server->liveCache->logger = $server->logger;
				$server->liveCache->flush();
				
			}
			
		}
		
		// 
		
		
		$server->handleRequest();
		//print_r($server->headerBuffer);
		
		
		
		
		
		//$site = SweteSite::loadSiteByUrl(
		
		
	
	}
}