<?php
namespace Mpm\Validation;


class Validator {
  /**
   * This is a Validator Class 
   */
   
   /**
    * Error Message Templates for errors 
    * 
    * @static 
    * @var array 
    */
   public static $errorMsg = [
    "required"=>"{name} field is required",
    "regex"=>"{name} field should match regex {regex}",
    "length"=>"{name} must be {length} {datatype} long",
    "minlength"=>"minimum {length} {datatype} are required",
    "maxlength"=>"maximum {length} {datatype} are allowed",
    "mimes"=>"only {mimes} files are allowed",
    ];
  
  
  /** 
   * @static
   * @param string $validtionName eg "required"
   * @param array  $args  ["name"=>"title"] in self::$errorMsg["required"] string's `{name}` will be overrided by 'title'
   * @return string  "{name} is required" changes to  "title is required".
   * */
  public static function prepareError($validationName,$args){
    $errorStr = self::$errorMsg[$validationName];
    $array_keys = array_map(fn($value)=>"{".$value."}",array_keys($args));
    return str_replace($array_keys,array_values($args),$errorStr);
  }
  
  /** 
   * @static
   * @param array  $data ["title"=>["value"=>"Home","validation"=>["required"=>true,"maxlength"=>20]],]
   * @return array ["valid"=>true|false,"error_list"=>["title"=>["errors"=>[]],]]
   */
  public static function validate($data){
    $responseArray = ["valid"=>true,"error_list"=>[]];
    foreach($data as $key=>$value) {
      $response = self::validate_value($key,$value['value'],$value['validation']);
      $errors = $response["error_list"];
      if(count($errors)!=0) $responseArray["valid"]=false;
      $responseArray["error_list"][$key] = $errors;
    }
    return $responseArray;
  }
  
  
  /**
   * @static
   * @param string $name name of the field.
   * @param string $value value of the field.
   * @param array $validations Eg . ["required"=>true,"maxlength"=>250].
   * @return array ["errors"=>["this field is required"]].
   */
  public static function validate_value($name,$value,$validations){
    $errors = array();
    foreach($validations as $validation=>$validation_value) {
      switch($validation){
        case "required":
          if($validation_value==true){
            if(self::checkempty($value)){
              array_push($errors,self::prepareError("required",["name"=>"This"]));
            }
          }
          break;
          
        case "regex":
          if(!self::checkregex($value,$validation_value)){
            array_push($errors,self::prepareError("regex",["name"=>"This","regex"=>$validation_value]));
          }
          break;
        
        case "length":
          if(!self::checklength($value,$validation_value)){
            array_push($errors,self::prepareError("length",["name"=>"This","length"=>$validation_value,"datatype"=>"characters"]));
          }
          break;
          
        case "minlength":
          if(!self::checkminlen($value,$validation_value)){
            array_push($errors,self::prepareError("minlength",["length"=>$validation_value,"datatype"=>"characters"]));
          }
          break;
          
        case "maxlength":
          if(!self::checkmaxlen($value,$validation_value)){
            array_push($errors,self::prepareError("maxlength",["length"=>$validation_value,"datatype"=>"characters"]));
          }
          break;
          
        case "mimes":
          $value = is_array($value)?$value:array($value);
          if(!self::checkmimes($value,$validation_value)){
            $validation_value = join(' , ',$validation_value);
            array_push($errors,self::prepareError("mimes",["mimes"=>$validation_value]));
          }
          break;
      }//switch
    }
  
    $response  = ["error_list"=>$errors];
    return $response;
  }//validate value
  
  /**
   * Check whether arguement 1 matches, Regular Expression of argument 2 .
   * 
   * @static
   * @param string|int $value This is the value to Matched 
   * @param string $regex Regex to be matched .
   * @return bool true id matched else false
   */
  public  static function checkregex($value,$regex){
    $regex = "@^".$regex."$@";
    return preg_match($regex,$value);
  }
  
  /**
   * Check Passed parameters are strictly equal . Returns true if Both are equal.
   * 
   * @static 
   * @param string|int $data1 
   * @param string|int $data2 
   * @return bool
   */
  public static function  checkequal($data1,$data2){
    return ($data1===$data2)?true:false;
  }
  
  /**
   * Checks whether Passed parameter is valid email address. Returns true if Parameter is valid email. 
   * 
   * @static 
   * @param string $email 
   * @return bool 
   */
  public static function checkemail($email){
   return filter_var($email,FILTER_VALIDATE_EMAIL)?true:false;
  }
  
  /**
   * Checks whether parameter length is equal to a given value of second parameter 
   * Returns true if value's length is equal to given  length
   * 
   * @static 
   * @param string $data 
   * @param int $length 
   * @return bool 
   */
  public static function checklength($data,$length) {
    if(is_null($data)) return false;
    $data = trim($data);
    return strlen($data)==$length?true:false;
  }//checklength

/**
 * Checks whether length of parameter 1 is equal or greater than a given digit .
 * 
 * @static
 * @param string $data 
 * @param int $length 
 * @return bool 
 */
public static function checkminlen($data,$length){
  if(is_null($data)) return false;
  $data = trim($data);
  return strlen($data)>=$length?true:false;
}

  /**
   * Checks whether length of parameter 1 is equal or less than a given digit .
   * 
   * @static
   * @param string $data 
   * @param int $length 
   * @return bool 
   */
  public static function checkmaxlen($data,$length){
    if(is_null($data)) return true;
    $data = trim($data);
    return strlen($data)<=$length?true:false;
  }

/**
 * Checks whether parameters is a emptu string .
 * 
 * @static
 * @param string $data 
 * @return bool 
 */
  public static function checkempty($data){
    if(is_null($data)) return true;
    $data = trim($data);
    return empty($data)?true:false;
  }
  
  /**
   * Check whether mimetype of filename is in given array of mimes . 
   * 
   * @static 
   * @param string $filename 
   * @param array $mimes
   * @return bool
   */
  public static function checkmime($filename,$mimes){
    if(!is_file($filename)) return true;
    $mime = pathinfo($filename,PATHINFO_EXTENSION);
    $mimes = array_map("strtolower",$mimes);
    $mime = strtolower($mime);
    return in_array($mime,$mimes)?true:false;
  }
  
  /**
   * Check whether mimetypes of array of filenames is in given array of mimes . 
   * 
   * @static 
   * @param array $filenames
   * @param array $mimes
   * @return bool
   */
  public static function checkmimes($filenames,$mimes){
    foreach($filenames as $filename) {
      if(!self::checkmime($filename,$mimes)) return false;
    }
    return true;
  }
  
  /**
   * checks passed string is a valid json ,and returns true ( or decoded array if second parameter is set  to true) if string is a json string
   * 
   * @param string $string 
   * @param bool $return_data default false
   * @return bool|array
   */
  public static function checkjson($string,$return_data=false){
    $data = json_decode($string,true);
    return (json_last_error() == JSON_ERROR_NONE) ? ($return_data ? $data : TRUE) : FALSE;
  }
  
  public static function clean($data){
    if(is_string($data)) return self::clean_input($data);
    $cleaned_data = [];
    foreach($data as $key=>$data){
      $data = self::clean_input($data);
      $cleaned_data[$key] = $data;
    }
    return $cleaned_data;
  }
  
  /**
   * Sanitizes User Input 
   * 
   * @param string $value 
   * @return 
   */
  public static function clean_input($value){
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    return $value;
  }
  
}//class Validator