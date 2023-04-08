<?php
namespace Config\Controller;
use function Mpm\View\render;
use Mpm\Core\Request;


class HomeController {
  public function home(Request $request){
    global $user;
    return render($request,'home.php',array("user"=>$user));
  }
}