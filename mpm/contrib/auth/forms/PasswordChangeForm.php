<?php
namespace Mpm\Contrib\Auth\Forms;
use Mpm\Forms as forms;
use Mpm\Forms\Fields as fields;

class PasswordChangeForm extends forms\Form {
  public $old_password,$new_password,$confirm_new_password;
  public function __construct(){
    $this->old_password = new fields\PasswordField("Old Password",lap:true);
    $this->new_password = new fields\PasswordField("New Password",lap:true);
    $this->confirm_new_password = new fields\PasswordField("Confirm New Password",lap:true);
  }
}