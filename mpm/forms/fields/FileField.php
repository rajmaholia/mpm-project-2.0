<?php
namespace Mpm\Forms\Fields;

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