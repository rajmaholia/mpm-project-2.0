 <nav class="navbar navbar-dark bg-primary px-1 ">
    <div class="navbar-brand text-center px-auto"><?php echo PROJECT_NAME;?> Administration</div>
    <?php if($user->is_staff==1) {?>
    <div class="nav">
      <a href ="<?php url("object_edit",["User",$user->id]); ?>" class="nav-link text-info">Welcome <?php echo $user->username; ?> , </a>
      <a href ="/" class="nav-link text-info">View Site</a>
      <a class="nav-link text-info disabled px-0">/</a>
      <a href="<?php echo reverse('logout'); ?>" class="nav-link text-info">Logout</a>
    </div>
    <?php }?>
  </nav>