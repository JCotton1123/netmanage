<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title><?php echo $title_for_layout; ?> - NetManage</title>
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

    echo $this->Html->css('/lib/bootstrap/css/bootstrap.min.css');
    echo $this->Html->css('/lib/bootstrap/css/bootstrap-theme.min.css');
    echo $this->Html->css('/lib/bootstrap/extras/bootstrap-flat.min.css');
    echo $this->Html->css('/lib/bootstrap/extras/bootstrap-flat-extras.min.css');
    echo $this->Html->css('/lib/bootstrap-plugins/tagsinput/bootstrap-tagsinput.css');
    echo $this->Html->css('/lib/datatables/bootstrap/css/dataTables.bootstrap.css');
    echo $this->Html->css('/lib/vis/vis.css');
    echo $this->Html->css('/css/app.css');
    echo $this->fetch('css');
	?>
</head>
<body>
  <?php echo $this->element('navigation'); ?>
  <div class="container <?php echo $controller; ?>">
    <?php echo $this->Session->flash(); ?>
    <?php echo $this->fetch('content'); ?>
  </div>
  <?php
    echo $this->Html->script('/js/jquery.min.js');
    echo $this->Html->script('/lib/bootstrap/js/bootstrap.min.js');
    echo $this->Html->script('/lib/bootstrap-plugins/tagsinput/bootstrap-tagsinput.js');
    echo $this->Html->script('/lib/datatables/datatables/js/jquery.dataTables.min.js');
    echo $this->Html->script('/lib/datatables/editable/js/jquery.editable.js');
    echo $this->Html->script('/lib/datatables/bootstrap/js/dataTables.bootstrap.js');
    echo $this->Html->script('/lib/datatables/editable/js/dataTables.editable.js');
    echo $this->Html->script('/lib/datatables/datatables.component.js');
    echo $this->Html->script('/lib/mousetrap/mousetrap.min.js');
    echo $this->Html->script('/lib/vis/vis.js');
    echo $this->Html->script('/js/app.js');
    echo $this->fetch('script');
  ?>
</body>
</html>
