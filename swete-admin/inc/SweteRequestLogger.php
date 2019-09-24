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
 * @brief A class that logs an HTTP request in the ProxyServer. It keeps
 * track of all of the pertinent details of the request including:
 *
 * - The URL of the page requested.
 * - The Request method, headers, and Post vars.
 * - The Website ID
 * - The Webpage ID
 * - A reason the webpage was not used (if it wasn't used)
 * - Webpage Version ID
 * - Information about any background requests made to the source site including:
 *		- Request method
 *		- URL
 *		- Headers
 *		- Response body
 *		- Response content-type
 *		- Response status code
 * - Whether live translation was used
 * - The translation memory id used to translate the request.
 * - The output content
 * - The output response headers.
 * - The number of live translation hits and misses.
 *
 * This logger is primarily used by the ProxyServer class when to log the requests
 * as they come in. 
 */
class SweteRequestLogger {

    public $blockId = null;
    public $blockUrl = null;

	/**
	 * @type boolean
	 */
	public $requestLoggingEnabled = true;
	
	/**
	 * @type boolean
	 */
	public $saveBodies = false;
	
	/**
	 * @type boolean
	 */
	public $onlyLogMisses = false;


	/**
	 * @type Dataface_Record
	 */
	private $record;
	
	
	/**
	 * @type string
	 */
	public $proxyRequestUrl;
	
	/**
	 * @type string
	 */
	public $proxyRequestMethod;
	
	/**
	 * @type array
	 */
	public $proxyRequestHeaders;
	
	/**
	 * @type array
	 */
	public $proxyRequestPostVars;
	
	
	/**
	 * @type int
	 */
	public $websiteId;
	
	/**
	 * @type int
	 */
	public $webpageId;
	
	/**
	 * @type string
	 */
	public $webpageNotUsedReason;
	
	/**
	 * @type int
	 */
	public $webpageVersionId;
	
	/**
	 * @type string
	 */
	public $requestMethod;
	
	/**
	 * @type string
	 */
	public $requestUrl;
	
	/**
	 * @type array
	 */
	public $requestHeaders;
	
	/**
	 * @type array
	 */
	public $requestPostVars;
	
	/**
	 * @type array
	 */
	public $responseHeaders;
	
	/**
	 * @type array
	 */
	public $responseBody;
	
	/**
	 * @type string
	 */
	public $requestDate;
	
	/**
	 * @type string
	 */
	public $responseContentType;
	
	/**
	 * @type int
	 */
	public $responseStatusCode;
	
	/**
	 * @type boolean
	 */
	public $liveTranslationEnabled;
	
	/**
	 * @type int
	 */
	public $liveTranslationMinStatus;
	
	/**
	 * @type int
	 */
	public $translationMemoryId;
	
	/**
	 * @type string
	 */
	public $outputContent;
	
	/**
	 * @type array
	 */
	public $outputResponseHeaders;

	/**
	 * @type int
	 */
	public $liveTranslationHits=0;
	
	/**
	 * @type int
	 */
	public $liveTranslationMisses=0;
	
	/**
	 * @type array
	 */
	public $missedStrings;
	
	/**
	 * @type array
	 */
	public $allStrings;
	
	/**
	 * @type array
	 */
	public $allTranslations;
	
	/**
	 * @brief Returns the record for this particular log entry. 
	 *
	 * @returns Dataface_Record a record of the @e http_request_log table.
	 */
	public function getRecord(){
	    if (!isset($this->record)) {
	        $this->record = new Dataface_Record('http_request_log', array());
	    }
		return $this->record;
	}
	
	private static function array_md5($array) {
	    if (!$array) return md5('');
	    asort($array);
	    return md5(implode('', $array));
	}
	
	/**
	 * @brief Saves the request to the database (the http_request_log table).
	 */
	public function save(){
		if ( !$this->requestLoggingEnabled ) return;
		if ( $this->onlyLogMisses and $this->liveTranslationMisses == 0  ) return;
		if ( !isset($this->record) ){
			$this->record = new Dataface_Record('http_request_log', array());
		}
		$vals = array(
			'proxy_request_url' => $this->proxyRequestUrl,
			'proxy_request_method' => $this->proxyRequestMethod,
			'proxy_request_headers' => $this->proxyRequestHeaders,
			'proxy_request_post_vars' => $this->proxyRequestPostVars,
			'website_id' => $this->websiteId,
			'webpage_id' => $this->webpageId,
			'webpage_version_id' => $this->webpageVersionId,
			'request_method' => $this->requestMethod,
			'request_url' => $this->requestUrl,
			'request_headers' => $this->requestHeaders,
			'request_post_vars' => $this->requestPostVars,
			'response_headers' => $this->responseHeaders,
			'request_date' => $this->requestDate,
			'response_content_type' => $this->responseContentType,
			'response_status_code' => $this->responseStatusCode,
			'live_translation_enabled' => $this->liveTranslationEnabled,
			'live_translation_min_status' => $this->liveTranslationMinStatus,
			'translation_memory_id' => $this->translationMemoryId,
			'live_translation_hits' => $this->liveTranslationHits,
			'live_translation_misses' => $this->liveTranslationMisses,
			'output_response_headers' => $this->outputResponseHeaders
		);
		
		if ( $this->saveBodies ){
			$vals['response_body'] = $this->responseBody;
			$vals['output_content'] = $this->outputContent;
			
		}
		
		$this->record->setValues($vals);
		$res = $this->record->save();
		if ( PEAR::isError($res) ){
			throw new Exception($res->getMessage(), $res->getCode());
		}
		if ($this->liveTranslationEnabled and strtolower($this->requestMethod) == 'get') {
            $page = df_get_record('webpage_status', array('page_url' => '='.$this->proxyRequestUrl));
            if (!$page) {
                $page = new Dataface_Record('webpage_status', array());
                $page->setValue('page_url', $this->proxyRequestUrl);
            }
            
            $prevResponseBodyChecksum = $page->val('response_body_checksum');
            $prevOutputContentChecksum = $page->val('output_content_checksum');
            //$prevStringsChecksum = $page->val('strings_checksum');
            $prevTranslationsChecksum = $page->val('translations_checksum');
            
            $page->setValues(array(
                'last_checked' => $this->requestDate,
                'num_translation_misses' => $this->liveTranslationMisses,
                'response_status_code' => $this->responseStatusCode,
                'response_content_type' => $this->responseContentType,
                'website_id' => $this->websiteId,
                'response_body_checksum' => md5($this->responseBody),
                'output_content_checksum' => md5($this->outputContent),
                'translations_checksum' => self::array_md5($this->allTranslations)
                
            ));
            if ($prevTranslationsChecksum != $page->val('translations_checksum')) {
                $page->setValue('last_translations_change', $this->requestDate);
            }
            if ($prevResponseBodyChecksum != $page->val('response_body_checksum')) {
                $page->setValue('last_response_body_change', $this->requestDate);
            }
            if ($prevOutputContentChecksum != $page->val('output_content_checksum')) {
                $page->setValue('last_output_content_change',  $this->requestDate);     
            }
            if ($this->missedStrings) {
                $page->setValue('missed_strings', json_encode($this->missedStrings));
            }
            if ($this->allStrings) {
                $page->setValue('strings', json_encode($this->allStrings));
            }
            if ($this->allTranslations) {
                $page->setValue('translations', json_encode($this->allTranslations));
            }
            $page->save();
            if (isset($this->blockId) and isset($this->blockUrl)) {
                $blockPage = df_get_record('webpage_status', array('page_url' => '='.$this->blockUrl));
                if (!$blockPage) {
                    $blockPage = new Dataface_Record('webpage_status', array());
                    $blockPage->setValue('page_url', $this->blockUrl);
                }
                $statusId = $blockPage->val('webpage_status_id');
                $blockPage->setValues($page->vals());
                $blockPage->setValue('webpage_status_id', $statusId);
                $blockPage->setValue('page_url', $this->blockUrl);
                $blockPage->save();
            }
        }
		
	}
}