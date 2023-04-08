<?php
namespace Mpm\Urls;

/**
 * Return url-patterns written in  given file name (without .php)  
 * 
 * @param string $urlfilepath 
 * @return array 
 */
function includes($urlfilepath) {
  $urlfilepath.=".php";
  if (file_exists($urlfilepath)):
      require_once($urlfilepath);
      return $urlpatterns;
  else : 
      return [];
  endif;
}

/**
 * Redirect to specified path relative to BASE_URL that is http[s]://< domain or IP address with ports>/
 * 
 * @param string $path 
 */
function redirect($path) {
  if (substr($path,0,1)=="/"):
      $path = substr($path,1);
  endif;
 header("Location:".BASE_URL.$path);
}

/** Redirects to given url using header 
 * parameter is absolute url of redirection 
 * 
 * @param string $url 
 */
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