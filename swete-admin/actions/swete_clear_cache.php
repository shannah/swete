<?php
class actions_swete_clear_cache {
    function handle($params) {
        $liveCache = DATAFACE_SITE_PATH . DIRECTORY_SEPARATOR . "livecache";
        //echo $liveCache;exit;
        if (!SweteTools::isAdmin()) {
            die("Only admins allowed to perform this action.");
        }
        
        self::rrmdir($liveCache, array('.htaccess', 'string_imports'), false);
        header('Content-type: application/json; charset=UTF-8');
        echo json_encode(array('code' => 200, 'message' => 'success'));
        
    }
    
    static function rrmdir($dir, $excludes=array(), $deleteRootDir=false) { 
        $sep = DIRECTORY_SEPARATOR;
       if (is_dir($dir)) { 
         $objects = scandir($dir); 
         foreach ($objects as $object) { 
            if (in_array($object, $excludes)) {
                continue;
            }
           if ($object != "." && $object != "..") { 
             if (is_dir($dir.$sep.$object))
               self::rrmdir($dir.$sep.$object, $excludes, true);
             else
               unlink($dir.$sep.$object); 
           } 
         }
         if ($deleteRootDir) {
            rmdir($dir); 
         }
       } 
    }
}