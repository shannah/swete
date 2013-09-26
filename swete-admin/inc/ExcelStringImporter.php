<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once 'modules/tm/lib/XFTranslationMemory.php';
require_once 'modules/excel/lib/PHPExcel.php';
require_once 'modules/excel/lib/PHPExcel/IOFactory.php';

/**
 * Description of CSVStringImporter
 *
 * @author shannah
 */

class ExcelStringImporter extends CSVStringImporter {
    
    public function __construct($inputFilePath, XFTranslationMemory $translationMemory = null ){
        $objPHPExcel = PHPExcel_IOFactory::load($inputFilePath);
        $csvWriter = new PHPExcel_Writer_CSV($objPHPExcel);
        $csvOut = $inputFilePath.'.csv';
        $csvWriter->setExcelCompatibility();
        $csvWriter->save($csvOut);
        parent::__construct($csvOut, $translationMemory);
        
    }
    
    
}
