<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Delete Device Attribute OID');
?>

<ol class="breadcrumb">
  <li><?php echo $this->Html->link('Device Attribute OIDs', '/device_attr_oids'); ?></li>
  <li class="active">Delete</li>
</ol>

<section>
  <?php echo $this->DataTables->table(
      'devices',
      $this->DataTables->getColumnHeadings(),
      '/device_attr_oids/delete.json'
  ); ?>
</section>
