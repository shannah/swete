<?php 

//Exports the translation memory to an XLIFF file
//uses the following tables:
//xf_tm_strings
//xf_tm_translation_memory_strings
//xf_tm_translations

require_once 'modules/tm/lib/XLIFFWriter.php';
require_once 'Dataface/ResultReader.php';
require_once 'modules/tm/lib/TMTools.php';
class actions_tm_export_as_xliff
{	
	public function handle(&$params)
	{
		//get the translation memory ID to export
		$app = Dataface_Application::getInstance();
		$query = &$app->getQuery();
		$record = $app->getRecord();
		
		if ( !array_key_exists('translation_memory_id' , $record->_values)) throw new Exception("No translation memory id was specified");
		$id = $record->_values['translation_memory_id'];
		$id = intval($id);
		
		$dlFileName = "TranslationMemory_$id.xliff";
		$outputFilename = tempnam('translation_memory','.xliff');//"TranslationMemory_$id.xliff";
		$xliffApparentSourceFile = "TRANSLATION_MEMORY_ID_$id";	//xliff requires the source file it came from. Dunno what to put here, since it came from the database?
		
		
		//get the translation memory's source and target languages from the database
		$sourceLanguage = NULL;
		$targetLanguage = NULL;
		$sql = "SELECT source_language, destination_language
				FROM xf_tm_translation_memories
				WHERE translation_memory_id = $id";
		$results = new Dataface_ResultReader($sql, df_db());
		foreach($results as $result)	//query should only contain 1 result
		{
			$sourceLanguage = $result->source_language;
			$targetLanguage = $result->destination_language;
			break;
		}
		
		//if the source/targetLanguages are NULL, then the entry did not exist in the database.
		if($sourceLanguage == NULL || $targetLanguage == NULL)
			throw new Exception("Export translation memory as XLIFF - Translation memory $id does not exist in the database!");
		
		//create the xliffwriter instance and set the header info
		$writer = new XLIFFWriter($sourceLanguage, $targetLanguage);
		$writer->setFile($xliffApparentSourceFile);	
		$writer->rawSources(true)->rawTranslations(true);
		
		//setup the sql for reading the translations
		$sql = "SELECT s.normalized_value as source, t.normalized_translation_value as target, tms.translation_memory_id
				FROM xf_tm_translations t
					 inner join xf_tm_translation_memory_strings tms on (t.string_id = tms.string_id and t.translation_id=tms.current_translation_id)
					 inner join xf_tm_strings s on s.string_id = t.string_id
				WHERE tms.translation_memory_id = $id";	
		$results = new Dataface_ResultReader($sql, df_db());	//xf_db_query($sql, df_db());//, t.language as target_language, s.language as source_language
		
		//open the output file for writing
		$outFile = fopen($outputFilename, 'w+');
		if(!$outFile)
			throw new Exception('Export translation memory as XLIFF - failed to open ' . $outputFilename);
		
		$numStrings = 0;	
		
		foreach($results as $result)
		{
		    $source = TMTools::encodeForXLIFF($result->source);
            // The translation should already be encoded so we don't need to encode it
            // we just do the xliff encoding
            $target = TMTools::encodeForXLIFF($result->target);
            if ( !$target ){
                $target = $source;
            }
			//Add the translations to the XLIFFWriter
			$writer->addTranslation($source, $target);
			$numStrings++;
			
			//flush to file every 100 strings to avoid running out of memory for very large sets
			if(($numStrings % 100) == 0)
				$writer->flush($outFile);
		}
		
		//save the rest of the data to file and close it
		$writer->write($outFile);
		fclose($outFile);
		
		//send the file to the browser
		header("Content-disposition: attachment; filename='".basename($dlFileName)."'");
		header('Content-Transfer-Encoding: binary');
		readfile($outputFilename);
		unlink($outputFilename);
		
		return;
	}
};