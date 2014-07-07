<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Devices');
?>

<section>
  <?php echo $this->DataTables->table(
      'devices',
      $this->DataTables->getColumnHeadings(),
      '/devices/index.json'
  ); ?>
</section>
