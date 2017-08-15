<?php

/* XLIFFWriter class
 * Author: Kevin Chow
 * Date: June 1, 2012
 */

//INTERFACE:
/*	public void setSourceLanguage(String $lang);
	public String getSourceLanguage();
	public void setTargetLanguage(String $lang);
	public String getTargetLanguage();
	public void addTranslation(String $source, String $target);
	public void addTranslations(array(String=>String) $translations);
	public void setFile(String $filename, String $datatype);
	public void flush(Resource $handle);
	public void write(Resource $handle);
*/

class XLIFFWriter
{
	private 
		$srcLang, 	//source language
		$targLang, 	//target language
		$currentFile,	//current source file for the translations
		$fileDatatype,	//the datatype of the current file
		$writer,	//handle to the XMLWriter class for writing to the XLIFF file
		$entryNum,	//integer, incremented for each new translation added
		$rawSources = false,
		$rawTranslations = false,
		$wroteFileHeader;
		
	//constructor.
	//creates a new instance of an XMLWriter, and writes the <xliff> header tag.
	//parameters: sourceLanguage : String, targetLanguage : String
	function __construct($sourceLanguage = "en", $targetLanguage = "en")
	{
	       assert($sourceLanguage != NULL && $this->isValidISOLanguageCode($sourceLanguage)); 	//check to make sure that the language code is 2 characters long
	       assert($targetLanguage != NULL && $this->isValidISOLanguageCode($targetLanguage)); 
	       $this->setSourceLanguage($sourceLanguage);
	       $this->setTargetLanguage($targetLanguage);
	       
	       //create the XMLWriter for writing to the XML file
	       $this->writer = NULL;
	       $this->writer = new XMLWriter;
	       if($this->writer == NULL) throw new Exception('XLIFFWriter::__construct() - Failed to create the XMLWriter instance.');
	       $this->writer->openMemory();
           $this->writer->setIndent( true );
           
           $this->writeHeader();
           
           $this->currentFile = null;
           $this->wroteFileHeader = false;
           $this->entryNum = 1;
	}
	
	function rawSources($raw=null){
	    if ( isset($raw) ){
	        $this->rawSources = $raw;
	        return $this;
	    }
	    return $this->rawSources;
	}
	
	function rawTranslations($raw=null){
	    if ( isset($raw) ){
	        $this->rawTranslations = $raw;
	        return $this;
	    }
	    return $this->rawTranslations;
	}
	
	//getters & setters
	public function getSourceLanguage() {return $this->srcLang;}
	public function getTargetLanguage() {return $this->targLang;}
	
	public function setSourceLanguage($lang) 
	{
		//Check to see if $lang is a valid ISO language code
		if($this->isValidISOLanguageCode($lang))
			$this->srcLang = $lang;
		else throw new Exception('XLIFFWriter::setSourceLanguage() - invalid language code.');
	}
	
	public function setTargetLanguage($lang) 
	{ 
		//Check to see if $lang is a valid ISO language code
		if($this->isValidISOLanguageCode($lang))
			$this->targLang = $lang;
		else throw new Exception('XLIFFWriter::setSourceLanguage() - invalid language code.'); 
	}
	
	
	
	//Adds a translation to the current file. This function must be called after 
	//setFile().
	public function addTranslation($source, $target)
	{
		if($this->currentFile == NULL)
			throw new Exception('XLIFFWriter::addTranslation() - setFile() must be called before addTranslation().');
		
		//the file header is written when the first translation is added.
		//this lets the user set the languages/file in any order, and it won't screw up.
		if($this->wroteFileHeader == false)
			$this->writeFileHeader();
			
		$this->writer->startElement( 'trans-unit' );
		$this->writer->writeAttribute( 'id', $this->entryNum );
		if ( $this->rawSources ){
		    $this->writer->startElement('source');
		    $this->writer->writeRaw($source);
		    $this->writer->endElement();
		} else {
            //example: <source xml:lang="en">Hello</source>
            $this->writer->writeElement('source', $source);	
        }	
        
        if ( $this->rawTranslations ){
            $this->writer->startElement('target');
            $this->writer->writeRaw($target);
            $this->writer->endElement();
        } else {
            //example: <target xml:lang="fr">Bonjour</target>
            $this->writer->writeElement('target', $target);
        }
        
        $this->writer->endElement();	//</trans-unit>
        
        //additional translations will have the ID attribute incrementing
        $this->entryNum++;
	}
	
	//parameters: $translations: an array of $source=>$target strings to add to the translations
	public function addTranslations($translations)
	{
		foreach($translations as $source=>$target)
		{
			$this->addTranslation($source, $target);			
		}	
	}
	
	//sets the original file that the translations came from. Places opening XML tags for <file>
	//and <body>. Also creates an empty <header> element.
	//If datatype is not specified, it defaults to 'html'
	public function setFile($filename, $datatype = null)
	{
		if($this->currentFile != $filename)
		{
			if($this->currentFile != NULL) 
				$this->endFile();
			
			$this->currentFile = $filename;
			$this->fileDatatype = $datatype;
			$this->wroteFileHeader = false;
		}	
	}
	
	//flushes the current contents to the handle
	//frees the memory of the XMLWriter, and appends the generated XML to the file
	public function flush($handle)
	{
		fseek($handle, 0, SEEK_END);
		$data = $this->writer->outputMemory( true );
		fwrite($handle, $data);
	}
	
	//ends the XLIFF file and writes the rest of the XML to the file
	public function write($handle)
	{
		$this->endXLIFF();
		fseek($handle, 0, SEEK_END);
		fwrite($handle, $this->writer->outputMemory( true ));
	}

	//HELPER FUNCTIONS///////////////
	
	//checks if the input is a valid ISO language code (pretty much if it has 2 chars)
	protected function isValidISOLanguageCode($lang)
	{
		return (strlen($lang) == 2);
	}
	
	//writes the file header with the source and target languages.
	protected function writeFileHeader()
	{
		//Specify <file> attributes
		$this->writer->startElement( 'file' ); // level 1
		$this->writer->writeAttribute('original', $this->currentFile);
        $this->writer->writeAttribute('source-language', $this->srcLang);
        $this->writer->writeAttribute('target-language', $this->targLang);
        //$this->writer->writeAttribute('tool', 'SWeTe');
        $this->writer->writeAttribute( 'datatype', $this->fileDatatype == null ? 'html' : $this->fileDatatype );
        
        //create an empty <header> element
        $this->writer->startElement("header");	//not sure of the purpose of this?
        $this->writer->endElement();
        
        //begin <body> section for translated strings (<trans-unit>s)
        $this->writer->startElement( 'body' );	//level 2
        
        $this->entryNum = 1;
        $this->wroteFileHeader = true;
	}
	
	//begins the <xliff> element
	protected function writeHeader()
	{
		$this->writer->startDocument('1.0', 'utf-8');
		$this->writer->startElement('xliff');	//level 0
		$this->writer->writeAttribute('version', '1.2');	
		$this->writer->writeAttribute( 'xmlns', 'urn:oasis:names:tc:xliff:document:1.2');	
	}
	
	//ends the current file and places closing XML tags
	protected function endFile()
	{
		if($this->wroteFileHeader)
		{
			$this->writer->endElement();	//</body>
			$this->writer->endElement(); 	// </file>
		}
	}
	
	//ends the current <file> element and the <xliff> element
	protected function endXLIFF()
	{
		$this->endFile();
		$this->writer->endElement(); // </xliff>
		$this->writer->endDocument();
	}
};








