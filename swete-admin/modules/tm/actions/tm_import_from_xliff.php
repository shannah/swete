<?php 

/*
 * tm_import_from_xliff action
 * Author: Kevin Chow
 * Date: July 25, 2012
 * 
 * This class imports translations from an XLIFF file into a translation memory.
 * This action uses the tm_import_from_xliff.html template.
 * When the user clicks on the "Import from XLIFF" button, they will be taken to a new screen with a file upload prompt.
 * If they upload a valid xliff file, the new translations will be added to the database. The username for the translations is set to the current user.
 */


require_once 'modules/tm/lib/XLIFFReader.php';
require_once 'xataface/Dataface/ResultReader.php';
require_once 'modules/tm/lib/XFTranslationMemory.php';

class actions_tm_import_from_xliff
{	
	public function handle(&$params)
	{
	    set_time_limit(0);
		//the HTML data sent to the template	
		$msg = "";
		$url = "";
		$usersDropDownHTML = "";
		$fileUploadHTML = "";
		$statusHTML = "";
		$userForm = "";
		
		$translationMemoryID = NULL;
		$currentUser = "";
		$currentUserRole = "";
		$uploadedFile = false;
		$fileSpecified = false;
		$validExtension = false;
		
		//get info about the current user
		$a = Dataface_AuthenticationTool::getInstance();
		$currentUser = $a->getLoggedInUser()->_values['username'];
		$roleID = $a->getLoggedInUser()->_values['role_id'];
		$sql = "SELECT ur.name AS role FROM user_roles ur WHERE ur.user_role_id=$roleID";
		$results = new Dataface_ResultReader($sql, df_db());
		foreach($results as $result)
		{
			$currentUserRole = $result->role;
			break;
		}
		
		//get the translation memory
		$app = Dataface_Application::getInstance();
		$query = &$app->getQuery();
		//if(!array_key_exists('-recordid', $query)) throw new Exception("No translation memory id was specified");
		$record = $app->getRecord();
		//get the translation memory ID
		if ( !array_key_exists('translation_memory_id' , $record->_values)) throw new Exception("No translation memory id was specified");
		$id = $record->_values['translation_memory_id'];
		$translationMemoryID = intval($id);
		
		//check the uploaded file
		if(count($_FILES) > 0)
		{
			$uploadedFile = true;
			
			if($_FILES['file']['name'] != NULL)
				$fileSpecified = true;
			$parts = explode('.', $_FILES['file']['name']);
			if(end($parts) == "xliff")
				$validExtension = true;	
		}
		
		//get the uploaded file if present
		if($fileSpecified && $validExtension)
		{
			//We have a valid file. Do the import.
			//get the translator. if there was a user specified in $_POST, then use that, otherwise, the translator is the current logged in user
			if(array_key_exists('username', $_POST))
				$translator = $_POST['username'];
			else 	
				$translator = $currentUser;
			
			//get the user selection for the status of the imported strings. They can be either submitted or approved.
			$status = $_POST['status'] == "SUBMITTED" ? XFTranslationMemory::TRANSLATION_SUBMITTED : XFTranslationMemory::TRANSLATION_APPROVED;
			
			$reader = new XLIFFReader();
			$listener = new tm_import_from_xliff_Listener(XFTranslationMemory::loadTranslationMemoryById($translationMemoryID), $translator, $status);
			$reader->addXliffListener($listener);
			
			//import the XLIFF file
			$failed = false;
			try
			{
				$reader->parse($_FILES['file']['tmp_name']);
			}
			catch(Exception $e)
			{	
				$failed = true;
				$msg = "There was an error importing the Xliff file. " . $e->getMessage();
			}
			
			if(!$failed)
			{
				//redirect to the browse view, and display a success message
				$query['-action'] = 'browse';	
				$query['--msg'] = "Xliff file was imported successfully.";
				$url = "index.php?" . http_build_query($query);
				header("Location: $url");
			}
			//else $msg = "There was an error importing the Xliff file.";
		}
		//get the user to upload a file
		else
		{
			if($uploadedFile && !$fileSpecified)
				$msg = "You must specify a file.<br>";
			else if($fileSpecified && !$validExtension)
				$msg = "Invalid file type. Please specify a valid .xliff file.";

			
		}
		
		//the destination url of the submit button (goes back to the same page)
		$url = "index.php?" . $_SERVER["QUERY_STRING"];
		
		//create and populate the drop down menu for selecting the translator
		if($currentUserRole == "ADMIN")
		{
			//let ADMINs select the translator from a drop down list
			$usersDropDownHTML = "<label for=\"username\">Translator:</label><select name=\"username\">";
			$sql = "SELECT u.username, ur.name AS role FROM users u INNER JOIN user_roles ur on u.role_id=ur.user_role_id";		
			$results = new Dataface_ResultReader($sql, df_db());
			foreach($results as $result)
			{
				if($result->username == $currentUser)
					$usersDropDownHTML .= "<option selected value=" . $result->username . ">" . $result->username . "</option>";
				else $usersDropDownHTML .= "<option value=" . $result->username . ">" . $result->username . "</option>";
			}
			$usersDropDownHTML .= "</select><br>";
		}
		else
			$usersDropDownHTML = "";
		
		df_register_skin('import from xliff skin', dirname(__FILE__).'/../templates');	//register the tm templates directory
		//$usersDropDownHTML contains the html for the drop down list of translators if the current user is an admin
		df_display(array('msg'=>$msg, 'userForm'=>$usersDropDownHTML, 'url'=>$url), $params['action']['template']);
	}
};

