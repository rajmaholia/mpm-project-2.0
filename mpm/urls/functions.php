<?php
namespace Mpm\Urls;

function includes($urlfilepath) {
  $urlfilepath.=".php";
  try {
    require_once($urlfilepath);
  } catch(Exception $e) {
    var_dump($e);
  }
  return $urlpatterns;
}

function redirect($path) {
  if(substr($path,0,1)=="/") {
    $path = substr($path,1);
  }
 header("Location:".BASE_URL.$path);
}

function http_redirect($url) {
  header("Location:".$url);
}

/**
 * Gets Urls , Views , Name of url and Wrap them all in an array respective order and return it .
 * 
 * @param string $url 
 * @param string $view
 * @param string|null $name 
 * @return array
 */
function path($url,$view,$name=null){
  return array($url,$view,$name);
}

/**
 * Get a name and return Url associated with it from urls.php 
 * 
 * @param string $name 
 * @param array $arguments 
 * @return string 
 */
function reverse($name,$arguments=array()){
  //return absolute url of url_name;
  global $urlpatterns;
  $path = array_column($urlpatterns,0,2)[$name];
  //0 is urlpath and 2 is its name
  $arra = preg_split("@/@",$path,-1,PREG_SPLIT_NO_EMPTY);
  $pattern = "/[(].*?[)]/";
  $count=0;
  foreach($arra as $key=>&$value) {
    if(preg_match($pattern,$value)){
      $value = $arguments[$count];
      $count++;
    }
  }
  $url = implode('/',$arra);
  $url = substr(trim($path),0,1)=="/"?"/".$url:$url;
  $url = substr(trim($path),-1,1)=="/"?$url."/":$url;
  if(count($arra)==0) return "/";
  return $url;
}