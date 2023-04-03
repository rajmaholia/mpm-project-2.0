<?php
use function Mpm\Database\db_read;


/**
 * Get a name and return Url associated with it from urls.php 
 * 
 * @param string $name 
 * @param array $arguments 
 * @return string 
 */
function reverse($name,$args=[]){
  //return absolute url of url_name;
  global $urlpatterns;
  $path = array_column($urlpatterns,0,2)[$name];
  //0 is urlpath and 2 is its name
  $arra = preg_split("@/@",$path,-1,PREG_SPLIT_NO_EMPTY);
  $pattern = "/[(].*?[)]/";
  $count=0;
  foreach($arra as $key=>&$value) {
    if(preg_match($pattern,$value)){
      $value = $args[$count];
      $count++;
    }
  }
  $url = implode('/',$arra);
  $url = substr(trim($path),0,1)=="/"?"/".$url:$url;
  $url = substr(trim($path),-1,1)=="/"?$url."/":$url;
  if(count($arra)==0) $url="/";
  else  return $url;
}

/**
 * Checks all apps that are  registered in settings.php in INSTALLED_APPS array for given filename under app's  static folder 
 * and returns full path of file if it matched any .
 * 
 * @param string $staticfile 
 * @return string 
 */
function staticfile($staticfile){
  $dirs = STATICFILES["DIRS"];
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

/**
 * @param string|int $value value to echo 
 * @param string|int $default default value 
 */
function echo_safe($value,$default="") {
  $value = (isset($value))?$value:$default;
  echo($value);
}

/**
 * An alias of reverse. But it uses echo(<$url>)  instead of return <$url> 
 * 
 * @param string $name 
 * @param array $args
 */
function url($name,$args=[]){
  $url = reverse($name,$args);
  echo $url;
}

function rurl($name,$args=[]){
  $url = reverse($name,$args);
  return $url;
}



