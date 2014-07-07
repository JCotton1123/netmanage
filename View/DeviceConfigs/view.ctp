<?php
  $deviceId = $config['Device']['id'];
  $deviceName = $config['Device']['name'];
  $configStr = $config['DeviceConfig']['config'];
?>
<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Configuration');
?>
<section>
  <ol class="breadcrumb">
    <li><?php echo $this->Html->link('Devices', "/devices"); ?></li>
    <li><?php echo $this->Html->link("$deviceName", "/devices/view/${deviceId}"); ?></li>
    <li class="active">Config</li>
  </ol>
  <pre>
    <?php echo $configStr; ?> 
  </pre>
</section>
