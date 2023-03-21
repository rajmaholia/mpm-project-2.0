<?php
if(php_sapi_name()!='cli') {
  exit("<h1>Access Denied </h1> ");
}

require_once 'mpm/core/command_line.php';
use function  Mpm\Core\execute_from_command_line;


execute_from_command_line($argv);
?>