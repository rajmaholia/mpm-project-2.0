<?php
if(php_sapi_name()=="cli") {
  require_once "config/settings.php";
  require_once "mpm/database/sql_reader.php";
  require_once "mpm/database/database_handler.php";
  require_once 'mpm/core/command_line.php';
}
  
  use function  Mpm\Core\execute_from_command_line;
  execute_from_command_line($argv);
?>