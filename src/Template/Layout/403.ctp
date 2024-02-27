<!DOCTYPE html>
<html>
  <head>
	<title>HC</title>
	<?php
		
		echo $this->Html->meta('favicon.ico','images/favicon.ico',array('type' => 'icon'));
		echo $this->Html->css(array(
						'Admin/bootstrap.min.css',
						'Admin/font-awesome/css/font-awesome.min.css',
						'Admin/custom.css'
					));
			
	
		echo $this->fetch('meta');
		echo $this->fetch('css');
		
	?>
  </head>
  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
		  <?php echo $this->fetch('content');  ?> 
		  </div>
    </div>  
</body>
</html>		  
		  
		  
 
