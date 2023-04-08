<?php
require_once 'mpm/core/Autoloader.php';
require_once 'mpm/core/global_settings.php';
require_once 'config/settings.php';
require_once 'config/admin.php';

use Mpm\Core\Autoloader;


spl_autoload_register('Mpm\Core\Autoloader::classLoader');
define(
  "AUTOLOAD",array(
    "DIRS"=>[
      "mpm/urls",
      "mpm/static",
      "mpm/view",
      "mpm/template",
      ],
    "FILES"=>[
      "mpm/contrib/auth/functions.php",
      ]
    ),
);

$autoloader = new Autoloader();
$autoloader->prepare(AUTOLOAD);
$autoloader->autoload();

require_once 'config/sessions.php';

require_once 'config/urls.php';