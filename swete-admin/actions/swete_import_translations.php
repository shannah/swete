<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of swete_import_translations_csv
 *
 * @author shannah
 */
class actions_swete_import_translations {
    //put your code here
    public function handle($params){
       $uitk = Dataface_ModuleTool::getInstance()->loadModule('modules_uitk');
       $uitk->registerPaths();
       
       Dataface_JavascriptTool::getInstance()
        ->import('swete/actions/import_translations.js');
       
       df_display(array(), 'swete/actions/import_translations.html');
    }
    
    
}

?>
