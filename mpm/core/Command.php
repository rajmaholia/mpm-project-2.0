<?php
 namespace Mpm\Core;
 use Mpm\Database\DB;
  
class Command {
  public static function execute($arguments)
  {
  echo "\n";
  switch($arguments[1]) {
    case 'serve':
      exec('php -S localhost:8080');
      break;
      
    case 'migrate':
     $db = DATABASE;
     $conn = DB::connect(database:false);
     $dbname = $db['database'];
      if(!$conn){
        exit("Couldn't connect to database server");
      }
      if(empty($dbname)) exit("[ERROR] Database name not set in config/settings.php\n");
      
      if(!mysqli_query($conn,"CREATE DATABASE IF NOT EXISTS $dbname"))
      {
        exit("Can't Create Database ".$dbname);
      } else {
        mysqli_query($conn,"Use ".$dbname);
        foreach($db['load_files'] as $file){
          DB::read_from_file($file,$conn);//foreach reading file line be line
        }//for each to get files
      }//else of create database
      mysqli_close($conn);
      break;
    
    case 'createadmin':
      if(!isset($arguments[2]) || !isset($arguments[3]) || !isset($arguments[4])){
          exit("\nUsage : php manage createadmin <username> <password> <email>\n\n");
      }
      $admin_username = $arguments[2];
      $admin_password = $arguments[3];
      $admin_password = password_hash($admin_password, PASSWORD_DEFAULT);
      $admin_email = $arguments[4];
      
      $dbname = DATABASE["database"];
      try {
        $conn = DB::connect();
      }
      catch(\Exception $e) {
        echo $e->getMessage()."\n";
        echo("Run `php manage migrate`.\n");
        exit("Quitting ... \n");
      }
      
      if(!DB::table_exists($dbname,"User")){
        echo("Database  not Configured Properly \n");
        echo("Run `php manage migrate`.\n");
        exit();
      }
      try {
        $response = mysqli_query($conn,"insert into User (username,password,email,is_staff) values('$admin_username','$admin_password','$admin_email',1)");
      } catch(\Exception $e) {
        try {
          mysqli_query($conn,"UPDATE User SET password='$admin_password',email='$admin_email',is_staff='1' WHERE username='$admin_username'");
        } catch(Exception $e){
           echo $e->getMessage();
           exit("\nQuitting ... \n");
        }
      }
      echo "SuperUser Created Successfully\n ";
      mysqli_close($conn);
      break;
    
    case 'makemigrations':
      if(!isset($arguments[2])) exit("Usage :  `php manage makemigrations <app>\n");
      else $app_name = $arguments[2];
      $db = DATABASE;
      try{
        $conn = mysqli_connect($db['host'],$db['username'],$db['password'],$db['database']);
      }catch(\Exception $e) {
        exit("Database not configured Properly\n");
      }
      $migrations = glob($app_name."/migrations/*.php");
      if(count($migrations)==0) exit("migrations not available for '$app_name'\n\n");
      
      foreach($migrations as $file){
        require_once($file);
        $sql = trim($sql);
        if(empty($sql)){
          echo "Migrations Not found in `$file`";
          echo "\nSkipping....";
          continue;
        }
        echo "Running Migration for  ".$file." . . .\n";
        try {
          DB::query($sql,$conn);
          echo("Success  : Migrations ".$file." Applied\n\n");
        } catch(\Exception $e) {
          echo "Error : ".mysqli_error($conn)."\n\n";
        }//try catch
      }
      echo "Done\n";
    mysqli_close($conn);
    break;
    
    
    case 'createproject':
      /*future release
      if(isset($arguments[2])) $project_name = $arguments[2];
     else $project_name = "config";
      if(file_exists($project_name)) exit("Project Exists With This Name `config` \n");*/
      $project_name = "config";
      
      if(file_exists($project_name)) exit("Project Exists\n");
      mkdir("$project_name");
      $files = glob("mpm/conf/project_templates/project_name/*_tpl");
      $files .= glob("mpm/conf/project_templates/*_tpl");
      foreach($files as $file){
       $new_file_name = explode("_tpl",$file)[0];
       echo copy($file,$project_name."/".basename($new_file_name))?"Done...\n":"[Error]\n";
     }
    break;
    
    case 'createapp':
      if(!isset($arguments[2])) exit("Usage :  `php manage createapp <app>`\n");
      else $app_name = $arguments[2];
      file_exists($app_name)?exit("App `$app_name` already Exists \n"):mkdir("$app_name/");
      
      mkdir("$app_name/migrations");
      $migration_file = glob("mpm/conf/app_templates/migrations/*_tpl")[0];
      $files = glob("mpm/conf/app_templates/*_tpl");
      
      echo copy($migration_file,"$app_name/migrations/".basename("initial.php"))?"Downloaded... `migrations/initial.php`\n":"[Error]\n";
  
      foreach($files as $file){
        $new_file_name = basename(explode("_tpl",$file)[0]);
        echo copy($file,$app_name."/".$new_file_name)?"Downloaded...  `$new_file_name`\n":"[Error]\n";
      }
      echo "\nApp `$app_name` Created Successfully\n";
      break;
      
    default:
      exit("Command not found : ".$arguments[1]);
  }//switch
  echo "\n";
  }//function
}