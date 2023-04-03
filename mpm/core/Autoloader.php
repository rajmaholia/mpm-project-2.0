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
}
