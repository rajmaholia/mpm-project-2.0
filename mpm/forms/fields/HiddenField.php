<?php
namespace Mpm\Forms\Fields;

class HiddenField extends InputField {
  public $value;
  function __construct($label,$value){
      parent::__construct($label,$value);
      $this->type = "hidden";
  }
}