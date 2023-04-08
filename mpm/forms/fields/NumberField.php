<?php
namespace Mpm\Forms\Fields;

class NumberField extends InputField{
  function __construct($label,$validation=["required"=>true,"maxlength"=>12],$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder,$validation);
      $this->type = "number";
  }
}