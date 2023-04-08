<?php
namespace Mpm\Forms\Fields;

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
