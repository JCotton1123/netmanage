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
  <table class="table table-striped table-bordered datatable">
    <thead>
      <th>Mac Address</th>
    </thead>
    <tbody>
    </tbody>
  </table>
</section>
