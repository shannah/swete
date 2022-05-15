<?php
/*
 * swete_export_found_strings_as_xliff.php
 * Author: Kevin Chow
 * Date:Aug 03, 2012
 *
 * Exports the found strings from a result set to an xliff file. This action called by both swete_export_found_strings_as_xliff,
 * and swete_export_selected_strings_as_xliff. This action is available under export in the strings tab on the swete main page.
 * "Export found strings as XLIFF" exports all of the results to XLIFF, while "Export selected strings as XLIFF" exports only the
 * ones the user has checked.
 */
require_once 'modules/tm/lib/TMTools.php';
require_once 'modules/tm/lib/XLIFFWriter.php';
require_once 'Dataface/RecordReader.php';

class actions_swete_export_found_strings_as_xliff
{
	public function handle(&$params)
	{
		//get the selected strings to export
		$app = Dataface_Application::getInstance();
		$query = &$app->getQuery();
		unset($query['-limit']);	//if we don't do this, it will only export the 30 results on the first page
		$selectedRecords = df_get_selected_records($query);
		$numRecords = count($selectedRecords);

		//check to see whether we should export the found or selected strings
		if($numRecords == 0)
		{
			//if there are none selected, then export all of the strings, and update $numRecords
			$selectedRecords = new Dataface_RecordReader($query, 40, false);
			$numRecords = iterator_count($selectedRecords);
		}
		//echo ini_get('upload_tmp_dir');
		//echo '/'.sys_get_temp_dir();
		$tmpDir = @ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
		$outFilename = tempnam($tmpDir, 'translation_miss_log');//"translation_miss_log.xliff";
		//echo 'File: '.$outFilename;exit;
		$numProcessed = 0;
		$writer = NULL;
		$outFile = NULL;
		//do only if there are results to export
		if($numRecords > 0)
		{
			foreach($selectedRecords as $record)
			{
			    if ( !$record->checkPermission('view') ){
			        continue;
			    }
				//for the first translation, we need to set up the writer, and get the source/target language
				if($numProcessed == 0)
				{
					//create the XLIFFWriter and write the header
					$sourceLanguage = $record->_values['source_language'];
					$targetLanguage = $record->_values['destination_language'];
					$writer = new XLIFFWriter($sourceLanguage, $targetLanguage);
					$writer->setFile("TranslationMissLog");
					// Indicate that we are adding translations and sources
					// as XML directly so the writer doesn't need to encode them
					$writer->rawSources(true)->rawTranslations(true);

					//open the output file for writing
					if(!($outFile = fopen($outFilename, "w+")))
						throw new Exception("Failed to open $outFilename for writing.");
				}

				//write the translation
				$originalFile = $record->_values['request_url'];
				$source = TMTools::encodeForXLIFF(TMTools::encode($record->_values['normalized_string'], $params));
				// The translation should already be encoded so we don't need to encode it
				// we just do the xliff encoding
				$target = TMTools::encodeForXLIFF($record->_values['normalized_translation_value']);
				if ( !$target ){
				    $target = $source;
				}
				if ($originalFile) {
					$writer->setFile($originalFile);
				} else {
					$writer->setFile('TranslationMissLog');
				}
				$writer->addTranslation($source, $target);
				$numProcessed++;

				//flush the writer, or it could run out of memory for large sets
				if(($numProcessed % 100) == 0)
					$writer->flush($outFile);
			}

			//close the writer and save the output file
			$writer->write($outFile);
			fclose($outFile);
			//unlink($outFile);
			//output the file to the browser
			header("Content-disposition: attachment; filename='exported-strings-".time().".xliff'");
			header('Content-Transfer-Encoding: binary');
			readfile($outFilename);
			unlink($outFilename);
		}

		return;
	}
};
