<?php
    $isDebug = Configure::read('debug') > 0;
    $isUserSignedIn = SessionComponent::read('Auth.User') != false;
    $controller = strtolower($this->params['controller']);

    //Determine what nav item should be active
    $activeNav = 'home';
    if(in_array($controller, array('devices','deviceconfigs')))
        $activeNav = 'devices';
    elseif(in_array($controller, array('reports')))
        $activeNav = 'reports';
    elseif(in_array($controller, array('settings')))
        $activeNav = 'settings';
    else
        $activeNav = 'home';

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
        - NetManage
	</title>
	<?php
		echo $this->Html->meta('icon');
        echo $this->Html->meta(
            'viewport',
            null,
            array(
                'name' => 'viewport',
                'content' => 'width=device-width, initial-scale=1'
            )
        );
        echo $this->Html->meta(
            'compatible',
            null,
            array(
                'http-equiv' => 'X-UA-Compatible',
                'content' => 'IE=edge'
            )
        );
		echo $this->fetch('meta');

        echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css');
        echo $this->Html->css('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css');
        echo $this->Html->css('//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.css');
        //echo $this->Html->css('/css/bootstrap.theme.css');
        echo $this->Html->css('/vis/vis.css');
        echo $this->Html->css('/css/app.css');
        echo $this->fetch('css');
	?>
</head>
<body>
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
        <?php if($isUserSignedIn) { ?>
            <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="<?php echo ($activeNav == 'home') ? 'active' : ''; ?>">
                  <a href="/">Home</a>
                <li class="<?php echo ($activeNav == 'devices') ? 'active' : ''; ?>">
                  <a href="/devices">Devices</a>
                </li>
                <li class="<?php echo ($activeNav == 'reports') ? 'active' : ''; ?>">
                  <a href="/reports">Reports</a>
                </li>
                <li class="<?php echo ($activeNav == 'settings') ? 'active' : ''; ?>">
                  <a href="/settings">Settings</a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="glyphicon glyphicon-user"></span>
                  <?php echo SessionComponent::read('Auth.User.username'); ?>
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
    <div class="container <?php echo $controller; ?>">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>
    <?php if($isDebug) { ?>
        <div style="display:none">
            <?php echo $this->element('sql_dump'); ?>
        </div>
    <?php } ?>
    <?php
        echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
        echo $this->Html->script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');
        echo $this->Html->script('//cdn.datatables.net/1.10.0/js/jquery.dataTables.js');
        echo $this->Html->script('//cdn.datatables.net/plug-ins/be7019ee387/integration/bootstrap/3/dataTables.bootstrap.js');
        echo $this->Html->script('/js/plugin.datatables.js');
        echo $this->Html->script('/js/mousetrap.min.js');
        echo $this->Html->script('/vis/vis.js');
        echo $this->Html->script('/js/app.js');
        echo $this->fetch('script');
    ?>
</body>
</html>
