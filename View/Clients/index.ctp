<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Clients');
?>

<section>
  <?php echo $this->DataTables->table(
      'clients',
      $this->DataTables->getColumnHeadings(),
      '/clients/index.json'
  ); ?>
</section>
