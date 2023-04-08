<?php
use function Mpm\Urls\{path,includes};

/*patterns
path(
    url:"/blog_detail/(?P<id>\d+)/",
    view:'blog/blog_detail.php',
    name:'blog_detail'
  ),*/

$urlpatterns =  [
  ...includes('mpm/contrib/admin/urls'),//Admin Urls
  ...includes('mpm/contrib/auth/urls'),//Authentication
  path('','HomeController@home','home'),
];

