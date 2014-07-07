<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Search Results');
?>
<section>
  <h3>Devices</h3>
  <table class="table table-striped table-bordered datatable">
    <thead>
      <th>Name</th>
      <th>Address</th>
      <th>Model</th>
    </thead>
    <tbody>
      <?php foreach($devices as $device) {
        $id = $device['Device']['id'];
        $name = $device['Device']['name'];
        $addr = $device['Device']['ip_addr'];
        $model = $device['Device']['model'];
        ?>
        <tr>
          <td><?php echo $this->Html->link($name, "/devices/view/${id}"); ?></td>
          <td><?php echo $this->Html->link($addr, "ssh://${addr}"); ?></td>
          <td><?php echo $model; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</section>
<section>
  <h3>Clients</h3>
  <table class="table table-striped table-bordered datatable">
    <thead>
      <th>Mac Address</th>
    </thead>
    <tbody>
    </tbody>
  </table>
</section>
