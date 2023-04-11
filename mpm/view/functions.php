<?php
namespace Mpm\View;
use Mpm\Core\TemplateEngine;
use Mpm\Core\Request;

/**
 * Renders Templates with logic applied .
 * @param array $server 
 * @param string $template_name 
 * @param array $vars Default null 
 * @return string 
 */
function render(Request $request,$template_name, $vars = null) {
  
  $filename = TemplateEngine::resolve($template_name); //gets first matched template name 
  if(is_array($filename) && $filename[0]==null) {
   //Handles when template not found 
    if(DEBUG==true){
    $mpmException = array(
      "name"=>"template_does_not_exists",
      "title"=>"Template Doesn't Exists",
      "target"=>$template_name,
      "extra"=>array("title"=>"Mpm Searched for template in these Directories",'data'=>$filename[1])
      );
    return render($request,"debug.php",array('mpm_exception'=>$mpmException));
    } else {
      echo "<h1>Internal Server Error (501)</h1>";
      echo "<p>There is a problem in internal Server</p>";
      exit();
    }
  }
  
  if (is_array($vars) && !empty($vars)) {
    extract($vars); //now these variable can be used in template by there key 
 }
  //starts buffering 
  ob_start();
  require($filename);
  return ob_get_clean();//return buffer 
}
