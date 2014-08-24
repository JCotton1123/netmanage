<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Configuration Management');
?>

<?php echo $this->Html->script('device_config_mgmt.js', array('inline' => false)); ?>

<section>
<form method="POST">
<div>
  <h3>Step 1 - Configuration</h3>
  <textarea class="form-control"></textarea>
</div>
<div>
  <h3>Step 2 - Select Devices</h3>
  <div id="device-tags" style="display:none;">
    <select multiple name="devices" data-role="tagsinput" placeholder="...add devices below"></select>
  </div>
  <?php echo $this->DataTables->table(
    'devices',
    array(
      '',
      'Name',
      'Address',
      'Model',
      'Serial',
    ),
    '/device_config_mgmt/index.json',
    array(
      'class' => 'table-bordered table-striped table-condensed',
      'data-length' => '5',
    )
  ); ?>
</div>
<div>
  <button type="submit" class="btn btn-primary">Push Configuration</button>
</div>
</form>
</section>
