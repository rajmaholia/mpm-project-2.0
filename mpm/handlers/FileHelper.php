<?php
namespace Mpm\Handlers;

class FileHelper {
 
  /**
   * Creates Directory Hierarchy is not Exists .
   * Eg . if parameter is `/path/to/something/` . If directory path doesn't exists it creates it and the  try to create path/to/ of doesn't Exists and continues so on .
   * 
   * @param $dir
   * 
   */
  public static function make_dir($dir) {
    $dirParts = explode("/",$dir);
    $currentDir = "";
    for($i=0;$i<count($dirParts);$i++){
      $currentDir .= $dirParts[$i]."/";
      file_exists($currentDir) || mkdir($currentDir);
    }
  }
}