<?php
namespace Mpm\Handlers;
use Mpm\Forms\FileField;
use Mpm\Handlers\FileHelper as FH;

class FileUploadHandler {
  public $hasFile,$FILE,$formObj;
  
  /**
   * FileUploadHandler's Constructor 
   * @param Mpm\Forms\Form $formObj This is an instance of Mpm\Forms\Form 
   * @param array $FILE This is $_FILES .
   */
  public function __construct($formObj,$FILE){
    $this->FILE = $FILE;
    $this->formObj = $formObj;
  }
  
  /** 
   * It returns 'true' if Array $this->File is not empty and 'false' of array is empty 
   * 
   * @return bool 
   */
  public function hasFile(){
    return count($this->FILE)>0?true:false;
  }
  
  /** 
   * Returns all fileFields name in a Mpm\Forms\Form instance 
   * 
   * @static
   * @param Form $form 
   * @return array Array Contains form fields that are instance of Mpm\Form\FileField
   */
  public static function fileFields($form){
    $fileFields = [];
    foreach(get_object_vars($form) as $name=>$obj){
      if($obj instanceof FileField){
        array_push($fileFields,$name);
      }
    }
    return $fileFields;
  }
  
  /** 
   * Returns enctype='multipart/form-data' if fileField are in forms. 
   * 
   * @static
   * @param Form $form 
   * @return string $enctype 
   */
  public static function formEnctype($form) {
    $enctype = self::hasFileField($form)?'enctype="multipart/form-data"':"";
    return $enctype;
  }
    
  /** 
   * Returns true if form has Mpm\Forms\FileField instance variable 
   * 
   * @static
   * @param Form $form 
   * @return bool 
   */
  public static function hasFileField($form){
    foreach(get_object_vars($form) as $obj){
      if($obj instanceof FileField){
        return true;
      }
    }
    return false;
  }
  
  /**
   * Check Whether Passed Object is instance of Mpm\Forms\FileField 
   * @static
   * @param Mpm\Auth\Field $field 
   * return bool true|false true if object $field is instanse of Mpm\Forms\FileField
   */
  public static function isFileField($field){
     return ($field instanceof FileField)?true:false;
  }
  
  /**
   * First ,  It checks whether given array contains has json value . Decodes them It it found such values and return modified array.
   * 
   * @static
   * @param array $data 
   * @return array
   */
  public static function normalize($data){
    $data = array_map("json_decode",$data);
    return $data;
  }
  
  /**
   * It takes array with form values to be filled out , 
   * And checks whether it has any filename in array . 
   * Eg . array("name"=>"raaz","image"=>"["thi.jpg"]") is changed to array("name"=>"raaz","image"=>["name"=>"thi.jpg"])
   * 
   * @static
   * @param array $data
   * @return array
   */
   public static function prepareForFiles($data){
     
   }
   
  /**
   * Uploads files in $_FILES 
   * 
   * @static
   * @param array $files  This should be $_FILES 
   * @return array Structure is like array("files_json"=>["fieldname1"=>"[json array of 'name' of uploaded images related with all formfields]"],"error_list"=>"['name']=>["errors,]")
   */
  public static function uploadFiles($files,$upload_dirs){
    $responseArray = ["files_json"=>[],"error_list"=>[]];
    
    foreach($files as $fieldname=>$file) {
      $response = self::uploadFile($file,$upload_dirs[$fieldname]);
      $responseArray["files_json"][$fieldname]=$response["files_json"];
      $responseArray["error_list"][$fieldname]=$response["error_list"];
    }
    return $responseArray;
  }
  
  
  /**
   * Uploads File related with single 'name'  
   * 
   * @static
   * @param array $files This is $_FILES['foo'] 
   * @return array ["error_list"=>[...],"files_json"=>"[...]"]
   */
  public static function uploadFile($files,$dir){
    $UPLOAD_KEY = rand(100,999)."".time();
    $dir = trim($dir);
   $dir = substr($dir,-1,1)!="/"?$dir."/":$dir;
      /** This Code block Changes the valuea thE FILES Keys in array if only one file is to  uploaded **/
    if(!is_array($files['name'])) {
      $file_array = array();
      foreach($files as $key=>$value) {
        $file_array[$key] = array($value);
      }
      $files = $file_array;
    }
  

    if(!file_exists(MEDIA_ROOT)) mkdir(MEDIA_ROOT);
    $target_dir = MEDIA_ROOT.$dir;//Target Directory to store files 
    if(!file_exists($target_dir)) FH::make_dir($target_dir);
    $response   = [];
    $errors     = [];
    $filesarray = [];
    for($i = 0; $i<count($files["name"]);$i++){
      $targetFile = $target_dir .$UPLOAD_KEY . basename($files["name"][$i]);
      if(file_exists($targetFile)) {
        $targetFile = $target_dir .$UPLOAD_KEY."".mt_rand(10000,99999).basename($files["name"][$i]);
      }
      
     if(move_uploaded_file($files["tmp_name"][$i],$targetFile)){
       array_push($filesarray,$dir.basename($targetFile,'.$fileType'));
     } else {
       array_push($errors,"Error : Couldn't Upload ".basename($files["name"][$i]));
     }
    }//for-loop upload file one by one;
    
    $response['error_list'] = $errors;
    $response['files_json'] = json_encode($filesarray);
    return $response;
  }//upload_file_handler
}