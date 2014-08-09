<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Schedule Software Upgrade');
?>

<?php echo $this->Html->script('device_config_mgmt.js', array('inline' => false)); ?>

<section>
<form method="POST">
<div>
  <h3>Step 1 - Schedule</h3>
  <input type="datetime" />
</div>
<div>
  <h3>Step 2 - Software</h3>
  <input type="file" name="software" />
</div>
<div>
  <h3>Step 3 - Select Devices</h3>
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
      'Firmware',
    ),
    '/device_software_mgmt/index.json',
    array(
      'class' => 'table-bordered table-striped table-condensed',
      'data-length' => '5',
    )
  ); ?>
</div>
<div>
  <button type="submit" class="btn btn-primary">Schedule Upgrade</button>
</div>
</form>
</section>
