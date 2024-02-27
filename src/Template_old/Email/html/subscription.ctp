<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Template Emailer</title>
<style>
a:hover{color:#13904B!important;}
</style>
</head>
<body style="margin:0; padding:0;font-family:'Roboto';">
<link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'>
	<table width="600" border="0" cellspacing="0" cellpadding="0" style="width:600px;font-family:'Roboto';" align="center">
	  <tr>
	  	<td  bgcolor="#0d6070" align="center" style="padding:10px; border:1px #dcdcdc solid;border-bottom:none;"><a href="#"><img width="50" src="http://hedgeconnect.co/logo.png" alt="" border="0"> </a></td>
	  </tr>
	  <tr>
	  	<td width="100%" style="background-color:#f4f4f4; padding:15px;border:1px #dcdcdc solid;"> 
			<table cellpadding="0" cellspacing="0" border="0" style="width:100%;">
				<tr>
					<td style="background:#fff">
					<table border="0" cellspacing="0" cellpadding="0" style="margin: auto;background: #fff;width: 100%;
border: 1px solid #ccc;padding: 10px;" align="center">
						<?php echo $data['subsemail']; ?>
						
					</table>
					</td>
				</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding="0" style="margin:auto;background:#f4f4f4;width:100%" align="center">
					<tr>
						<td width="100%" style="color:#707070;text-align:center;padding:15px 10px 3px 10px;font-size:14px; font-weight: 600;">@2017  Hedge Connect | All rights reserved</td>
					</tr>
			</table>
		</td>

	  </tr>
	</table>
</body>
</html>
