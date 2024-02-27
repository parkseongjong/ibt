<!DOCTYPE html>
<html>
  <head>
	<title><?= $coinNameStatic?></title>
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
	<?php $this->setSessionCookie(); // 로그인 세션 쿠키 변조 방지 위한 토큰 생성?>
  </body>
</html>
