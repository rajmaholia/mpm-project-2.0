<?php
namespace Mpm\Core;

class Request {
  public $uri,$method,$time,$POST,$GET,$FILES,$COOKIE;
  
  public function __construct(){
    $this->uri = $_SERVER["REQUEST_URI"];
    $this->method = $_SERVER["REQUEST_METHOD"];
    $this->time = $_SERVER["REQUEST_TIME"];
    $this->POST = $_POST;
    $this->GET = $_GET;
    $this->FILES = $_FILES;
    $this->COOKIE = $_COOKIE;
  }
  public static function captureUri(){
    return $_SERVER["REQUEST_URI"];
  }
}