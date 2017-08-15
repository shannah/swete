<?php
class actions_tm_validate_xliff {
    function handle($params){
        if (@$_POST['--xliff-content'] ){
            $dd = new DOMDocument();
            libxml_use_internal_errors(true);
            $dd->loadXML($_POST['--xliff-content']);
            
            if($dd->schemaValidate('modules/tm/lib/xliff-core-1.2-strict.xsd')){
                df_write_json(array(
                    'code' => 200,
                    'message' => 'This document is valid'
                ));
                exit;
            } else { 
                ob_start();
                $this->libxml_display_errors();
                $errors = ob_get_contents();
                ob_end_clean();
                
                df_write_json(array(
                    'code' => 500,
                    'message' => 'This document is not valid',
                    'errors' => $errors
                ));
            }
        } else {
            $mod = Dataface_ModuleTool::getInstance()->loadModule('modules_tm');
            $mod->registerPaths();
            Dataface_JavascriptTool::getInstance()->import(
                'xataface/modules/tm/validate_xliff.js'
            );
            df_display(array(), 'xataface/modules/tm/validate_xliff.html');
        }
    }
    
    function libxml_display_error($error)
    {
        $return = "<br/>\n";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "<b>Warning $error->code</b>: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "<b>Error $error->code</b>: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "<b>Fatal Error $error->code</b>: ";
                break;
        }
        $return .= trim($error->message);
        if ($error->file) {
            $return .=    " in <b>$error->file</b>";
        }
        $return .= " on line <b>$error->line</b>\n";
    
        return $return;
    }
    
    function libxml_display_errors() {
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            print $this->libxml_display_error($error);
        }
        libxml_clear_errors();
    }
}