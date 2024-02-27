<!DOCTYPE html>
<html>
  <head>
	<title><?= $title?></title>
	<?php
		echo $this->Html->meta('favicon.ico','images/favicon.ico',array('type' => 'icon'));
		echo $this->fetch('meta');
		echo $this->fetch('css');
	?>
	<link rel="stylesheet" href="<?php echo $this->request->webroot ?>assets/html/css/errors.css" />
  </head>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
         <?php echo $this->fetch('content');  ?>
      </div>
    </div>
  </body>
</html>