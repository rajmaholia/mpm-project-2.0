<?php
namespace Mpm\Session;

class Session {
  /**
   * Session Id 
   */
  public $id;
  
  public static function start(){
    session_start();
  }
  
  public static function destroy(){
    session_destroy();
  }
  
  public static function setVar($var,$value){
    $_SESSION[$var] = $value;
  }

/*Get The Session Variable*/
  public static function getVar($var){
    if(isset($_SESSION[$var])) {
     return  $_SESSION[$var];
     } else {
       return null;
     }
  }
  
  /*Get the session variable value from an array*/
  public static function getVarArray($arr,$key){
    $arr = isset($_SESSION[$arr])?$_SESSION[$arr]:null;
    return ($arr==null)?null:(isset($arr[$key])?$arr[$key]:null);
  }
  
  /* Unset Session Variable */
  public static function unsetVar($var) {
    unset($_SESSION[$var]);
  }
  
  public function getId(){
    return session_id();
  }

  
  public function getToken(){
    
  }
}