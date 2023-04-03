<?php
/**
 * @author  Raaz Maholia <maholialekhraj46@gmail.com>
 * 
 * HOW TO USE
 * - Import the file into your php file .
 * - create a class extending 'Form'  class like --
 *   - class <Foo> extends forms\Form {
 *       function __construct(){
 *          $this->name  = forms\InputField(<label: string>,<max_length:integer>,<show-label:true|false>,<lap(use lable as placeholder):true|false>,<placeholder:string>)
 *       }
 *     }
 * - create a instance of it.
 * - $form = new <Foo>();
 * - to render form ---
 * - echo $form->render_form();
 * - this returns html as follow 
 *   - <input type="text" maxlength="" placeholder=""/>
 * 
 */

namespace Mpm\Forms;
use Mpm\Validation\Validator;
use Mpm\Handlers\FileUploadHandler as FUH;

class Form {
  public $formValues;
  public $error_list;
  public $is_valid;
  
  public function fill_form() {
    if(func_num_args()==2)
      $this->fill_form_including_files(...func_get_args());
    else
     $this->formValues = func_get_args()[0];
     
    $this->validate();
  }
  
  public function fill_form_including_files($postdata,$filedata){
    $formdata = array_merge($postdata,$filedata);
    $this->formValues = $formdata;
  }
  
  public function reset_form() {
    $this->formValues = array();
  }
  
  public function values(){
    return (object)$this->formValues;
  }
  
  /**
   * Return instances that are intance of Mpm\Forms\Field 
   */
  public function instances(){
    $all_instances = get_object_vars($this);
    foreach($all_instances as $name=>&$instance){
      if($instance instanceof Field) continue;
      else unset($all_instances[$name]);
    }
    return $all_instances;
  }
  
  
  /** Returns form data the can be used in Mpm\Validation\Validator::validate() Method .
   * Returned areay is like ["name"=>[$value=>"value","validation"=>['required']],]
   * @return array 
   */
  public  function getPreparedData(){
    $formFields = $this->instances();
    $formData = $this->formValues;
    $preparedData = [];
    //$key is form instance name and $value is its object of type Mpm\Forms\<Foo>Field
    foreach($formFields as $key=>$value){
      if(!isset($value->validation)) continue;
      $fieldValue = (FUH::isFileField($value))?(isset($formData[$key]["name"])?$formData[$key]["name"]:$formData[$key]):$formData[$key];
      $preparedData[$key]=["value"=>$fieldValue,"validation"=>$value->validation];
    }
    return $preparedData;
  }
  
  
  private function validate(){
    $preparedData = $this->getPreparedData();
    $response = Validator::validate($preparedData);
    $this->error_list = $response["error_list"];
    $this->is_valid = $response["valid"];
  }
  
  public function get_errors(){
    return $this->error_list;
  }//get_errors
  
  /**
   * Checks whether form has instance of Mpm\Forms\FileFieid as instance variable 
   * Returns true it has instance of FileFieid .
   * 
   * @return bool
   */
  public function hasFileField(){
   foreach(get_object_vars($this) as $obj){
      if($obj instanceof FileField){
        return true;
      }
    }
    return false;
  }
  
  /**
   * Returns instances of form that are instance of Mpm\Forms\FileField .
   * 
   * @return array 
   */
  public function fileFields(){
    $fileFields = [];
    foreach(get_object_vars($this) as $name=>$obj){
      if($obj instanceof FileField){
        array_push($fileFields,$name);
      }
    }
    return $fileFields;
  }
  
  /** 
   * Returns html code of form. 
   * 
   * @return string 
   */
  public function render_form(){
    $fieldCode = "";
    $fileFields = FUH::fileFields($this);
    $instances = $this->instances(); //Field Instances
    foreach($instances as $name=>$val){
        $val->setName($name);
        if(isset($this->formValues[$name]) &&  !in_array($name,$fileFields))
          $val->setValue($this->formValues[$name]);
        
        if(isset(($this->formValues)) && isset($this->error_list[$name]))
          $val->setErrorList($this->error_list[$name]);
        
        $fieldCode .= $val->create_field();
    }
    return $fieldCode;
  }
}//Class Form



