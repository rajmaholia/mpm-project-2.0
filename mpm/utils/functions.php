<?php
namespace Mpm\Utils;


/**
 * Truncates and tring to a given length and pads the string with '...' by default.
 * 
 * @param string $string 
 * @param int $limit 
 * @param string $pad 
 * @return string
 */
function truncate($string, $limit, $pad = "...")
{
  if(strlen($string) <= $limit){
    return $string;
  } else {
      $string = substr($string,0 ,$limit) . $pad;
    return $string;
  }
}

/**
 * @param string $value
 * @return string 
 */
function quote($value){
  return "'".$value."'";
}

/**
 * Gets Multidimensional array and If there is any json value is the array , decodes it and returns modified array .
 * 
 * @param array $data 
 * @return array 
 */
function normalize(array $data){
  foreach($data as &$array){
   $array = normalize_one($array);
  }
  return $data;
}

/**
 * Gets array and If there is any json value is the array , decodes it and returns modified array .
 * 
 * @param array $data 
 * @return array 
 */
function normalize_one(array $data){
  foreach($data as &$value){
     $value = json_safe($value); 
  }
  return $data;
}

/**
 * Check whether password string is a valid json  , If is a json string .
 * 
 * @param $string 
 * @return string
 */
function json_safe($string){
    return is_json($string)?json_decode($string):$string;
}

/**
 * Check whether passed string is a valid json  or or not 
 * Return true if string is a valid json.
 * 
 * @param string $string
 * @return bool 
 */
function is_json($string){
    if(is_null($string)) return false;
    $data = json_decode($string,true);
    return (json_last_error() == JSON_ERROR_NONE) ?TRUE : FALSE;
}