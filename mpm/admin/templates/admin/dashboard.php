<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | <?php echo PROJECT_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo staticfile("admin/css/base.css"); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  </head>
<body>
 <nav class="navbar navbar-dark bg-primary px-1 ">
    <div class="navbar-brand text-center px-auto"><?php echo PROJECT_NAME;?> Administration</div>
    <div class="nav">
      <a href ="/" class="nav-link text-info">View Site</a>
      <a href ="#" class="nav-link text-info disabled">/</a>
      <a href="<?php echo reverse('logout'); ?>" class="nav-link text-info">Logout</a>
    </div>
  </nav>
<!-- Container -->
<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-sm-8 p-0">
      <?php foreach($groups as $group_name=>$models) { ?>
      <div class="display-group mb-4">
        <div class="group-heading bg-primary p-2">
          <?php echo $group_name ;
          ?>
        </div>
        <div class="group-items bg-secondary">
          <?php foreach($models as $model) {?>
          <div class="group-item border p-1">  <a href="<?php echo reverse('object_list',array($model));?>" class="text-decoration-none text-white"><?php echo $model; ?></a>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php  } ?>
    </div> <!-- col-sm-6 -->
    <div class="col-sm-4 p-0">
      <div class="recent-actions border">
        <div class="header bg-primary p-2">
          Recent Actions
        </div>
        <ul class="Actions list-unstyled">
          <li class="bg-info border">1</li>
          <li class="bg-info border">2</li>
          <li class="bg-info border">3</li>
        </ul>
      </div>
      </div>
    </div> <!-- col sm 4 -->
  </div>
</div>

<!-- Container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>