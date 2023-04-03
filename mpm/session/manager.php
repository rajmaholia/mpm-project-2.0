<?php
namespace Mpm\Session;
require_once "mpm/session/User.php";
session_start();

$base_url = isset($_SERVER['HTTPS'])&& $_SERVER['HTTPS']==='on' ? "https":"http"."://".$_SERVER['HTTP_HOST'].'/';

$user = new User();