<?php
namespace Mpm\Forms\Fields;

class TextField extends Field {
  public $label,$rows,$cols,$showLabel,$lap,$placeholder,$name,$column,$validation,$value,$error_list;
  
  function __construct($label,$rows=10,$cols=20,$showLabel=true,$lap=true,$placeholder="",$validation=['required'=>true],$value="",$error_list=array()){
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

