<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Reports');
?>
<section>
  <table class="table table-striped">
    <thead>
    <tr>
      <td>Name</td>
      <td>Description</td>
    </tr>
    </thead>
    <tbody>
      <tr>
        <td><?php echo $this->Html->link('Device Inventory', '/reports/device_inventory'); ?></td>
        <td>Export a CSV of devices</td>
      </tr>
      <tr>
        <td><?php echo $this->Html->link('Nagios Export', '/reports/nagios_export'); ?></td>
        <td>Export Nagios host and hostgroup declarations for all of your network devices</td>
      </tr>
      <tr>
        <td><?php echo $this->Html->link('Network Graph', '/reports/network_graph'); ?></td>
        <td>Generate a layer-2 network graph</td>
      </tr>
    </tbody>
  </table>
</section>
