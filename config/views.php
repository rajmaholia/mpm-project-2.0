<?php
use function Mpm\View\render;


function home($server){
  global $user;
  return render($server,'home.php',array("user"=>$user));
}