<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'modules/tm/lib/XFTranslationMemory.php';

/**
 * Description of CSVStringImporter
 *
 * @author shannah
 */

class CSVStringImporter {
    /**
     *
     * @var XFTranslationMemory The target translation memory for imported strings.
     * If this is null, then the importer will check to see if the CSV file specifies
     * a translation memory explicitly.
     */
    private $targetTranslationMemory;
    
    /**
     *
     * @var string
     */
    private $inputFilePath;
    
    public $errors = array();
    public $succeeded = 0;
    public $failed = 0;
    
    public function CSVStringImporter($inputFilePath, XFTranslationMemory $translationMemory = null ){
        $this->inputFilePath = $inputFilePath;
        $this->targetTranslationMemory = $translationMemory;
    }
    
    public function import(){
        $fh = fopen($this->inputFilePath, 'r');
        if ( !$fh ){
            throw new Exception(sprintf(
                    "Failed to open input file '%s'",
                    $this->inputFilePath
            ));
        }
        $headers = array_flip(fgetcsv($fh));
        
        $required_fields = array(
            'normalized_string',
            'normalized_translation_value'
        );
        
        if ( !isset($this->targetTranslationMemory) ){
            $required_fields[] = 'translation_memory_uuid';
        }
        
        foreach ($required_fields as $f ){
            if ( !array_key_exists($f, $headers) ){
                throw new Exception(sprintf(
                    "Missing required column heading: %s",
                    $f
                ));
            }
        }
        
        while (($row = fgetcsv($fh)) !== false){
            $string = $row[$headers['normalized_string']];
            $translation = $row[$headers['normalized_translation_value']];
            $translationMemory = $this->targetTranslationMemory;
            $tmuuid = $row[$headers['translation_memory_uuid']];
            if ( !isset($translationMemory) ){
                $translationMemory = XFTranslationMemory::loadTranslationMemoryByUuid($tmuuid);
            }
            
            if ( !isset($translationMemory) ){
                $this->errors[] = array(
                    'row' => $row,
                    'message' => 'No translation memory assigned.'
                );
                $this->failed++;
                continue;
            }
            
            $strRec = XFTranslationMemory::addString($string, $translationMemory->getSourceLanguage());
            
            $res = df_q(sprintf(
                "select string_id from translation_miss_log where string_id=%d",
                $strRec->val('string_id')
            ));
            
            if ( mysql_num_rows($res) == 0 ){
                @mysql_free_result($res);
                // This string is not in the translation miss log yet.  We
                // will import it now
                $tlogEntry = new Dataface_Record('translation_miss_log', array());
            
                $nstr = TMTools::normalize($string);
                $trimStripped = trim(strip_tags($nstr));
                if ( !$trimStripped ) continue;
                if ( preg_match('/^[0-9 \.,%\$#@\(\)\!\?\'":\+=\-\/><]*$/', $trimStripped))  continue;
                        // If the string is just a number or non-word we just skip it.
                //$estr = TMTools::normalize(TMTools::encode($nstr, $junk));
                // We don't need to encode the string

                $res = df_q(sprintf("select website_id from websites where translation_memory_id=%d",
                        $translationMemory->getRecord()->val('translation_memory_id')));

                if ( !$res ){
                    $this->failed++;
                    $this->errors[] = array(
                        'row' => $row,
                        'message' => sprintf("No website found for translation memory %d",
                            $translationMemory->getRecord()->val('translation_memory_id'))
                    );
                    continue;
                }
                list($websiteId) = mysql_fetch_row($res);
                @mysql_free_result($res);

                $hstr = md5($string);

                $tlogEntry->setValues(array(
                        'http_request_log_id' => null,
                        'string' => $string,
                        'normalized_string' => $string,
                        'encoded_string' => $string,
                        'string_hash' => $hstr,
                        'date_inserted'=> date('Y-m-d H:i:s'),
                        //'webpage_id'=>$page->val('webpage_id'),
                        'website_id'=>$websiteId,
                        'source_language' => $translationMemory->getSourceLanguage(),
                        'destination_language' => $translationMemory->getDestinationLanguage(),
                        'translation_memory_id' => $translationMemory->getRecord()->val('translation_memory_id'),
                        'string_id' => $strRec->val('string_id')

                ));

                $res = $tlogEntry->save();

                if ( PEAR::isError($res) ){
                    $this->errors[] = array(
                        'row' => $row,
                        'message' => 'Failed to insert translation miss log entry: '.$res->getMessage()
                    ); 
                }
                
            }
            
            
            
            if ( @trim($translation) ){
            
                try {
                    $translationMemory->setTranslationStatus($string, $translation, XFTranslationMemory::TRANSLATION_APPROVED);
                } catch ( Exception $ex ){
                    $this->failed++;
                    $this->errors[] = array(
                        'row' => $row,
                        'message' => 'Failed to set translation status: '.$ex->getMessage()
                    );
                    continue;
                }
            } else {
                // No translation provided we don't need to import the translation
            }
            $this->succeeded++;
        }
    }
    
    
}
