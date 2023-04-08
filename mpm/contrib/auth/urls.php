<?php
namespace Mpm\Contrib\Auth;
use function Mpm\Urls\path;

$urlpatterns = [
  path(
    url:'/auth/login/',
    view:'AuthController@login',
    name:'login'
     ),
  path(
    url:'/auth/signup/',
    view:'AuthController@signup',
    name:'signup'
   ),
  path(
    url:'/auth/logout/',
    view:'AuthController@logout',
    name:'logout'
   ),
  path(
    url:'/auth/password-change/',
    view:'AuthController@password_change',
    name:'password_change'
   ),
  path(
    url:'/auth/password-change/done/',
    view:'AuthController@password_change_done',
    name:'password_change_done'
   ),
];