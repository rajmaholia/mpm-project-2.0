<?php
namespace Mpm\Core;


class Autoloader {
  private  $dirs;
  private $files;
  
  public  function prepare($autoload){
    $this->dirs = $autoload["DIRS"];
    $this->files = $autoload["FILES"];
  }
  
  public  function autoload(){
    foreach($this->dirs as $dir) {
     $files =  glob($dir."/*.php");
     foreach($files as $file)
       require_once($file);
    }
    
    foreach($this->files as $file) {
      require_once($file);
    }
  }
  
  public static function classLoader($class){
    $class = str_replace("\\","/",$class);//Replaces \ with / 
    $directory_parts = explode("/",$class);
    $last_key = array_key_last($directory_parts);
    foreach($directory_parts as $k=>&$v){
      if($k!=$last_key) $v = strtolower($v);
    }
    unset($v);
    $file = implode("/",$directory_parts);
    require_once(BASE_DIR."/{$file}.php");
  }
}
