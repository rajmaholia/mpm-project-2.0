<?php
namespace Mpm\Handlers;

class FileHelper {
  
  public static function validate_required() {
    
  }
  
  public static function make_dir($dir) {
    $dirParts = explode("/",$dir);
    $currentDir = "";
    for($i=0;$i<count($dirParts);$i++){
      $currentDir .= $dirParts[$i]."/";
      file_exists($currentDir) || mkdir($currentDir);
    }
  }
}