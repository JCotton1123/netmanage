<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Search Results');
?>
<section class="condensed">
  <form role="form" action="/search" method="post">
      <div class="input-group">
        <input type="text" name="searchTerm" value="<?php echo $searchTerm; ?>" class="form-control" />
        <span class="input-group-btn">
          <button type="submit" class="btn btn-default">
            <span class="glyphicon glyphicon-search"></span>
          </button>
        </span>
      </div>
  </form>
</section>
<section>
  <h3>Devices</h3>
  <table class="table table-striped table-bordered datatable" data-length="5">
    <thead>
      <th>Name</th>
      <th>Address</th>
      <th>Model</th>
    </thead>
    <tbody>
      <?php foreach($devices as $device) {
        $id = $device['Device']['id'];
        $name = $device['Device']['name'];
        $addr = $device['Device']['friendly_ip_addr'];
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
  <table class="table table-striped table-bordered datatable" data-length="5">
    <thead>
      <th>Mac Address</th>
      <th>Device</th>
      <th>Port</th>
      <th>Vlan</th>
      <th>Up/Down</th>
    </thead>
    <tbody>
      <?php foreach($clients as $client) {
        $mac_addr = $client['ClientPort']['friendly_mac_addr'];
        $device = $client['Device']['name'];
        $port = $client['ClientPort']['port'];
        $vlan = $client['ClientPort']['vlan'];
        $upDown = $client['ClientPort']['friendly_up_down'];
        ?> 
        <tr>
          <td><?php echo $mac_addr; ?></td>
          <td><?php echo $device; ?></td>
          <td><?php echo $port; ?></td>
          <td><?php echo $vlan; ?></td>
          <td><?php echo $upDown; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</section>
