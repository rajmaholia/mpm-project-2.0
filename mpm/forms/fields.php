<?php
namespace Mpm\Forms;

define("FIELD_ID_PREFIX","id-");
define("FIELD_LABEL_ID_PREFIX", "id-label-");
define("FIELD_CLASS" , "class-form-control");
define("FIELD_LABEL_CLASS","class-form-label");
define("RADIOGROUP_CLASS","class-radio-group");
define("RADIOGROUP_ID_PREFIX", "id-radio-group-");
define("SELECTGROUP_CLASS","class-select-group");
define("SELECTGROUP_ID_PREFIX", "id-select-group-");
define("ERROR_PREFIX", "error-");


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
    if($this->lap==true){
          $this->placeholder = $this->label;
    }
    if(!empty($this->label) && $this->showLabel) {
      $labell = "<label class='class-form-label' id='$idArray[0]'>$this->label</label>";
    }
    $errors = "";
    foreach($this->error_list as $error){
      $errors.="<li>{$error}</li>";
    }
    
    $textarea= "<textarea name='$this->name'  rows=\"$this->rows\" cols=\"$this->cols\" class='class-form-control' id=\"$idArray[1]\" style='width:100%' placeholder=\"$this->placeholder\">{$this->value}</textarea>
    <ul class='error error-list' id='$idError'>{$errors}
    </ul>
    ";
    $htmlCode = "<div class='form-field'>".$labell . $textarea."</div>";
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
      $labell = "<label class=\"class-form-label\"  id=\"$idArray[0]\">$this->label</label>";
    }
      $errors = "";
      foreach($this->error_list as $error){
        $errors.="<li>{$error}</li>";
      }

      $input = "<input type='$this->type'   name='$this->name' class='class-form-control' id=\"$idArray[1]\"  style='display: block;width:100%; font-size:16px;' placeholder=\"$this->placeholder\" value=\"$this->value\"/>
        <ul class='error error-list' id='$idError'>{$errors}</ul>
      ";
    return  "<div class='form-field'>".$labell . $input."</div>";
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
  public $name,$labelEnd,$checked;
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
    $idInput = $this->generateId(FIELD_ID_PREFIX,$this->label);
    $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->label);
    $idError = $this->generateId(ERROR_PREFIX,$this->label);
    $idArray = array($idLabel,$idInput);
    
    $labelTag = "";
    if(!empty($this->label)){
      $labelTag = "<label for=$idInput class=\"form-check-label\"  id=\"$idArray[0]\">$this->label</label>";
    }
    
    $errors = "";
    foreach($this->error_list as $error){
      $errors.="<li>{$error}</li>";
    }
    
    $checked = $this->checked ?"checked":"";
    
    $inputTag = "<input type='checkbox'   name='$this->name' value='1' class='form-check-input' id=\"$idArray[1]\"   $checked/>";
    $errorTag = "<ul class='error error-list' id='$idError'>{$errors}</ul>";
    $fieldHtml = $this->labelEnd ? $inputTag.$labelTag.$errorTag:$labelTag.$inputTag.$errorTag;
    return "<div class='form-field form-check'>".$fieldHtml."</div>";
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
  public $name,$values,$type,$labelEnd,$check,$title,$multiple,$value='';
  function __construct($title,iterable $values,$type="radio",$check=0,$labelEnd=true,$multiple=''){
    $this->title = $title;
    $this->values = $values;
    $this->type = $type;
    $this->labelEnd = $labelEnd;
    $this->check = $check;
    $this->multiple = $multiple;
  }
  
  function create_field(){
    $count=1;
    $codeLine = '';
    foreach($this->values as $value) {
      if($this->check == $count) {
        $checked="checked";
        $selected = "selected";
      } else {
        $checked = " ";
        $selected = " ";
      }
      $form_value = $value[0];
      $display_value = $value[1];
      $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$form_value);
      $idInput = $this->generateId(FIELD_ID_PREFIX,$form_value)."-$count";
      $ids = array($idLabel,$idInput);
      $container_id = $this->generateId(RADIOGROUP_ID_PREFIX,$this->name);
     
      if($this->type=='select'){
        $field = "<option value='$form_value' $selected>$display_value</option>";
        $label = "";
      } else {
        $field = "<input  type=\"$this->type\" name=\"$this->name\" value=\"$form_value\" id=\"$ids[1]\" class='".FIELD_CLASS."' $checked /> ";
        $label = "<label  for=\"$ids[1]\" class='".FIELD_LABEL_CLASS."' id=\"$ids[0]\">$display_value</label>";
      }
      $codeLine .= $this->labelEnd?$field . $label : $label . $field;
      $codeLine = "<div style='display:flex;flex-wrap:wrap; width:360px'>".$codeLine."</div>";
      $count++;
    }
    if($this->type=="select"){
      $idContainer = $this->generateId(SELECTGROUP_ID_PREFIX,$this->name);
     $errorId = $this->generateId(ERROR_PREFIX,$this->name);
      $htmlCode = "<div class='form-field'>
      <label for='$idContainer'>$this->title</label>
      <select name='$this->name' id=\"$idContainer\" style='width:100%;padding:5px' class='" . SELECTGROUP_CLASS . " class-form-control' $this->multiple>" . $codeLine . "</select>
      <span class='error' id='$errorId'></span><div>
      ";
    } else {
    $idContainer = $this->generateId(RADIOGROUP_ID_PREFIX,$this->name);
    $htmlCode = "<div  id=\"$idContainer\" class='form-field ".RADIOGROUP_CLASS."'>". $codeLine . "</div>";
    }
    return $htmlCode;
  }
}//namespace



class FileField extends Field{
  public $label,$showLabel,$name,$validation,$value,$error_list,$multiple,$type,$upload_to;
  
  function __construct($label,$value=[],$showLabel=true,$validation=['required'=>true],$error_list=array(),$multiple='',$upload_to=""){
      parent::__construct($value,$validation,$error_list);
      $this->label = $label;
      $this->showLabel = $showLabel;
      $this->type = "file";
      $this->multiple = $multiple;
      $this->upload_to = $upload_to;
  }
  
  public function setName($n){
    $this->name = $n;
  }
 
 
  public function setErrorList($errors){
    $this->error_list = $errors;
  }
  public function create_field(){
    $idInput = $this->generateId(FIELD_ID_PREFIX,$this->label);
    $idLabel = $this->generateId(FIELD_LABEL_ID_PREFIX,$this->label);
    $idError = $this->generateId(ERROR_PREFIX,$this->label);
    $idArray = array($idLabel,$idInput);
    $labell = "";

    if(!empty($this->label) && $this->showLabel){
      $labell = "<label class=\"class-form-label\"  id=\"$idArray[0]\">$this->label</label>";
    }
      $errors = "";
      foreach($this->error_list as $error){
        $errors.="<li>{$error}</li>";
      }
      if($this->multiple=='multiple') {
        $this->name .='[]';
      }

        $input = "<input  type='$this->type'   name='$this->name' class='class-form-control' id=\"$idArray[1]\"  style='display: block;width:100%; font-size:16px;'  {$this->multiple}/>
          <ul class='error error-list' id='$idError'>{$errors}</ul>
        ";
    return  "<div class='form-field'>".$labell . $input."</div>";
  }
}

