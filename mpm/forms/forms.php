<?php
/**
 * Author : Raaz Maholia
 * Email : maholialekhraj46@gmail.com
 *
 *      how to use 
 *import the file into your php file
 * create a class extending 'Form'  class like --
 * class <Foo> extends forms\Form {
 *   function __construct(){
 *      $this->name  = forms\InputField(<label: string>,<max_length:integer>,<show-label:true|false>,<lap(use lable as placeholder):true|false>,<placeholder:string>)
 *  }
 *}
 *create a instance of it
 * $form = new <Foo>();
 * to render form ---
 * echo $form->render_form();
 * this returns html as follow 
 * <input type="text" maxlength="" placeholder=""/>
 */
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

class Form {
  public $formValues;
  public $error_list;
  protected function generateId($prefix,$suffix){
    $suffix = strtolower(trim($suffix));
    $suffix = str_replace(" ","-",$suffix);
    $id = $prefix . $suffix;
    return $id;
  }
  public function fill_form($form_values) {
    $this->formValues = $form_values;
  }
  public function reset_form() {
    $this->formValues = array();
  }
  
  public function render_form(){
    $fieldCode = "";
    foreach(get_object_vars($this) as $name=>$val){
       if($name!='formValues' && $name!='error_list'){
        $val->setName($name);
        if(isset($this->formValues[$name])){;
          $val->setValue($this->formValues[$name]);
        }
        
        if(($this->formValues!=null) && isset($this->error_list[$name])){
          $val->setErrorList($this->error_list[$name]);
        }
        
        $fieldCode .= $val->create_field();
      }//if ! formValues
    }
    return $fieldCode;
  }
  
  public function get_errors(){
    $error_list = array();
    $formData = $this->formValues;
    foreach(get_object_vars($this) as $key=>$value) {
    if($key!='formValues' && $key!='error_list') {
     if(isset(get_object_vars($value)['required'])) {
        $is_required =  get_object_vars($value)['required'];
        if($is_required==true && empty($formData[$key])) {
          $error_list[$key]=array("Required");
          
        }
      }
      if(!is_array(get_object_vars($value)) && isset(get_object_vars($value)['max_length']) && isset($formData[$key])) {
        $max_length =  get_object_vars($value)['max_length'];
        if(strlen($formData[$key])>$max_length) {
          $error_list[$key]=array("{$key} must not exceed {$max_length} Characters");
        }
      } 
      }
    }//foreach
    $this->error_list = $error_list;
    return $this->error_list;
  }//is_valid
}//Class Form


class Field {
  protected function __construct($required,$value,$error_list){
    $this->required = $required;
    $this->value = $value;
    $this->error_list= $error_list;
  }
  
  
  protected function generateId($prefix,$suffix){
    $suffix = strtolower(trim($suffix));
    $suffix = str_replace(" ","-",$suffix);
    $id = $prefix . $suffix;
    return $id;
  }
}

class TextField extends Field {
  public $label,$rows,$cols,$showLabel,$lap,$placeholder,$name,$column,$required,$value,$error_list;
  function __construct($label,$rows=10,$cols=8,$showLabel=true,$lap=true,$placeholder="",$required=true,$value="",$error_list=array()){
    parent::__construct($required,$value,$error_list);
    $this->label = $label;
    $this->rows = $rows;
    $this->cols = $cols;
    $this->showLabel = $showLabel;
    $this->lap = $lap;
    $this->placeholder = $placeholder;
    $this->value = $value;
  }
  
  public function setName($n){
    $this->name = $n;
  }
  
  public function setValue($value){
    $this->value = $value;
  }
  
  public function setErrorList($errors){
    $this->error_list = $errors;
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
 
  public $label,$showLabel,$lap,$placeholder,$max_length,$type,$name,$required,$value,$error_list;
  
  function __construct($label,$max_length=250,$showLabel=true,$lap=false,$placeholder="",$required=true,$value='',$error_list=array()){
      parent::__construct($required,$value,$error_list);
      $this->label = $label;
      $this->max_length = $max_length;
      $this->showLabel = $showLabel;
      $this->lap = $lap;
      $this->placeholder = $placeholder;
      $this->type = "text";
      $this->value = $value;
  }
  
  public function setName($n){
    $this->name = $n;
  }
  public function setValue($value){
    $this->value = $value;
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

      $input = "<input  type='$this->type'   name='$this->name' class='class-form-control' id=\"$idArray[1]\"  style='display: block;width:100%; font-size:16px;' placeholder=\"$this->placeholder\" value=\"$this->value\"/>
        <ul class='error error-list' id='$idError'>{$errors}</ul>
      ";
    return  "<div class='form-field'>".$labell . $input."</div>";
  }
}

class NumberField extends InputField{
  function __construct($label,$max_length=12,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$max_length,$showLabel,$lap,$placeholder);
      $this->type = "number";
  }
}


class EmailField extends InputField{
  function __construct($label,$max_length=12,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$max_length,$showLabel,$lap,$placeholder);
      $this->type = "email";
  }
}

class DateField extends InputField {
  function __construct($label,$max_length=12,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$max_length,$showLabel,$lap,$placeholder);
      $this->type = "date";
  }
}

class PasswordField extends InputField {
  function __construct($label,$max_length=12,$showLabel=true,$lap=false,$placeholder=""){
      parent::__construct($label,$max_length,$showLabel,$lap,$placeholder);
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
  
  public function setName($n){
    $this->name = $n;
  }
  public function setValue($value){
    $this->value = $value;
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
  public $label,$showLabel,$name,$required,$value,$error_list,$multiple,$type;
  
  function __construct($label,$showLabel=true,$required=true,$value='',$error_list=array(),$multiple=''){
    parent::__construct($required,$value,$error_list);
      $this->label = $label;
      $this->showLabel = $showLabel;
      $this->type = "file";
      $this->multiple = $multiple;
      $this->value = $value;
  }
  
  public function setName($n){
    $this->name = $n;
  }
  public function setValue($value){
    $this->value = $value;
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
       // maxlength=\"$this->max_length\"
        $input = "<input  type='$this->type'   name='$this->name' class='class-form-control' id=\"$idArray[1]\"  style='display: block;width:100%; font-size:16px;' value=\"$this->value\" {$this->multiple}/>
          <ul class='error error-list' id='$idError'>{$errors}</ul>
        ";
    return  "<div class='form-field'>".$labell . $input."</div>";
  }
}

