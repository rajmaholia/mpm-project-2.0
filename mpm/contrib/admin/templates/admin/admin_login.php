<?php
require_once 'mpm/contrib/admin/components/header_code.php';
require_once 'mpm/contrib/admin/components/nav.php'; ?>
<div class="container">
  <form action="" method="post">
    <?php echo $form->render_form();?>
    <button class="btn btn-primary">Login</button>
  </form>
</div>
<?php require_once 'mpm/contrib/admin/components/footer_code.php'; ?>