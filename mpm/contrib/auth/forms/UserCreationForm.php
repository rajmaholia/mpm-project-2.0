<?php
namespace Mpm\Contrib\Auth\Forms;
use Mpm\Forms as forms;
use Mpm\Forms\Fields as fields;

class UserCreationForm extends forms\Form {
  public $username,$fullname,$password,$confirm_password,$mobile_number;
  
  public static $fields = ['username','fullname','password','mobile_number'];
  
  public function __construct(){
    $this->username = new fields\InputField("Username",lap:true);
    $this->fullname = new fields\InputField("Full Name",lap:true);
    $this->password = new fields\PasswordField("Password",lap:true);
    $this->confirm_password = new fields\PasswordField("Confirm Password",lap:true);
    $this->mobile_number= new fields\NumberField("Mobile Number",lap:true);
  }
}