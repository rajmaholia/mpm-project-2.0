<?php
namespace Mpm\Static;

/**
 * Takes static file path as parameter and return first matching static file's absolute path . and 
 * Returns empty string if no matching static file found . 
 * 
 * If given param is test/test.js , then its checks all folder's (that are registered under APPS in config/settings.php and STATICFILES['DIRS']) static folder for test/test.js file . And
 * returns first matching file.
 * 
 * @param string $staticfile 
 * @return string 
 */
function staticfile($staticfile){
  $dirs = STATICFILES["DIRS"];//
    $dirs = array_merge($dirs,APPS);
    //AUTOLOAD_STATICFILES["DIRS"],
    $i = 0;
    foreach($dirs as $dir){
      $a =  glob($dir."/static/$staticfile");
      if(count($a)>0) return (substr($a[0],0,1)=="/"?$a[0]:"/".$a[0]);
      $i++;
    }
  return "";
}