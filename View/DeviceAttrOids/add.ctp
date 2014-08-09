<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Add Device Attribute OID');
?>

<ol class="breadcrumb">
  <li><?php echo $this->Html->link('Device Attribute OIDs', '/device_attr_oids'); ?></li>
  <li class="active">Add</li>
</ol>

<section>
  <form action="/device_attr_oids/add" method="POST">
    <div class="form-group">
      <label>SysObjectId</label>
      <input type="text" class="form-control" name="data[DeviceAttrOid][sys_object_id]" required />
    </div>
    <div class="form-group">
      <label>Name</label>
      <input type="text" class="form-control" name="data[DeviceAttrOid][name]" required />
    </div>
    <div class="form-group">
      <label>OID</label>
      <input type="text" class="form-control" name="data[DeviceAttrOid][oid]" required />
    </div>
    <div class="form-group">
      <label>Filter</label>
      <input type="text" class="form-control" name="data[DeviceAttrOid][oid]" />
    </div>
    <button class="btn btn-primary" type="submit">Add</button>
    <a class="btn btn-default" href="/device_attr_oids">Cancel</a>
  </form>
</section>
