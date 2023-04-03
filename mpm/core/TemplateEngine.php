<?php
namespace Mpm\Core;


class TemplateEngine {
  public static function resolve($template){
    $dirs = TEMPLATES["DIRS"];
    $dirs = array_merge($dirs,AUTOLOAD_TEMPLATES["DIRS"],APPS);
    $template_mining_string="";
    $i=1;
    foreach($dirs as $dir){
      $template_mining_string .= "$i . $dir/templates/ <br>";
      
      $a =  glob($dir."/templates/$template");
      if(count($a)>0) return $a[0];
      $i++;
    }
    return array(null,$template_mining_string);
  }
}