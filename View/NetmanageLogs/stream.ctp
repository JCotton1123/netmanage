<?php foreach($logs as $log){ ?>
  <tr>
    <td><?php echo $log['NetmanageLog']['timestamp']; ?></td>
    <td><?php echo $log['NetmanageLog']['type']; ?></td>
    <td><?php echo $log['NetmanageLog']['message']; ?></td>
  </tr>
<?php }
