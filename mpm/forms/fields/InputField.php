<?php
namespace Mpm\Forms\Fields;

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
