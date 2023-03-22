<?php 
require "mpm/auth/components/header_code.php"
?>
<div class="container">
 <div class="form-container ">
   <h1 style="font-family:serif">Register</h1>
     <form action="" class="form" method="post">
      <?php echo $form->render_form();?>
    <button type="submit" class="btn btn-primary">Register</button>
    
     </form>
     <p>Already have an account <a href="<?php echo reverse('login');?>">Login</a></p>
 </div>
  </div>
<?php require "mpm/auth/components/footer_code.php";?>