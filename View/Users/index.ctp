<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Users');
?>

<?php $this->start('actions');
  echo $this->ActionMenu->menu(array(
    'Add New User' => array(
      'url' => "/users/add",
      'enabled' => in_array($__userRole, array('administrator'))
    )
  ));
$this->end(); ?>

<section>
  <?php echo $this->DataTables->table(
      'users',
      $this->DataTables->getColumnHeadings(),
      '/users/index.json'
  ); ?>
</section>
