<?php
namespace Mpm\Forms;

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



class TextField extends Field {
  public $label,$rows,$cols,$showLabel,$lap,$placeholder,$name,$column,$validation,$value,$error_list;
  
  function __construct($label,$rows=10,$cols=8,$showLabel=true,$lap=true,$placeholder="",$validation=['required'=>true],$value="",$error_list=array()){
    parent::__construct($label,$value,$validation,$error_list);
    $this->rows = $rows;
    $this->cols = $cols;
    $this->showLabel = $showLabel;
    $this->lap = $lap;
    $this->placeholder = $placeholder;
  }

  public function create_field(){
    $idInput = $this->generateId(FIELD_ID_PREFIX,$this->label);
    $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->label);
    $idError = $this->generateId(ERROR_PREFIX,$this->label);
    $idArray = array($idLabel,$idInput);
    if($this->lap==true) $this->placeholder = $this->label;
    if(!empty($this->label) && $this->showLabel) {
      $labell = "<label class='".FIELD_LABEL_CLASS."' id='{$idLabel}'>$this->label</label>";
    }
          
    $errors = "";
    foreach($this->error_list as $error){
      $errors.="<li>{$error}</li>";
    }
    
    $textarea= "
    <textarea name='{$this->name}'  rows='{$this->rows}' cols='{$this->cols}' class='".FIELD_CLASS."' id='{$idInput}'  placeholder='{$this->placeholder}'>{$this->value}</textarea>
    <ul class='".ERROR_CLASS."' id='{$idError}'>{$errors}</ul>
    ";
    $htmlCode = "<div class='".FIELD_CONTAINER_CLASS."' >{$labell} {$textarea}</div>";
    return $htmlCode;
  }
}


class InputField extends Field {
 
  public $label,$showLabel,$lap,$placeholder,$type,$name,$validation,$value,$error_list;
  
  function __construct($label,$showLabel=true,$lap=false,$placeholder="",$validation=['required'=>true,"maxlength"=>250],$value='',$error_list=array()){
      parent::__construct($label,$value,$validation,$error_list);
      $this->showLabel = $showLabel;
      $this->lap = $lap;
      $this->placeholder = $placeholder;
      $this->type = "text";
  }

  public function create_field(){
    $idInput = $this->generateId(FIELD_ID_PREFIX,$this->label);
    $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->label);
    $idError = $this->generateId(ERROR_PREFIX,$this->label);
    $idArray = array($idLabel,$idInput);
    $labell = "";
    if($this->lap==true){
       $this->placeholder = $this->label;
    }
    if(!empty($this->label) && $this->showLabel){
      $labell = "<label class='".FIELD_LABEL_CLASS."'  id='{$idArray[0]}'>$this->label</label>";
    }
      $errors = "";
      foreach($this->error_list as $error){
        $errors.="<li>{$error}</li>";
      }

      $input = "<input type='{$this->type}' name='{$this->name}' class='".FIELD_CLASS."' id='{$idArray[1]}'  placeholder='{$this->placeholder}' value='{$this->value}'/>
        <ul class='".ERROR_CLASS."' id='{$idError}'>{$errors}</ul>
      ";
    return  "<div class='".FIELD_CONTAINER_CLASS."'>{$labell} {$input}</div>";
  }
}


class NumberField extends InputField{
  function __construct($label,$validation=["required"=>true,"maxlength"=>12],$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder,$validation);
      $this->type = "number";
  }
}


class EmailField extends InputField{
  function __construct($label,$validation=["required"=>true,"maxlength"=>50],$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder,$validation);
      $this->type = "email";
  }
}


class DateField extends InputField {
  function __construct($label,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder);
      $this->type = "date";
  }
}


class TimeField extends InputField {
  function __construct($label,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder);
      $this->type = "time";
  }
}


class DateTimeField extends InputField {
  function __construct($label,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder);
      $this->type = "datetime-local";
  }
}


class PasswordField extends InputField {
  function __construct($label,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$showLabel,$lap,$placeholder);
      $this->type = "password";
  }
}


class HiddenField extends InputField {
  public $value;
  function __construct($label,$value){
      parent::__construct($label,$value);
      $this->type = "hidden";
  }
}


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


/**
 * Renders checkbox , radio field and select box .
 * 
 * @param string $title 
 * @param array $values [["val","Value"]] First item of each array is real value and second is displaye value
 * @param string $type Type of field radio|checkbox|select 
 * @param int $check Default Value 
 * @param bool $labelEnd 
 * @param string $multiple "multiple"|"" for select box 
 * 
 */
class RadioGroup extends Field {
  public $name,$choices,$type,$labelEnd,$title,$multiple,$value;
  function __construct($title,iterable $choices,$value=null,$labelEnd=true,$multiple=false){
    $this->title = $title;
    $this->choices = $choices;
    $this->labelEnd = $labelEnd;
    $this->value = $value;
    $this->multiple = $multiple;
  }
  
