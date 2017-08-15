<?php 


/*
googleTranslatePlugin class - uses the Google Translate 2.0 API to translate strings to other languages. 
This may run slowly, since it is sending the strings to a server to do the translations.

Usage: 
1) Set the Google API Key via setGoogleAPIKey()
2) add the strings you want translated via addSourceStrings() or addSourceString()
3) call getTranslations() to send the strings to google translate and return the results
		
Progress listeners implementing the googleTranslatePlugin_ProgressListener interface can be added via addProgressListener(). 
The setProgress() method will be called on startup before any translations have been sent, and also after each translation completes.
		

Author: Kevin Chow
Date: June 17, 2012

INTERFACE:
void setSourceLanguage(String $srcLang) - sets the language of the input strings
void setTargetLanguage(String $targLang)	- sets the desired output language
String getSourceLanguage(void)
String getTargetLanguage(void)
void setGoogleAPIKey($key) - sets the Google API Key to be used when translating the strings. A valid API Key MUST be supplied 
							 before calling getTranslations().
void addSourceString($string) - adds a string to be translated into the target language
void addSourceStrings($strings) - adds strings in an array to be translated into the target language
array(source=>target) getTranslations() - translates all of the user provided strings into the target language using the Google
										  Translate API and returns an array of source=>target strings.
addProgressListener(listener: googleTranslatePlugin_ProgressListener) - calls any progress listeners with progress updates.

*/

//A progress listener interface for the googleTranslatePlugin. 
//Users may implement the interface and add listeners by calling addProgressListener().
//The listener's setProgress() method will be called before any translations have been sent, and 
//once for every completed translation. the $message parameter will indicate the last source string that was translated
//and whether it was successful or not.
interface googleTranslatePlugin_ProgressListener
{
	public function setProgress($message, $numComplete, $numTotal);
};

class googleTranslatePlugin
{
	private $strings,
			$sourceLanguage,
			$targetLanguage,
			$translatedStrings,
			$googleAPIKey,
			$listeners,		//list of googleTranslatePlugin_ProgressListener that will be updated when translations are completed
			$maxCurlRequests;	//max number of parallel requests at a time to the google server.
			
	const endpoint = 'https://www.googleapis.com/language/translate/v2?';
	
	public function __construct($srcLang = NULL, $targLang = NULL, $strings = NULL, $googleAPIKey = NULL)
	{
		if($strings != NULL) $this->addSourceStrings($strings);
		$this->setTargetLanguage($targLang);
		$this->setSourceLanguage($srcLang);
		$this->setGoogleAPIKey($googleAPIKey);
		
		$this->strings = array();
		$this->translatedStrings = array();
		$this->listeners = array();	
		$this->maxCurlRequests = 20;	
	}
	
	public function setSourceLanguage($srcLang) {$this->sourceLanguage = $srcLang;}
	public function setTargetLanguage($targLang){$this->targetLanguage = $targLang;}
	
	public function getSourceLanguage() {return $this->sourceLanguage;}
	public function getTargetLanguage() {return $this->targetLanguage;}
	
	//
	public function setGoogleAPIKey($key)
	{
		$this->googleAPIKey = $key;
	}
	
	//add the strings to the list of strings to be translated
	public function addSourceStrings($strings)
	{
		foreach($strings as $s)
			addSourceString($s);
	}
	
	public function addSourceString($string) 
	{
		array_push($this->strings, $string);
	}
		
