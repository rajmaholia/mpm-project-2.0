<?php require_once 'mpm/admin/components/header_code.php'; ?>
<?php require_once 'mpm/admin/components/nav.php'; ?>
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
<?php require_once 'mpm/admin/components/footer_code.php'; ?>