<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $coinNameStatic; ?> | SUCCESS</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
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


    ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
	<link rel="shortcut icon" href="<?=$this->request->webroot ?>assets/hedge/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="index.html"><b><?php echo $coinNameStatic; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg"></p>

        
                    <?=$this->Flash->render();?>
         
        
        <a href="<?php echo $this->Url->build(['controller'=>'users','action'=>'login']);  ?>" class="text-center">Login</a>

    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?php echo $this->request->webroot.'css/Admin/bower_components/jquery/dist/jquery.min.js'; ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $this->request->webroot.'css/Admin/bower_components/bootstrap/dist/js/bootstrap.min.js'; ?>"></script>
<!-- iCheck -->
<script src="<?php echo $this->request->webroot.'css/Admin/iCheck/icheck.min.js'; ?>"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
	
	
</script>


<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '1512266082175831');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=1512266082175831&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


<script>
  fbq('track', 'CompleteRegistration');
</script>


</body>
</html>
