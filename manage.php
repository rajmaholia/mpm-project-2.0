<?php
if(php_sapi_name()=="cli") :
    require_once "config/settings.php";
    require_once "mpm/database/DB.php";
    require_once 'mpm/core/Command.php';
else :
    exit();
endif;

use Mpm\Core\Command;
  
Command::execute($argv);
?>