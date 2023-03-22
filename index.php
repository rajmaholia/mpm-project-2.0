<?php
/**
 * This is Index file
 *  DON'T EDIT THIS 
**/
require_once 'config/autoload.php';

use Mpm\Core\{Request,Router};

Router::process(Request::captureUri(),$urlpatterns);

