<?php
if(!defined('SECURE')) exit('<h1>Access Denied</h1>');
require "mpm/auth/components/header_code.php"
?>
<div class="container">
<div class="form-container">
  <h1 style="font-family:serif">Login Here</h1>
  <form action="" class="form" method="post">
    <?php echo $form->render_form(); ?>
    <button type="submit" class="btn btn-primary">Login</button>
  </form>
  <p style="text-align:center">Doesn't Have An Account <a href="<?php echo reverse('signup');?>">Sign Up</a></p>
</div>
</div>
<?php require "mpm/auth/components/footer_code.php";?>