  public function setValue($value){
    $values = array_column($this->choices,0);
    in_array($value,$values)?$this->value = $value:null;
    if($this->multiple && is_array($value)){
      foreach($value as $key=>&$option) {
       if(!in_array($option,$values)) unset($value[$key]);
      }
      $this->value = $value;
    }
  }
  
  function create_field(){
    $codeLine = '';
    if($this->multiple){ $this->type = "checkbox";$this->name.='[]';}
    else {$this->type = "radio";}
    $count = 1;
    foreach($this->choices as $value) {
      if($this->multiple && is_array($this->value) && in_array($value[0],$this->value)) $checked="checked";
      elseif($this->value == $value[0]) $checked="checked";
      else $checked = "";
      
      $form_value = $value[0];//Value to display in value field and stored in database
      $display_value = $value[1];//Displayed as field label in UI
      $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$form_value);
      $idInput = $this->generateId(FIELD_ID_PREFIX,$form_value)."-$count";
      $container_id = $this->generateId(RADIOGROUP_ID_PREFIX,$this->name);
     
      $field = "<input  type='{$this->type}' name='{$this->name}' value='{$form_value}' id='{$idInput}' class='".RADIOFIELD_CLASS."' $checked /> ";
      $label = "<label  for='{$idInput}' class='".RADIOFIELD_LABEL_CLASS."' id='{$idLabel}'>$display_value</label>";
      $codeLine .= $this->labelEnd?$field . $label : $label . $field;
      $codeLine = "<div id='{$container_id}' class='".RADIOGROUP_CLASS."'>{$codeLine}</div>";
      $count++;
    }

    $idContainer = $this->generateId(FIELD_CONTAINER_ID_PREFIX,$this->name);
    $htmlCode = "<div  id='{$idContainer}' class='".FIELD_CONTAINER_CLASS."'>{$codeLine}</div>";
    return $htmlCode;
  }
}//namespace

/** Renders Select Box **/
class SelectField extends Field {
  public $name,$choices,$selected,$label,$multiple,$value='';
  function __construct($label,iterable $choices,$selected=0,$multiple=false){
    $this->label = $label;
    $this->choices = $choices;
    $this->selected = $selected;
    $this->multiple = $multiple;
  }
  public function setValue($value){
    $values = array_column($this->choices,0);
    if($this->multiple && is_array($value)){
      foreach($value as $key=>&$option) {
       if(!in_array($option,$values)) unset($value[$key]);
      }
      $this->value = $value;
    }
    else {in_array($value,$values)?$this->value = $value:null;}
  }
  
  public function create_field(){
    if($this->multiple){$multiple = "multiple";$this->name.="[]";}
    else {$multiple="";}
    $labelID = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->name);
    $fieldID = $this->generateId(FIELD_ID_PREFIX,$this->name);
    $fieldContainerID = $this->generateId(FIELD_CONTAINER_ID_PREFIX,$this->name);
    $options = "";
    $selected = "";
    foreach($this->choices as $key=>$choice){
      if($choice[0] == $this->selected || ($this->multiple && is_array($this->selected) && in_array($choice[0],$this->selected))) $selected = "selected";
 
      $options .= "<option value='{$choice[0]}' {$selected}>{$choice[1]}</option>";
      $selected = "";
    }
    $label   = "<label id='{$labelID}' for='{$fieldID}' class=''>$this->label</label>";
    $field   = "<select name='{$this->name}' class='".SELECTGROUP_CLASS."' id='{$fieldID}' {$multiple}>$options</select>";
    $htmlCode = "<div id='{$fieldContainerID}' class='".FIELD_CONTAINER_CLASS."'>$label $field</div> ";
    return $htmlCode;
  }
}


class FileField extends Field{
  public $label,$showLabel,$name,$validation,$value,$error_list,$multiple,$type,$upload_to;
  
  function __construct($label,$value=[],$showLabel=true,$validation=['required'=>true],$error_list=array(),$multiple=false,$upload_to=""){
      parent::__construct($value,$validation,$error_list);
      $this->label = $label;
      $this->showLabel = $showLabel;
      $this->type = "file";
      $this->multiple = $multiple;
      $this->upload_to = $upload_to;
  }
  
  public function create_field(){
    $idInput = $this->generateId(FIELD_ID_PREFIX,$this->label);
    $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->label);
    $idError = $this->generateId(ERROR_PREFIX,$this->label);
    $idArray = array($idLabel,$idInput);

    if(!empty($this->label) && $this->showLabel)
      $label= "<label class='".FIELD_LABEL_CLASS."'  id='{$idArray[0]}'>$this->label</label>";
    else $label = "";
    
    $errors = "";
    foreach($this->error_list as $error){
      $errors.="<li>{$error}</li>";
    }
    if($this->multiple=='multiple') {$this->name .='[]';$multiple="multiple";}
    else {$multiple="";}
    
    $input = "<input  type='{$this->type}'   name='{$this->name}' class='".FIELD_CLASS."' id='{$idArray[1]}'  {$multiple}/>
              <ul class='".ERROR_CLASS."' id='{$idError}'>{$errors}</ul>
             ";
    return  "<div class='form-field'>{$label} {$input}</div>";
  }
}

