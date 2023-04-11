<?php
namespace Mpm\Contrib\Auth;
use function Mpm\Urls\redirect;

function login_required($login_url_name='login') {
  global $user;
  if($user->id==null) redirect(reverse($login_url_name));
}
