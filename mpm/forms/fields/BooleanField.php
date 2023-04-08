<?php
namespace Mpm\Forms\Fields;



/**
 * Render input field of type checkbox with value 0 or 1; 
 */
class BooleanField extends Field{
  
  /**
   * @var string $name 
   * @var bool   $checked 
   * @var bool   $labelEnd If true Label tag will be after checkbox
   */
  public $name,$label,$labelEnd,$checked;
  function __construct($label,$checked=true,$labelEnd=true){
    parent::__construct($label);
    $this->labelEnd = $labelEnd;
    $this->checked = $checked;
  }
  
  /** Sets checked property to true and checks checkbox in form */
  public function check(){
    $this->checked = true;
  }
  
  /**  Sets checked property to false and unchecks checkbox in form */
  public function uncheck(){
    $this->checked = false;
  }
  
 /** 
  * If value is true or evacuate to true , it calls check() method otherwise class uncheck() .
  * 
  * @param mixed $value 
  */
  public function setValue($value){
    $value?$this->check():$this->uncheck();
  }
  
  public function create_field(){
    $idInput = $this->generateId(FIELD_ID_PREFIX,$this->name);
    $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->name);
    $idError = $this->generateId(ERROR_PREFIX,$this->name);
    $idArray = array($idLabel,$idInput);
    
    $labelTag = "";
    if(!empty($this->label)){
      $labelTag = "<label for='{$idInput}' class='".CHECKFIELD_LABEL_CLASS."'  id='{$idArray[0]}'>$this->label</label>";
    }
    
    $errors = "";
    foreach($this->error_list as $error){
      $errors.="<li>{$error}</li>";
    }
    
    $checked = $this->checked ?"checked":"";
    
    $inputTag = "<input type='checkbox'   name='$this->name' value='1' class='".CHECKFIELD_CLASS."' id='{$idArray[1]}'   $checked/>";
    $errorTag = "<ul class='".ERROR_CLASS."' id='{$idError}'>{$errors}</ul>";
    $fieldHtml = $this->labelEnd ? $inputTag.$labelTag.$errorTag:$labelTag.$inputTag.$errorTag;
    return "<div class='form-field form-check'>{$fieldHtml}</div>";
  }
}

