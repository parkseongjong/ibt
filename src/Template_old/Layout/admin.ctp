<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
       <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo $this->request->webroot?>assets/images/favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?php echo $this->request->webroot?>assets/images/favicon.ico" type="image/x-icon">
    <title>SMBIT Exchange</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/bower_components/bootstrap/dist/css/bootstrap.min.css'; ?>" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/bower_components/font-awesome/css/font-awesome.min.css'; ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/bower_components/Ionicons/css/ionicons.min.css'; ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/AdminLTE.min.css'; ?>">
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/bower_components/jvectormap/jquery-jvectormap.css'; ?>">
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/pnotify.css'; ?>">
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'assets/css/multi-select.css'; ?>">
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css'; ?>">
    
   
    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/bower_components/dist/css/skins/_all-skins.min.css'; ?>">


    <link rel="stylesheet" href="<?php echo $this->request->webroot.'css/Admin/style.css'; ?>">
    <script src="<?php echo $this->request->webroot.'css/Admin/bower_components/jquery/dist/jquery.min.js'; ?>"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>


<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  
<?php 
echo $this->element('Admin/header'); 
if($authUser['user_type']== 'A'){
	echo $this->element('Admin/left_sidebar'); 
}else{
	echo $this->element('Admin/left_sidebar_user'); 
}
echo $this->fetch('content');
echo $this->element('Admin/footer');
die;
?> 
