<?php
namespace Mpm\Forms;

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