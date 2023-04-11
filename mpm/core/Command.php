<?php
 namespace Mpm\Core;
 use Mpm\Database\DB;
  
class Command { 
  private static $commandSummary = "
    Usage          : python manage.php <command> <options>\n
    Commands  \n
    serve          : Starts the development serve at localhost:8080 \n 
    createapp      : Creates pre-defined minimal structure for an app \n 
    createadmin    : Creates superuser for your admin dashboard \n 
    migrate        : Loads predefined User table in database \n 
    makemigrations : Applies migrations in database ,  written in given app \n
    ";
  
  public static function execute($arguments)
  {
  isset($arguments[1])?"":exit(self::showHelp());
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
     $project_name = "config";
      
      if(file_exists($project_name)) exit("Project Exists\n");
      mkdir("{$project_name}");
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
      $targetFolder    = "{$app_name}";//where to copy all files and folders 
      $appTemplateDir  = "mpm/conf/app_templates/";//directory that holds predefined folders and files for an app
      $directoryIterator = new \DirectoryIterator($appTemplateDir);
      foreach($directoryIterator as $directory) {
        if($directory->isFile()):
          $oldFileName = $directory->getPathname();//full name of file with path
          $fileName = preg_match("/^\w+.php/",$directory->getFileName(),$newFileName);
          echo copy($oldFileName,"{$targetFolder}/{$newFileName[0]}")?"Downloaded...  `{$targetFolder}/{$newFileName[0]}`\n":"[Error]\n";
      
        elseif($directory->isDir()):
          $dirName = "{$targetFolder}/{$directory->getFilename()}";
          echo mkdir("{$dirName}")?"Downloaded ... `{$dirName}`\n" : "[Error]\n";
          $subConfFiles = glob("{$directory->getPathName()}/*.php*");
          foreach($subConfFiles as $file){
            preg_match("/^\w+.php/",basename($file),$newSubFileName);
            echo "\n";
            echo copy($file,"{$dirName}/{$newSubFileName[0]}")?"Downloaded...  `{$dirName}/{$newSubFileName[0]}`\n":"[Error]\n";
          }
        endif;
      }
      echo "\nApp `$app_name` Created Successfully\n";
      break;
      
    default:
      echo("Command not found : `{$arguments[1]}`");
  }//switch
  echo "\n\n";
  }//function
  
  public static function showHelp($command="help"){
    return [
      "help"=>self::$commandSummary,
      ][$command];
  }
}