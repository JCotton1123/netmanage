<?php
  $id = $device['Device']['id'];
  $name = $device['Device']['name'];
  $ipAddr = $device['Device']['ip_addr'];
  $serialNumber = $device['Device']['serial'];
  $model = $device['Device']['model'];
  $sysObjectId = $device['Device']['sys_object_id'];
  $firmware = $device['Device']['firmware'];
  $lastSeen = $device['Device']['last_seen'];
  $created = $device['Device']['created'];
?>
<?php
  $this->extend('/Common/default');
  $this->assign('title', $name);
?>

<?php $this->start('actions'); ?>
  <div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="device-action-menu" data-toggle="dropdown">
      Actions 
      <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" role="menu" aria-labelledby="device-action-menu">
      <li role="presentation">
        <?php
          echo $this->Html->link(
            'Delete',
            "/devices/delete/${id}",
            array(
              'role' => 'menuitem',
              'tabindex' => '-1'),
            'Are you sure you want to delete this device?'
          );
        ?>
      </li>
    </ul>
  </div>
<?php $this->end(); ?>

<section>
  <h3>Attributes</h3>
  <table class="table">
    <tr>
      <td>IP Address</td>
      <td><?php echo $this->Html->link($ipAddr, "ssh://$ipAddr"); ?></td>
    </tr>
    <tr>
      <td>Serial Number</td>
      <td><?php echo $serialNumber; ?></td>
    </tr>
    <tr>
      <td>Model</td>
      <td><?php echo $model; ?></td>
    </tr>
    <tr>
      <td>SysObjectId</td>
      <td><?php echo $sysObjectId; ?></td>
    <tr>
      <td>Firmware</td>
      <td><?php echo $firmware; ?></td>
    </tr>
    <tr>
      <td>Last Seen</td>
      <td><?php echo $lastSeen; ?></td>
    </tr>
    <tr>
      <td>First Seen</td>
      <td><?php echo $created; ?></td>
    </tr>
  </table>
</section> <!-- /.attributes -->

<section>
  <h3>Neighbors</h3>
    <?php echo $this->DataTables->table(
    'neighbors',
    array(
      'Neighbor Name',
      'Neighbor Platform',
      'Local Port',
      'Neighbor Port',
      'First Seen',
      'Last Seen'
    ),
    "/devices/neighbors/${id}.json",
    array(
      'data-length' => 10
    )
  ); ?>
</section> <!-- /neighbors -->

<section>
  <h3>Configurations</h3>
  <?php echo $this->DataTables->table(
    'configs',
    array(
      'Timestamp',
      'Diff'
    ),
    "/devices/configs/${id}.json",
    array(
      'data-length' => 2
    )
  ); ?>
</section> <!-- /configurations -->

<section>
  <h3>Logs</h3>
  <?php echo $this->DataTables->table(
    'logs',
    array(
      'Timestamp',
      'Fac/Sev/Mnem',
      'Message'
    ),
    "/device_logs/llist/${id}.json",
    array(
      'data-length' => 10,
    )
  ); ?>
</section>
