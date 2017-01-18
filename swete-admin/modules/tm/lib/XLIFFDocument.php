
<?php 
/*XLIFF Document class for reading/writing XLIFF files
* Author: Kevin Chow
* Date: June 1, 2012
*/
/* INTERFACE:
function getTranslations(file:String=null):array(src:String=>target:String)
function addTranslations(file:String, translations:array(src:String=>target:String) ): void
function getSourceLanguage()
function getTargetLanguage():String
function setSourceLanguage(src:String):void
function setTargetLanguage(target:String):void
static function read(file:String): XLIFFDocument
function write(file:String): void
*/

include('XLIFFWriter.php');

class XLIFFDocument
{
	private $translations,		//array of XLIFFDocument_TranslationFile to store the files and translations
			$defSourceLanguage, $defTargetLanguage;	//default source/target languages when not specified for individual files
	
	//Users of this class may create instances through the static method read() or through the constructor, with 0 parameters.
	public function __construct()
	{
		$this->translations = array();
		$this->defSourceLanguage = NULL;
		$this->defTargetLanguage = NULL;
	}
	
	//sets the source language for the file specified. If no file is specified, it will set the default source language for all new files.
	//The default source language is set to the source language of the first file entered.
	public function setSourceLanguage($src, $file = NULL)
	{
		if($file == NULL)
			$this->defSourceLanguage = $src;
		else 
		{
			$this->createIndexIfNotExist($file);	//if the file doesn't exist, create a new index.
			$this->translations[$file]->sourceLanguage = $src;
			//if the default source language is not set, set it to the first file's source language (can be changed later)
			if($this->defSourceLanguage == NULL)
				$this->defSourceLanguage = $src;
		}
	}
	
	//sets the target language for the file specified. If no file is specified, it will set the default target language for all new files.
	//The default target language is set to the target language of the first file entered.
	public function setTargetLanguage($target, $file = NULL)
	{
		if($file == NULL) 
			$this->defTargetLanguage = $target;
		else
		{ 
			$this->createIndexIfNotExist($file);	//if the file doesn't exist, create a new index.
			$this->translations[$file]->targetLanguage = $target;
			//if the default target language is not set, set it to the first file's target language (can be changed later)
			if($this->defTargetLanguage == NULL)
				$this->defTargetLanguage = $target;
		}
	}
	
	//returns the source language of the file specified. If no file is specified, it returns the default source language. 
	public function getSourceLanguage($file = NULL)
	{
		if($file == NULL) 
			return $this->defSourceLanguage;
		else 
		{
			if(!array_key_exists($file, $this->translations))
				throw new Exception('XLIFFDocument::getSourceLanguage() - file ' . $file . ' does not exist.');
			return $this->translations[$file]->sourceLanguage;
		}
	}
	
	//returns the target language of the file specified. If no file is specified, it returns the default target language. 
	public function getTargetLanguage($file = NULL)
	{
		if($file == NULL) 
			return $this->defTargetLanguage;
		else 
		{
			if(!array_key_exists($file, $this->translations))
				throw new Exception('XLIFFDocument::getTargetLanguage() - file ' . $file . ' does not exist.');
			return $this->translations[$file]->targetLanguage;
		}
	}
	
	//reads in data from an xliff file
	//$file is the filename of the xliff file.
	public static function read($file)
	{
		//read the XLIFF translations from the file
		$reader = new XMLReader();
		if(!$reader->open($file))
			throw new Exception('XLIFFDocument::read() - could not open the file: ' . $file);
		
		$xliffDoc = new XLIFFDocument();	//create new instance of XLIFFDocument class	
			
		$currentFile = NULL;
		$srcLang = NULL;
		$targLang = NULL;
		$datatype = NULL;
		$transunitID = 0;
		$source = NULL;
		$target = NULL;
		$fileTranslations = array();
		
		while($reader->read())	//goes through all of the nodes/elements in the file one by one
		{
			//must check $reader->nodeType, otherwise these will be called for both the opening and closing tag
			if($reader->nodeType == XMLReader::ELEMENT)
			{
				//opening <file> tag
				if($reader->name == 'file')
				{
					//read the <file> tag and the attributes
					$currentFile = $reader->getAttribute('original');
					$srcLang = $reader->getAttribute('source-language');
					$targLang = $reader->getAttribute('target-language');
					$datatype = $reader->getAttribute('datatype');
					
					//check for missing data in the header
					if($currentFile == NULL || $srcLang == NULL || $targLang == NULL || $datatype == NULL)
						throw new Exception('XLIFFDocument::read() - invalid <file> header.');
					
					//update the defSourceLanguage and defTargetLanguage variables with the data from the file
					if($xliffDoc->defSourceLanguage == NULL)
						$xliffDoc->defSourceLanguage = $srcLang;
					if($xliffDoc->defTargetLanguage == NULL)
						$xliffDoc->defTargetLanguage = $targLang;
				}
				
				//opening <trans-unit> tag
				else if ($reader->name == 'trans-unit')
				{
					$transunitID = $reader->getAttribute('id');
				}
				
				//opening <source> tag
				else if ($reader->name == 'source')
				{
					if($source != NULL)
						throw new Exception('XLIFFDocument::read() - source is already defined for file ' . $currentFile . ' in trans-unit ' . $transunitID . '.');
					$source = $reader->readString();
				}
				
				//opening <target> tag
				else if ($reader->name == 'target')
				{
					if($target != NULL)
						throw new Exception('XLIFFDocument::read() - target is already defined for file ' . $currentFile . ' in trans-unit ' . $transunitID . '.');
					$target = $reader->readString();
				}
			}//($reader->nodeType == XMLReader::ELEMENT)
			
			//must check $reader->nodeType, otherwise these will be called for both the opening and closing tag
			else if ($reader->nodeType == XMLReader::END_ELEMENT)
			{
				
				//closing <trans-unit> tag
				if ($reader->name == 'trans-unit')
				{
					if($source == NULL || $target == NULL)
						throw new Exception('XMLReader::read() - failed to read trans-unit ' . $transunitID . ' for file ' . $currentFile);
					$translations[$source] = $target;
					$source = NULL;
					$target = NULL;	//set these to NULL so that they do not somehow get carried over to the next trans-unit by accident
				}

				//closing <file> tag
				else if($reader->name == 'file')
				{
					$transfile = new XLIFFDocument_TranslationFile($srcLang, $targLang, $datatype, $translations);
					$xliffDoc->translations[$currentFile] = $transfile;
					$translations = array();	//clear $translations for the next file
				}
			}//($reader->nodeType == XMLReader::END_ELEMENT)
		}//while(reader->read()
		
		$reader->close();
		
		return $xliffDoc;
	}
	
