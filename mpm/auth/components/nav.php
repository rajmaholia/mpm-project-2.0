
  <nav class="navbar navbar-dark bg-primary px-1 ">
    <div class="navbar-brand text-center px-auto"><?php echo PROJECT_NAME;?> Administration</div>
    <?php if($user->is_staff==1) {?>
    <div class="nav">
      <a href ="/" class="nav-link text-info">View Site</a>
      <a href ="#" class="nav-link text-info disabled">/</a>
      <a href="<?php echo reverse('logout'); ?>" class="nav-link text-info">Logout</a>
    </div>
    <?php }?>
  </nav>