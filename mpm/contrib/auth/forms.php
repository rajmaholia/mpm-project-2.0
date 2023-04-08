<?php
namespace Mpm\Contrib\Auth;
use Mpm\Forms as forms;


/**
 * Form Structure of User Change 
 */
class UserChangeForm extends forms\Form {
  public $username,$fullname,$mobile_number,$is_staff,$email,$joined_on;

  public function __construct(){
    $this->username = new forms\InputField("Username",lap:true);
    $this->fullname = new forms\InputField("Full Name",lap:true,validation:["required"=>false,"maxlength"=>250]);
    $this->mobile_number = new forms\NumberField("Mobile Number",lap:true, validation:["required"=>false,"maxlength"=>10]);
    $this->is_staff = new forms\BooleanField("Staff Status",checked:false,labelEnd:false);
    $this->email = new forms\EmailField("Email", validation:["required"=>false]);
    $this->joined_on = new forms\DateTimeField("Joined On");
  }
}

class UserCreationForm extends forms\Form {
  public $username,$fullname,$password,$confirm_password,$mobile_number;
  
  public static $fields = ['username','fullname','password','mobile_number'];
  
  public function __construct(){
    $this->username = new forms\InputField("Username",lap:true);
    $this->fullname = new forms\InputField("Full Name",lap:true);
    $this->password = new forms\PasswordField("Password",lap:true);
    $this->confirm_password = new forms\PasswordField("Confirm Password",lap:true);
    $this->mobile_number= new forms\NumberField("Mobile Number",lap:true);
  }
}

class UserLoginForm extends forms\Form {
  public $username,$password;
  public function __construct(){
    $this->username = new forms\InputField("Username",lap:true);
    $this->password = new forms\PasswordField("Password",lap:true);
  }
}


class PasswordChangeForm extends forms\Form {
  public $old_password,$new_password,$confirm_new_password;
  public function __construct(){
    $this->old_password = new forms\PasswordField("Old Password",lap:true);
    $this->new_password = new forms\PasswordField("New Password",lap:true);
    $this->confirm_new_password = new forms\PasswordField("Confirm New Password",lap:true);
  }
}
