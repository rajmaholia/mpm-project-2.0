<?php require_once 'mpm/contrib/admin/components/header_code.php'; ?>
<?php require_once 'mpm/contrib/admin/components/nav.php'; ?>

<div class="container-fluid">
  <!-- header -->
  <div class="row sticky-top ">
   <nav style="--bs-breadcrumb-divider: '>';background-color:#d36bca;" aria-label="breadcrumb" class="col-sm-12 p-2">
    <ol class="breadcrumb p-0 m-0">
      <li class="breadcrumb-item"><a href="<?php url('admin_dashboard');?>" class="text-decoration-none text-white">Home</a></li>
      <li class="breadcrumb-item text-muted" ><a href="<?php  url('object_list',array($table));?>" style="color:white;text-decoration:none"><?php echo $table; ?> </a></li>
      <li class="breadcrumb-item active text-muted" aria-current="page"><?php echo $id; ?></li>
    </ol>
   </nav>
  </div>
  <!-- header END-->
  <div class="container">
    <form action="" <?php if (isset($enctype)) echo($enctype) ?> method="post">
      <?php 
      echo $form->render_form();
      if($form->hasFileField()) render_files($form,$filesData);
      ?>
      <button class="btn btn-primary">Save</button>
    </form>
  </div>
</div>
<?php require_once 'mpm/contrib/admin/components/footer_code.php'; ?>