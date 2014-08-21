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
  <?php if($__isLoggedIn) {
    $userId = $this->Session->read('Auth.User.id');
    $userName = $this->Session->read('Auth.User.name');
    ?>
      <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <?php echo $this->element('navigation_items'); ?>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <span class="glyphicon glyphicon-user"></span>
            <?php echo $userName; ?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <li><?php echo $this->Html->link('Change Password', "/users/reset_password/${userId}"); ?></li>
            <li><?php echo $this->Html->link('Logout', '/users/logout'); ?></li>
          </ul>
        </li>
      </ul>
    </div><!--/.nav-collapse --> 
  <?php } ?>
</div> <!-- container -->
</div> <!-- /navbar -->
