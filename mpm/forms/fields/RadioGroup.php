<?php
namespace Mpm\Forms\Fields;

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
