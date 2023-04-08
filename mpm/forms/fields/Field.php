<?php
namespace Mpm\Forms\Fields;

define("FIELD_CONTAINER_CLASS","form-field");
define("FIELD_CONTAINER_ID_PREFIX","id-form-field-");
define("FIELD_ID_PREFIX","id-");
define("FIELD_LABEL_ID_PREFIX","id-label-");
define("FIELD_CLASS","form-control");
define("FIELD_LABEL_CLASS","form-label");
define("RADIOGROUP_CLASS","form-check");
define("RADIOFIELD_CLASS","form-check-input");
define("RADIOFIELD_LABEL_CLASS","form-check-label");
define("RADIOGROUP_ID_PREFIX","id-radio-group-");
define("SELECTGROUP_CLASS","form-select");
define("ERROR_PREFIX","error-");
define("ERROR_CLASS","error-list");
define("CHECKFIELD_CLASS","form-check-input");
define("CHECKFIELD_DIV_CLASS","form-check");
define("CHECKFIELD_LABEL_CLASS","form-check-label");

class Field {
  public $label,$value,$validation,$name,$error_list;
 
  protected function __construct($label,$value="",array $validation=[],$error_list=[]){
    $this->label = $label;
    $this->value = $value;
    $this->validation = $validation;
    $this->error_list= $error_list;
  }
  
  /** 
   * Generate Id for form field by merging prefix and suffix.
   * 
   * @param string $prefix eg. id-
   * @param string $suffix eg. this is name of the field.
   * @return string $prefix.$suffix
   */
  protected function generateId($prefix,$suffix){
    $suffix = strtolower(trim($suffix));
    $suffix = str_replace(" ","-",$suffix);
    $id = $prefix . $suffix;
    return $id;
  }
  
  /**
   * Sets name instance variable . 
   * In forms this the value of name attribute
   * 
   * @param string $name
   */
  public function setName($name){
    $this->name = $name;
  }
  
  /** 
   * Sets value instance variable .
   * In forms this  value is value-attribute of the field . 
   * 
   * @param $value
   */
  public function setValue($value){
    $this->value = $value;
  }
  
  public function setErrorList($error_list){
    $this->error_list= $error_list;
  }
}