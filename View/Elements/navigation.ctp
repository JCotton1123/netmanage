<div class="navbar navbar-default navbar-static-top" role="navigation">
<div class="container">
  <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">
        <img src="/img/logo.png" style="height:30px;" />
        NetManage
      </a>
  </div>
  <?php if($isLoggedIn) { ?>
      <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
          <li class="<?php echo ($activeNav == 'home') ? 'active' : ''; ?>">
            <a href="/">Home</a>
          </li>
          <li class="dropdown <?php echo ($activeNav == 'devices') ? 'active' : ''; ?>">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              Devices <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li><?php echo $this->Html->link('Device Management', '/devices'); ?></li>
              <li><?php echo $this->Html->link('Configuration Management', '/device_config_mgmt'); ?></li>
              <li><?php echo $this->Html->link('Software Management', '/device_software_mgmt'); ?></li>
              <li><?php echo $this->Html->link('Device Attribute OIDs', '/device_attr_oids'); ?></li>
            </ul>
          </li>
          <li class="<?php echo ($activeNav == 'reports') ? 'active' : ''; ?>">
            <a href="/reports">Reports</a>
          </li>
          <li class="dropdown <?php echo ($activeNav == 'settings') ? 'active' : ''; ?>">
            <?php echo $this->Html->link('Settings', '/settings'); ?>
          </li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-user"></span>
            <?php echo $username; ?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><?php echo $this->Html->link('Logout', '/users/logout'); ?></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse --> 
  <?php } ?>
</div> <!-- container -->
</div> <!-- /navbar -->
