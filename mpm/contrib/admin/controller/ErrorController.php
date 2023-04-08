<?php 
namespace Mpm\Contrib\Admin\Controller;
use function Mpm\View\render;
use Mpm\Core\Request;

class ErrorController {
  public function page_not_found(Request $request){
    return render($request,'404.php');
  }

  function permission_denied(Request $request){
    return render($request,'permission_denied.php');
  }
}