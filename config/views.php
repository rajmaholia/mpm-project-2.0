<?php
use function Mpm\View\render;
use Mpm\Core\Request;

function home(Request $request){
  global $user;
  return render($request,'home.php',array("user"=>$user));
}