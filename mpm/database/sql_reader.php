<?php
namespace Mpm\Database;


function read_from_file($conn,$file){
      echo "Loading : ".$file ."\n";
      $query = '';
      $sqlScript = file($file);
      foreach ($sqlScript as $line)	{
        $startWith = substr(trim($line), 0 ,2);
        $endWith = substr(trim($line), -1 ,1);
  	    if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
  		        continue;
  	     }
  	     $query = $query . $line;
  	     if ($endWith == ';') {
  	       try{
  		       mysqli_query($conn,$query);
  	       }catch(\Exception $e){
  		       echo "\n[ERROR]\n Problem in executing the SQL query :\n\t\"" . trim($query) ."\" \n\n";
  		       echo "REASON : ".mysqli_error($conn)."\n\n";
  		     $query= '';		
  	       }
  	      }//endswith
        }//foreach reading file line be line
        echo "Loaded : ".$file ."\n\n";
}

function read_query($conn,$sql) {
  $query_array = explode(';',$sql);
  foreach($query_array as $query) {
    $query = trim($query);
    if(empty($query)) continue;
    try {
      echo "\nReading `". substr($query,0,25)." ....)`\n";
      mysqli_query($conn,$query);
      echo "[Done]";
    } catch(\Exception $e) {
      echo "[ERROR] : ".mysqli_error($conn);
    }
    echo "\n\n";
  }
}