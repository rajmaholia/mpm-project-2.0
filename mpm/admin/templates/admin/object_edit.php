<?php require_once 'mpm/admin/components/header_code.php'; ?>
<?php require_once 'mpm/admin/components/nav.php'; ?>

<div class="container">
  <!-- header -->
  <div class="row sticky-top">
   <nav style="--bs-breadcrumb-divider: '>';background-color:#d36bca;" aria-label="breadcrumb" class=" p-2">
    <ol class="breadcrumb p-0 m-0">
      <li class="breadcrumb-item"><a href="<?php echo reverse('admin_dashboard');?>" class="text-decoration-none text-white">Home</a></li>
      <li class="breadcrumb-item active text-muted" aria-current="page"><a href="<?php echo reverse('object_list',array($table))?>" style="color:white;text-decoration:none"><?php echo $table; ?> </a></li>
      <li class="breadcrumb-item active text-muted" aria-current="page"><a href="<?php echo reverse('object_edit')?>" style="color:black;text-decoration:none"><?php echo $id; ?> </a></li>
    </ol>
   </nav>
</div>
  <!-- header END-->
  <div class="container">
    <form action="" method="post">
      <?php echo $form->render_form();?>
      <button class="btn btn-primary">Save</button>
    </form>
  </div>
  
<?php require_once 'mpm/admin/components/footer_code.php'; ?>