<?php
namespace Mpm\Contrib\Auth\Forms;
use Mpm\Forms as forms;
use Mpm\Forms\Fields as fields;


class UserLoginForm extends forms\Form {
  public $username,$password;
  public function __construct(){
    $this->username = new fields\InputField("Username",lap:true);
    $this->password = new fields\PasswordField("Password",lap:true);
  }
}