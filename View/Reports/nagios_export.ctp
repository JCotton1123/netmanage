<?php
  $this->extend('/Common/default');
  $this->assign('title', 'Nagios Export');
?>

<style>
  textarea {
    min-height: 200px;
  }
</style>

<ol class="breadcrumb">
  <li><?php echo $this->Html->link('Reports', '/reports'); ?></li>
  <li class="active">Nagios Export</li>
</ol>

<section>
  <h3>Parameters</h3>
  <form method="post" role="form" />
    <div class="form-group">
      <label>Device Filter</label>
      <input type="text" class="form-control" name="device_filter" value="<?php echo $deviceFilter; ?>" />
      <span class="help-block">Select a subset of your devices by name or IP address. This is helpful, for example, if you apply different settings to layer 2 and layer 3 devices, remote devices, etc.</span>
    </div>
    <div class="form-group">
      <label>Nagios Host Template</label>
      <input type="text" class="form-control" name="host_template" value="<?php echo $hostTemplate; ?>"  />
      <span class="help-block">See the <a href="http://nagios.sourceforge.net/docs/3_0/objectdefinitions.html#host">Nagios documentation</a>.</span>
    </div>
    <div class="form-group">
      <label>Nagios Host Parents</label>
      <input type="text" class="form-control" name="parents" value="<?php echo $hostParents; ?>" />
      <span class="help-block">See the <a href="http://nagios.sourceforge.net/docs/3_0/objectdefinitions.html#host">Nagios documentation</a>.</span>
    </div>
    <div class="form-group">
      <label>Hostgroup Name</label>
      <input type="text" class="form-control" name="hostgroup_name" value="<?php echo $hostgroupName; ?>"  />
      <span class="help-block">See the <a href="http://nagios.sourceforge.net/docs/3_0/objectdefinitions.html#hostgroup">Nagios documentation</a>.</span>
    </div>
    <div class="form-group">
      <label>Hostgroup Alias</label>
      <input type="text" class="form-control" name="hostgroup_alias" value="<?php echo $hostgroupAlias; ?>" />
      <span class="help-block">See the <a href="http://nagios.sourceforge.net/docs/3_0/objectdefinitions.html#hostgroup">Nagios documentation</a>.</span>
    </div>
    <button type="submit" class="btn btn-default">Update</button> 
  </form>
</section>

<section>
  <h3>Host Declarations</h3>
  <textarea class="form-control" readonly><?php echo $hosts; ?></textarea>
</section>

<section>
  <h3>Hostgroup Declarations</h3>
  <textarea class="form-control" readonly><?php echo $hostgroup; ?></textarea>
</section>
