<?php
require_once 'mpm/core/Autoloader.php';
require_once 'config/settings.php';
require_once 'config/admin.php';

use Mpm\Core\Autoloader;

define(
  "AUTOLOAD",array(
    "DIRS"=>[
      "mpm/urls",
      "mpm/core",
      "mpm/session",
      "mpm/utils",
      "mpm/database",
      "mpm/forms",
      "mpm/handlers",
      "mpm/static",
      "mpm/validation",
      "mpm/view",
      "mpm/template",
      ],
    "FILES"=>[
      "mpm/auth/functions.php",
      ]
    ),
);

$autoloader = new Autoloader();
$autoloader->prepare(AUTOLOAD);
$autoloader->autoload();

require_once 'config/sessions.php';

require_once 'config/urls.php';

foreach(APPS as $app) {require_once(glob("$app/views.php")[0]);}