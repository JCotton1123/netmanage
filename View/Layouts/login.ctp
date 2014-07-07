<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
        Login - NetManage
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
        echo $this->fetch('css');
	?>
</head>
<body>
    <div class="container">
        <?php echo $this->Session->flash(); ?>
        <?php echo $this->fetch('content'); ?>
    </div>
    <?php
        echo $this->Html->script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
        echo $this->Html->script('//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js');
        echo $this->fetch('script');
    ?>
</body>
</html>
