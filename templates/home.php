<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | MPM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  </head>
<body>
  <nav class="navbar navbar-dark bg-primary">
    <div class="navbar-brand">MPM</div>
  </nav>
<!-- Container -->
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-12">
      
      <h1>Welcome to MPM </h1>
      <p>It Worked</p>
      <?php if($user->id==null) {?>
      <a href="<?php echo reverse('login'); ?>" class="btn btn-primary">Login</a>
      <?php } else {?>
      <a href="<?php echo reverse('logout'); ?>" class="btn btn-primary">Logout</a>
      <?php }?>
    </div>
  </div>
</div>
<!-- Container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>