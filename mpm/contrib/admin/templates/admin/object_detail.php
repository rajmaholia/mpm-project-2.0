<?php require 'components/header_code.php';
;?>
<?php if(isset($_GET['table']) && isset($_GET['id'])){
  $table = $_GET['table'];
  $id = $_GET['id'];
  try {
    $classvars = get_class_vars($table);
    $table_exists = true;
    $pkey = $classvars['list_display'][0];
  } catch(TypeError $e){
    $table_exists = false;
    echo "Table Doesn't Found!";
  }
  $data = db_read($table,filter:array($pkey=>$id));
} 
?>
<!-- Main page -->
<div class="container">
  <div class="row sticky-top">
    <nav class="col-sm-12  fw-bold navbar navbar-dark" style="background-color:#b510c7;">
      <a href="#" style="font-family:serif" class="navbar-brand">FlashKart Admin</a>
    </nav>
       <nav style="--bs-breadcrumb-divider: '>';background-color:#d36bca;" aria-label="breadcrumb" class=" p-2">
    <ol class="breadcrumb p-0 m-0">
      <li class="breadcrumb-item"><a href="/admin/dashboard.php" style="color:white;text-decoration:none">Home</a></li>
      <li class="breadcrumb-item"><a href="/admin/dashboard.php" style="color:white;text-decoration:none"><?php echo $table; ?></a></li>
      <li class="breadcrumb-item active text-muted" aria-current="page"><?php echo $id; ?></li>
    </ol>
   </nav>
  </div>
<?php if(isset($data)&&count($data)>0) { 
  foreach($data[0] as $key=>$value) {
    echo "<h3>$key</h3>";
    echo "<p>$value</hp>";
    //var_dump($value);
  }
}?>
      
</div>
<?php require 'components/footer_code.php';?>