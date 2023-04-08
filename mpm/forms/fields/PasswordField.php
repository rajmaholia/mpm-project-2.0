<?php
namespace Mpm\Forms\Fields;

class PasswordField extends InputField {
  function __construct($label,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder);
      $this->type = "password";
  }
}