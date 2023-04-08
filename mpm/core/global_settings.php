<?php
namespace Mpm\Core;

/* Global Settings */

$BASE_DIR = dirname(dirname(dirname(__FILE__)));


define('AUTOLOAD_TEMPLATES',[
  'DIRS'=>array("mpm/exceptions"),
]);

$DEBUG    =   true;

$TEMPLATES = [
              "AUTOLOAD_DIRS"=>["mpm/exceptions"],
             ];

$MEDIA_URL = "/media/";
$MEDIA_ROOT = $BASE_DIR."/media/";

$LOGIN_REDIRECT_URL   = "home";
$LOGOUT_REDIRECT_URL   = "home";

$AUTOLOAD_TEMPLATES    = [
                          'DIRS'=>["mpm/exceptions"],
                         ];


/* Email Configuration **/
$DEFAULT_FROM_EMAIL    = '';
$EMAIL_HOST            = '';
$EMAIL_HOST_USER       = '';
$EMAIL_HOST_PASSWORD   = '';
$EMAIL_HOST_PORT       = '';
$EMAIL_SECURE_PROTOCOL = '';


/* Database Configurations */
$DATABASE = [
            'username' => "root",
            'password' => "root",
            'host'     => "0.0.0.0",
            'port'     =>"3306",
            'database' => "mp_test",//databasse name;
            'load_files'=>array('mpm/auth/User.sql'),
          ];
