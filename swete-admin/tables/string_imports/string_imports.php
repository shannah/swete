<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of string_imports
 *
 * @author shannah
 */
require_once 'modules/tm/lib/XFTranslationMemory.php';
require_once 'inc/CSVStringImporter.php';
class tables_string_imports {
    
    /**
     * Handles the insertion of a new imported record.  This should parse the 
     * input file and place the strings appropriately into the translation_miss_log
     * table and the translation memory.
     * @param Dataface_Record $record
     */
    public function afterInsert(Dataface_Record $record){
        
        $filePath = $record->getContainerSource('file');
        if ( !file_exists($filePath) ){
            throw new Exception("Source file doesn't exist");
        }
        
        switch ( $record->val('file_format') ){
            case 'CSV':
                $translationMemory = null;
                if ( $record->val('target_translation_memory_uuid') ){
                    $translationMemory = XFTranslationMemory::loadTranslationMemoryByUuid(
                        $record->val('target_translation_memory_uuid')
                    );
                }
                
                $importer = new CSVStringImporter($filePath, $translationMemory);
                $importer->fixEncoding();
                $message = 'Import succeeded';
                $status = 'COMPLETE';
                try {
                    $importer->import();
                } catch ( Exception $ex){
                    $message = 'Import failed: '.$ex->getMessage();
                    $status = 'FAILED';
                }
                
                $log = $message . "\r\n".
                        "Succeeded: ".$importer->succeeded.", ".
                        "Failed: ".$importer->failed."\r\n".
                        "Error Log:\r\n===========\r\n";
                
                foreach ( $importer->errors as $row ){
                    $log .= "Import Row: ".implode(",", $row['row'])."\r\n".
                            "Error Message: ".$row['message'];
                }
                
                df_q(sprintf(
                        "update string_imports 
                            set 
                                log='%s', 
                                status='%s', 
                                succeeded=%d, 
                                failed=%d
                            where
                                string_import_id=%d",
                        addslashes($log),
                        addslashes($status),
                        $importer->succeeded,
                        $importer->failed,
                        $record->val('string_import_id')
                        ));
                
                break;
                
                
                
            default : 
                throw new Exception(sprintf(
                        "Unrecognized file format: %s",
                        $record->val('file_format')
                ));
        }
        
        
    }
    
}

?>
