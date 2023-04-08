<?php
namespace Mpm\Core;
use function Mpm\View\render;
use function Mpm\Urls\{reverse,redirect};

class Router { 
  
  /**
   * @param string $url      Requested URL 
   * @param array  $patterns Patterns (regex) to be matched Against URL
   * @return array 
   */
  public static function matchedPattern($url,$patterns){
    if(substr(trim($url),-1)!='/') $url.='/';// If Url doesn't ends with / append a '/' after URL
    $paths = array_column($patterns,0); //extract all paths only , from patterns
    for($i = 0;$i<count($patterns);$i++) {//Loop Over all patterns (regex)  to match against Request URL 
      $current_url = $patterns[$i][0];// Url route
      if(empty(trim($current_url))) $current_url = "/";
      $pattern = "@^{$current_url}$@";
      if(preg_match($pattern, $url)) return $patterns[$i];
    }
    return false;
  }
  
  public static function process($url,$urlpatterns){
    $pattern = self::matchedPattern($url,$urlpatterns);
    if($pattern==false) exit(self::error('NoReverseMatch',$url,$urlpatterns));
    $view_name = $pattern[1];
    $pattern[0] = empty($pattern[0])?"/":$pattern[0];
    preg_match("@{$pattern[0]}@",$url,$matches);
    $groups = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
    
    $content = self::render($view_name,$groups);
    echo($content);
  }
  
  public static function error($type,$url,$urlpatterns){
    if(DEBUG===true){
        $mpmException = array(
          "name"=>"no_reverse_match",
          "title"=>"No Reverse Match For",
          "target"=>$url,
          "extra"=>array("title"=>"Mpm Tried in this order","data"=>stripcslashes(json_encode($urlpatterns)))
        );
        echo(render($_SERVER,"debug.php",array("mpm_exception"=>$mpmException))); 
      }
      else {
        redirect(reverse('404'));
      }
  }
  
  public static function render($view_name,$groups) {
    $request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], getallheaders(), file_get_contents('php://input'));
    return count($groups)>0?$view_name($request,$groups):$view_name($request);
  }
}//class Router