<!DOCTYPE html>
<html>
  <head>
	<title>SMBIT Exchange</title>
	<link rel="shortcut icon" href="<?php echo $this->request->webroot?>assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo $this->request->webroot?>assets/images/favicon.ico" type="image/x-icon">
	<?php
		
		echo $this->Html->meta('favicon.ico','images/favicon.ico',array('type' => 'icon'));
		echo $this->Html->css(array(
						'Admin/bower_components/bootstrap/dist/css/bootstrap.min.css',
						'Admin/bower_components/font-awesome/css/font-awesome.min.css',
						'Admin/bower_components/Ionicons/css/ionicons.min.css',
						'Admin/AdminLTE.min.css',
						'Admin/iCheck/square/blue.css',
						'Admin/style.css'
					));
			
	
		echo $this->fetch('meta');
		echo $this->fetch('css');
		
	?>
  </head>

  <body class="hold-transition login-page">
    <?php echo $this->fetch('content');  ?>
  </body>
</html>
