<?php
namespace Mpm\Validation;

function test_input($data,$empty=false){
  if(empty($data) && $empty==true) {
    return true;
  }
  elseif(!empty($data)) {
  $data = trim($data);
  $data = htmlspecialchars($data);
  $data = stripslashes($data);
  return $data;
} else {
  return false;
}
}

function checklength($data,$length,$fixed=false) {
  if($fixed === true && strlen($data)!=$length) {
    return false;
  }else {
    if(strlen($data) <= $length) {
      return true;
    } elseif(strlen($data)>$length) {
      return false;
    }
  }
}

function checkemail($email){
  if(filter_var($email,FILTER_VALIDATE_EMAIL)){
    return true;
  }else {
   return false;
  }
}

function checkequal($data1,$data2){
  return ($data1===$data2)?true:false;
}

function cleaned_data($data){
  $cleaned_data = array();
  foreach($data as $key=>$data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    $data = stripslashes($data);
    $cleaned_data[$key] = $data;
  }
  return $cleaned_data;
}
?>