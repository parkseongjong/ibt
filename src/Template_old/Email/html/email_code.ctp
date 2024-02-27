<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verification code</title>
<style>
.logos img{ width:80%; margin:auto;}
@media only screen and (max-width: 600px) {
	table{ width:100%!important;}
	
}
</style>
<link href='https://fonts.googleapis.com/css?family=Raleway:400,300,500,700,900' rel='stylesheet' type='text/css'>
</head>

<body style=" font-size:14px; text-align:center; margin:0; color:#000;font-family: 'Raleway', sans-serif !important;">
<table style="background:#f6f6f6; width:100%;    height: 100vh;">
    <tr>
        <td>
            <table align="center" width="600"  style=" background:#fff; ">
        <tbody>
    
          <tr>
          <td><h4 style="text-align: left;
padding-left: 16px; margin:0px;">Hi <?php echo $data['username']; ?>,</h4></td>
          </tr>
   
		  
		  <tr align="center">
            <td><p style="padding:0 3%; line-height:25px;    text-align: justify;">Below is your Authentication code </p></td>
          </tr>
		  
		   <tr>
				  <td align="center";><div style=" font-weight:bold;   padding: 12px 35px;
			color: #fff;
			border-radius:5px;
			text-align:center
			font-size: 14px;
			margin: 10px 0 20px;
			background: #ec552b;
			display: inline-block;
			text-decoration: none;">Authentication Code: <?php  echo $data['new_code'];?></div></td>
			</tr>
          
          <tr align="center">
            <td><p style="padding:0 3%; line-height:25px;    text-align: justify;
            margin:0px;">Thanks, <br/>Team Support</p></td>
          </tr>

          
    
    </tbody>
    </table>
    
  <table align="center" width="600"  style=" background:#f3f5f7; color:#b7bbc1 ">
          
    <tr>
    <td>
    <h4>©<?php echo date('Y'); ?> All right reserved</h4>
    </td>
    </tr>  
    
          
          
         
        </table>
     
</body>
</html>

