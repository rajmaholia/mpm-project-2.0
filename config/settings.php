<?php 
/***** Settings "********/
define("PROJECT_NAME","MPM");


define("BASE_DIR",realpath("./"));
define("BASE_PATH","./");
$base_url = isset($_SERVER['HTTPS'])&& $_SERVER['HTTPS']==='on' ? "https":"http"."://".$_SERVER['HTTP_HOST'].'/';
define("BASE_URL",$base_url);

define("DEBUG", true);
/***  APPS **/
define("APPS",[
  'mpm/admin',
  'mpm/auth',
  'config',
]);

/* Database  Configurations */
define('DATABASE',[
  'username' => "root",
  'password' => "root",
  'host'     => "0.0.0.0",
  'port'     =>"3306",
  'database' => "",//databasse name;
  'load_files'=>array('mpm/auth/User.sql'),
]);

define('TEMPLATES',[
  'DIRS'=>array(BASE_DIR),
]);

define('STATICFILES',[
  'DIRS'=>array(BASE_PATH),
]);



define("UPLOAD_PATH",'uploads/');
define("LOGIN_REDIRECT_URL","home");
define("LOGOUT_REDIRECT_URL","home");
define("AUTH_USER_MODEL","User");