class tm_import_from_xliff_Listener implements XLIFFListener
{
	private $translationMemory,	//instance of XFTranslationMemory
			$translator,
			$status,
			$currentFile,
			$sourceLanguage,
			$targetLanguage,
			$datatype;
			
	public function __construct($translationMemory, $translator, $status=XFTranslationMemory::TRANSLATION_SUBMITTED)
	{
		$this->translator = $translator;
		$this->status = $status;
		$this->translationMemory = $translationMemory;
	}

	//called when starting to parse the XLIFF file. Takes an XLIFFEvent_Start event as a parameter.
	//Available members in $eStart: none
	public function onStart(XLIFFEvent_Start $eStart)
	{
	}
	
	//called when finished parsing the XLIFF file. Takes an XLIFFEvent_End event as a parameter.
	//Available members in $eEnd: none
	public function onEnd(XLIFFEvent_End $eEnd)
	{
	}
	
	//called when starting a new translation file. Takes an XLIFFEvent_StartFile event as a parameter.
	//Available members in $eStartFile: file (String), sourceLanguage (String), targetLanguage (String), datatype (String)
	public function onStartFile(XLIFFEvent_StartFile $eStartFile)
	{
		$this->currentFile = $eStartFile->file;
		$this->sourceLanguage = $eStartFile->sourceLanguage;
		$this->targetLanguage = $eStartFile->targetLanguage;
		$this->datatype = $eStartFile->datatype;
	}
	
	//called when ending a translation file. Takes an XLIFFEvent_EndFile event as a parameter.
	//Available members in $eEndFile: none
	public function onEndFile(XLIFFEvent_EndFile $eEndFile)
	{
		$this->currentFile = NULL;
	}
	
	//called when a translation is read. Takes an XLIFFEvent_Translation event as a parameter.
	//Available members in $eTranslation: source (String), target (String)
	public function onTranslation(XLIFFEvent_Translation $eTranslation)
	{
		$src = $eTranslation->source;
		$targ = $eTranslation->target;
		if ( !trim($src) or !trim($targ) ){
		    // No need to add blanks
		    return;
		}
		if ( trim($src) === trim($targ) ){
		    // NO need to import translations that are the same.
		    // THis is important because, in order to help with OmegaT compatibility
		    // we export XLIFF files to add the translation as the source
		    // by default.  We don't want to import these on reimport.
		    return;
		}
		$this->translationMemory->addTranslation($src, $targ, $this->translator);
		$this->translationMemory->setTranslationStatus($src, $targ, $this->status, $this->translator);
	}
};
