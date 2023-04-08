<?php
namespace Mpm\Contrib\Auth\Forms;
use Mpm\Forms as forms;
use Mpm\Forms\Fields as fields;

/**
 * Form Structure of User Change 
 */
class UserChangeForm extends forms\Form {
  public $username,$fullname,$mobile_number,$is_staff,$email,$joined_on;

  public function __construct(){
    $this->username = new fields\InputField("Username",lap:true);
    $this->fullname = new fields\InputField("Full Name",lap:true,validation:["required"=>false,"maxlength"=>250]);
    $this->mobile_number = new fields\NumberField("Mobile Number",lap:true, validation:["required"=>false,"maxlength"=>10]);
    $this->is_staff = new fields\BooleanField("Staff Status",checked:false,labelEnd:false);
    $this->email = new fields\EmailField("Email", validation:["required"=>false]);
    $this->joined_on = new fields\DateTimeField("Joined On");
  }
}