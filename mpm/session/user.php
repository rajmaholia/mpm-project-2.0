<?php
namespace Mpm\Session;

class User {
  public $id,$username,$password,$email,$is_staff,$joined_on,$mobile_number,$fullname;
  public function  __construct(){
    $this->id              = Session::getVarArray('user','id');
    $this->username        = Session::getVarArray('user','username');
    $this->password        = Session::getVarArray('user','password');
    $this->is_staff        = Session::getVarArray('user','is_staff');
    $this->joined_on       = Session::getVarArray('user','joined_on');
    $this->mobile_number   = Session::getVarArray('user','mobile_number');
    $this->fullname        = Session::getVarArray('user','fullname');
 }
}
