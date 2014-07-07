<?php foreach($logs as $log){ ?>
  <tr>
    <td><?php echo $log['DeviceLog']['timestamp']; ?></td>
    <td><?php echo $log['DeviceLog']['host']; ?></td>
    <td><?php echo $log['DeviceLog']['fac_sev_mnem']; ?></td>
    <td><?php echo $log['DeviceLog']['event_descr']; ?></td>
  </tr>
<?php }
