<?php
namespace Mpm\Forms\Fields;

class EmailField extends InputField{
  function __construct($label,$validation=["required"=>true,"maxlength"=>50],$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder,$validation);
      $this->type = "email";
  }
}