	//Sends all of the source strings to google translate, and updates any progress listeners with information about
	//which requests succeeded/failed, and how many have been completed so far.
	//Returns an array of source=>target translated strings
	public function getTranslations()
	{
		if($this->googleAPIKey == NULL)
			throw new Exception('googleTranslatePlugin::getTranslations() - A valid Google API Key must be provided before calling this function.');
			
		if($this->sourceLanguage == NULL)
			throw new Exception('googleTranslatePlugin::getTranslations() - The source language must be specified.');
			
		if($this->targetLanguage == NULL)
			throw new Exception('googleTranslatePlugin::getTranslations() - The target language must be specified.');
		
		$jobHandles = array();	//array of jobhandle=>string
		$mh = curl_multi_init();
		
		reset($this->strings);	//set array pointer to beginning
		$numStrings = count($this->strings);
		$numTranslated = 0;
		
		//call the progress listeners to tell them we are starting the translations
		foreach($this->listeners as $listener)
			$listener->setProgress("STARTING", 0, $numStrings);
		
		//start the first batch of requests	
		for($requestNum = 0; $requestNum < min($this->maxCurlRequests, $numStrings); $requestNum++)
		{		
			$s = each($this->strings);	//go through each string in strings
			$s = $s['value'];
			
			$job = $this->createCurlJob($s);	
			$jobHandles[$job] = $s;
			curl_multi_add_handle($mh, $job);
		}
				
		$active = null;
		
		//execute the handles
		do
		{
			while(($curlmResult = curl_multi_exec($mh, $active)) == CURLM_CALL_MULTI_PERFORM);
			if($curlmResult != CURLM_OK)
	            throw new Exception('CURL ERROR!');
	       ///     
			while($done = curl_multi_info_read($mh)) 
			{
	            //every time the loop gets here, it means we have completed another request. 
	            //However, this does not indicate if it was successful or not.
				$numTranslated++;
	            
				$job = $done['handle'];	//handle of the job
	            $s = $jobHandles[$job];	//original string
				$info = curl_getinfo($job);
				
	            if ($info['http_code'] == 200)  //curl request was successful
	            {
	            	$json = curl_multi_getcontent($job);
	                $this->translatedStrings[$s] = $this->extractResult($json);
	                
	                //call the progress listeners to tell them a string was just translated
	                foreach($this->listeners as $listener)
	                	$listener->setProgress("SUCCEEDED - " . $s, $numTranslated, $numStrings);
	
	                // start a new request (it's important to do this before removing the old one)
	                $newString = each($this->strings);	//get next string in strings
	                if($newString !== false)
	                {
						$newString = $newString['value'];		
						$newJob = $this->createCurlJob($newString);	
						$jobHandles[$newJob] = $newString;
						curl_multi_add_handle($mh, $newJob);
	                }
	                               
	                // remove the curl handle that just completed
	                curl_multi_remove_handle($mh, $job);
	                curl_close($job);
	            } 
	            else 
	            {
	                // request failed. Set the translated string to NULL to indicate failure.
	                $this->translatedStrings[$s] = NULL;
	                //call the progress listeners to tell them a request failed
	                foreach($this->listeners as $listener)
	                	$listener->setProgress("FAILED - " . $s, $numTranslated, $numStrings);
	            }
            
			} 
			///
		}
		while($active);
           
		//close the curl multi handle
		curl_multi_close($mh);
		
		return $this->translatedStrings;
	}
	
	public function addProgressListener($listener)
	{
		$this->listeners[spl_object_hash($listener)] = $listener;	
	}
	
	public function removeProgressListener($listener)
	{
		if(array_key_exists(spl_object_hash($listener), $this->listeners))
			unset ($this->listeners[spl_object_hash($listener)]);
	}
	
	//creates the curl job and sets the appropriate options/settings for it.
	//takes $str - the string to be translated.
	//returns the handle to the job
	private function createCurlJob($str)
	{
		$params = array();
		$params['key'] = $this->googleAPIKey;
		$params['q'] = $str;
		$params['source'] = $this->sourceLanguage;
		$params['target'] = $this->targetLanguage;

		$url = self::endpoint;// . http_build_query($params);
		
		$jobHandle = curl_init($url);
		curl_setopt($jobHandle, CURLOPT_HEADER, false);
		curl_setopt($jobHandle, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: GET'));//to tell the Translate API to treat the request as a GET, even though we are using POST
		curl_setopt($jobHandle, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($jobHandle, CURLOPT_POST, true);		//we are using POST, since the data limit is a lot higher than GET
		curl_setopt($jobHandle, CURLOPT_POSTFIELDS, http_build_query($params));
		//curl_setopt($jobHandle, CURLOPT_FAILONERROR, 0);
		curl_setopt($jobHandle, CURLOPT_SSL_VERIFYPEER, false);
		//curl_setopt($jobHandle, CURLOPT_SSL_VERIFYHOST, 0);
		
		return $jobHandle;
	}
	
	//google translate gives us back something like this: (JSON format)
	// { "data": { "translations": [ { "translatedText": "Bonjour" } ] } }
	//we just want the "Bonjour"
	private function extractResult($string)
	{
		$json = json_decode($string, true);
		
		if($json === NULL) return NULL;
		
		else if(array_key_exists('error', $json))
			throw new Exception('googleTranslatePlugin - Google translate error. Message: ' . $json['error']['errors'][0]['message'] . ' Reason: ' . $json['error']['errors'][0]['reason']);
		
		else if(array_key_exists('data', $json))
			return htmlspecialchars_decode($json["data"]["translations"][0]["translatedText"]);
			
		else throw new Exception('googleTranslatePlugin::extractResult() - data in unexpected format.');
	}
	
};
