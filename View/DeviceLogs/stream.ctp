<?php foreach($logs as $log){

  $timestamp = $log['DeviceLog']['timestamp'];
  $host = $log['DeviceLog']['friendly_ip_addr'];
  if(isset($log['Device']['name']))
      $host = $log['Device']['name'];
  $facSevMnem = $log['DeviceLog']['fac_sev_mnem'];
  $message = $log['DeviceLog']['message'];
  ?>
  <tr>
    <td><?php echo $timestamp; ?></td>
    <td><?php echo $host; ?></td>
    <td><?php echo $facSevMnem; ?></td>
    <td><?php echo $message; ?></td>
  </tr>
<?php }