	//adds translations to the file specified.
	//$translations should be an array consisting of source=>target pairs.
	public function addTranslations($file, $translations)
	{
		if($file == NULL)
			throw new Exception('XLIFFDocument::addTranslations() - $file parameter can not be NULL.');
			
		$this->createIndexIfNotExist($file);
		$this->translations[$file]->translations += $translations;	
	}
	
	//returns the translations in the specified file. If no file is specified, it returns the translations in all files.
	//returns an array of source=>target pairs.
	public function getTranslations($file = NULL)
	{
		$ret = array();
		if($file != NULL) //return only translations from $file
		{
			if(!array_key_exists($file, $this->translations))
				throw new Exception('XLIFFDocument::getTranslations() - file ' . $file . ' does not exist.');
			
			$ret = $this->translations[$file]->translations;	//get only the translations from 1 file
		}
		else	//get the translations from all of the files
		{
			foreach($this->translations as $transfile)
				$ret += $transfile->translations;
		}
		return $ret;
	}
	
	//writes the stored data to the xliff file specified
	//Requires that setSourceLanguage() and setTargetLanguage() were called at least once before calling write().
	public function write($file)
	{
		if($this->defSourceLanguage == NULL  || $this->defTargetLanguage == NULL) 
			throw new Exception('XLIFFDocument::write() - source and target language not specified.');
	
		//check to make sure the transfiles are all valid
		foreach($this->translations as $filename=>$transfile)
		{	
			if($transfile->sourceLanguage == NULL)
				throw new Exception('XLIFFDocument::write() - source language not specified in file ' . $file . '. You can set the default source language using setSourceLanguage().' );
			if($transfile->targetLanguage == NULL)
				throw new Exception('XLIFFDocument::write() - target language not specified in file ' . $file . '. You can set the default target language using setTargetLanguage().' );
		}
		
		$writer = new XLIFFWriter($this->defSourceLanguage, $this->defTargetLanguage);
		
		//add the stored data in $translations to the XLIFFWriter
		foreach($this->translations as $filename=>$transfile)
		{	
			$writer->setFile($filename, $transfile->datatype);
			$writer->setSourceLanguage($transfile->sourceLanguage);
			$writer->setTargetLanguage($transfile->targetLanguage);
			$writer->addTranslations($transfile->translations);
		}
		
		//write to the file
		$handle = fopen($file, 'w+');
		if($handle == FALSE) throw new Exception('XLIFFDocument::write() - failed to open ' . $file . ' for writing.');
		$writer->write($handle);
		fclose($handle);
	}
	
	//creates a new XLIFFDocument_TranslationFile in $this->translations[$file] if it does not already exist.
	private function createIndexIfNotExist($file)
	{
		if(!array_key_exists($file, $this->translations))
		{
			$this->translations[$file] = new XLIFFDocument_TranslationFile($this->defSourceLanguage, $this->defTargetLanguage, 'html');
		}
	}
};

//helper class for storing translations
class XLIFFDocument_TranslationFile
{	
	function __construct($srcLang = NULL, $targLang = NULL, $datatype = NULL, $translations = NULL)
	{
		//$this->file = $filename;
		$this->sourceLanguage = $srcLang;
		$this->targetLanguage = $targLang;
		$this->datatype = $datatype;
		$this->translations = ($translations == NULL ? array() : $translations);
	}
	
	//public $file;		//name of the source file: string
	public $sourceLanguage, $targetLanguage;	//2 char strings
	public $datatype;
	public $translations;		//array of source=>translated strings.
};


?>
