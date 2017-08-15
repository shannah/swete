<?php 

/*	XLIFFReader class - an event based reader for XLIFF files. Users should implement an XLIFFListener class to listen for events, 
 * 	add it using addXliffListener(), then call parse() to begin parsing the XLIFF file. The appropriate XLIFFListener callback functions 
 * 	will automatically be called on an event.
 *  Author: Kevin Chow
 *  Date: June 26, 2012
 */

include ("XLIFFListener.php");

class XLIFFReader
{
	private $listeners,		//array of XLIFFListener. Their callback functions will be called during parsing of the XLIFF file.
			$parser,		//the SAX xml parser used to read the xml file	
			$readChunkSize,	//this reader loads the file bit by bit into memory. Specifies how large the read chunks are.
			$source,
			$target,
			$isInSourceTag,	//boolean. determines if we are inside a <source> tag.
			$isInTargetTag,	//boolean. determines if we are inside a <target> tag.
			$lastAtts = null;
	
	public function __construct()
	{
		$this->listeners = array();
		$this->parser = NULL;
		$this->readChunkSize = 10000;	//might change later?
		$this->isInSourceTag = false;
		$this->isInTargetTag = false;
	}
	
	public function addXliffListener(&$XliffListener)
	{		
		$this->listeners[spl_object_hash($XliffListener)] = $XliffListener;
	}

	public function removeXliffListener(&$XliffListener)
	{
		if(array_key_exists(spl_object_hash($XliffListener), $this->listeners))
			unset ($this->listeners[spl_object_hash($XliffListener)]);
	}
	
	//parses the xml file. Calls the appropriate user given callback functions upon reading information from the XML.
	//Available callback functions are declared in the XLIFFListener class
	public function parse($file)
	{
		//if there are no listeners, there is no point in reading the file.
		//if(count($this->listeners) == 0) 
		//	return;
		
		//try to open $file
		$xliffFileHandle = fopen($file, "rb");
		if(!$xliffFileHandle)
			throw new Exception("XLIFFReader::parse() - Failed to open " . $file . "<br>");
		
		//set options for the xml parser
		$this->parser = xml_parser_create();
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, "onElementStart", "onElementEnd");
		xml_set_character_data_handler ($this->parser , "onCharData");
		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false); 
			
		//EVENT: onStart
		$startEvent = new XLIFFEvent_Start();
		
		foreach($this->listeners as $listener)
			$listener->onStart($startEvent);
		
		//parse the XLIFF
	 	while (($data = fread($xliffFileHandle, $this->readChunkSize))) 
	 	{ 
			if (!xml_parse($this->parser, $data, feof($xliffFileHandle))) 
				throw new Exception("XLIFFReader::parse() - Error on line " . xml_get_current_line_number($this->parser));
	 	}
		
		//EVENT: onEnd
		$endEvent = new XLIFFEvent_End();
		
		foreach($this->listeners as $listener)
			$listener->onEnd($endEvent);
	}
	
	//callbacks for the SAX xml functions.
	private function onElementStart($parser , $name , $attributes )
	{
		//opening tags
		if($name === "file")
		{		
			//EVENT: onStartFile
			$startFileEvent = new XLIFFEvent_StartFile();
			$startFileEvent->file = $attributes["original"];
			$startFileEvent->sourceLanguage = $attributes["source-language"];
			$startFileEvent->targetLanguage = $attributes["target-language"];
			$startFileEvent->datatype = $attributes["datatype"];
			
			foreach($this->listeners as $listener)
				$listener->onStartFile($startFileEvent);
		}
		else if($name === "trans-unit")
		{
			$this->transID = $attributes["id"];		
		}
		else if($name == "source")
		{
			$this->isInSourceTag = true;
			$this->source = "";
		}
		else if($name == "target")
		{
			$this->isInTargetTag = true;
			$this->target = "";
		}
		else if ($name == "g")
		{
		    $str = '';
		    if ( @$attributes['ctype'] == 'x-variable' ){
		        $str .= '<v id="'.$attributes['id'].'">';
		    } else {
		        $str .= '<g id="'.$attributes['id'].'">';
		    }
		    if ( $this->isInSourceTag ){
		        $this->source .= $str;
		    } else if ($this->isInTargetTag ){
		        $this->target .= $str;
		    }
		}
		else if ($name == "x")
		{
		    $str = '<x id="'.$attributes['id'].'"/>';
		   
		    if ( $this->isInSourceTag ){
		        $this->source .= $str;
		    } else if ($this->isInTargetTag ){
		        $this->target .= $str;
		    }
		}
		$this->lastAtts = $attributes;
	
	}
	
	private function onElementEnd($parser , $name)
	{
		if($name === "trans-unit")
		{
			if($this->transID !== NULL)
			{
				//EVENT: onTranslation
				$translationEvent = new XLIFFEvent_Translation();
				$translationEvent->source = $this->source;
				$translationEvent->target = $this->target;
				
				foreach($this->listeners as $listener)
					$listener->onTranslation($translationEvent);
					
				$this->transID = NULL;
			}
			else 	//XLIFF format error
				throw new Exception("XLIFFReader:parse() - bad XLIFF file!");
		}
		else if($name === "file")
		{
			//EVENT: onEndFile
			$endFileEvent = new XLIFFEvent_EndFile();
			
			foreach($this->listeners as $listener)
				$listener->onEndFile($endFileEvent);
		}
		else if($name == "source")
		{
			$this->isInSourceTag = false;
		}
		else if($name == "target")
		{
			$this->isInTargetTag = false;
		}
		else if ($name == "g")
		{
		    $attributes = $this->lastAtts;
		    if ( is_array($attributes) ){
                $str = '';
                if ( @$attributes['ctype'] == 'x-variable' ){
                    $str .= '</v>';
                } else {
                    $str .= '</g>';
                }
                if ( $this->isInSourceTag ){
                    $this->source .= $str;
                } else if ($this->isInTargetTag ){
                    $this->target .= $str;
                }
            }
		}
		
	}
	
	//character data handler
	private function onCharData($parser, $data)
	{
		if($this->isInSourceTag)
		{
			$this->source .= $data;		//sometimes we will get the data back bit by bit if there are special chars in there, so we need to reassemble them.
		}
		else if($this->isInTargetTag)
		{
			$this->target .= $data;		//sometimes we will get the data back bit by bit if there are special chars in there, so we need to reassemble them.
		}	
	}

};

?>