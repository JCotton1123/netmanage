<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Device Attribute OIDs');
?>

<?php $this->start('actions'); ?>
  <div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="device-action-menu" data-toggle="dropdown">
      Actions
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="device-action-menu">
      <li role="presentation">
        <?php echo $this->Html->link('Add', '/device_attr_oids/add'); ?>
        <?php echo $this->Html->link('Delete', '/device_attr_oids/delete'); ?>
      </li>
    </ul>
  </div>
<?php $this->end(); ?>

<section>
  <div class="callout callout-info">
    <p>Double-click a value to edit it inline.</p>
  </div>
  <?php echo $this->DataTables->table(
      'devices',
      $this->DataTables->getColumnHeadings(),
      '/device_attr_oids/index.json',
      array(
        'data-editable' => 'true',
        'data-update-url' => '/device_attr_oids/update.json'
      )
  ); ?>
</section>
