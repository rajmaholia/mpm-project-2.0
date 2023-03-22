<?php
namespace Mpm\Auth;
use function Mpm\Urls\path;

$urlpatterns = [
  path(
    url:'/auth/login/',
    view:'login',
    name:'login'
     ),
  path(
    url:'/auth/signup/',
    view:'signup',
    name:'signup'
   ),
  path(
    url:'/auth/logout/',
    view:'logout',
    name:'logout'
   ),
  path(
    url:'/auth/password-change/',
    view:'password_change',
    name:'password_change'
   ),
  path(
    url:'/auth/password-change/done/',
    view:'password_change_done',
    name:'password_change_done'
   ),
  path(
    url:'/404/',
    view:'page_not_found',
    name:'404'
   ),
  path(
    url:'/permission-denied/',
    view:'permission_denied',
    name:'permission_denied'
   ),
];