<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>:: User Signup ::</title>

</head>

<body>

<style>
	body{ margin:0px; padding:0px;}
	.container{ width:600px; margin:auto;}
	.bg{ width:100%; height:832px; background: url(<?php echo 'http://'.$_SERVER['SERVER_NAME'].$this->request->webroot.'images/bg.png'; ?>) center top no-repeat;}
	
	.table_first tr td{ padding:0px;}
	.name{ font-size:2.1em; color:#088479;}
	.thank{ font-size:1.8em; color:#575757;}
	.confirm{ font-size:17px; color:#4f4f4f;}
	.verify{ background-color:#088479; color:#fff; padding:10px 15px; border-radius:5px; text-decoration:none; font-size:1.7em; margin-top:30px;}
	.verify:hover{ background-color:#055c54;}
	
	.copyright{ font-size:1.1em; color:#8f8f8f;}
	
</style>
 


<table  width="600px" align="center" style="background-color:#060;">
 
    <tr>
    	<td align="center" style="padding-bottom:20px; padding-top:20px;">
        	<img src="<?php echo 'http://'.$_SERVER['SERVER_NAME'].$this->request->webroot ?>assets/images/logo.png">
        </td>
     </tr>

    <tr>
        <td style="padding-left:14px; padding-right:12px;">
            <table width="570px" border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:left; border:1px solid#cfe2e2; background-color:#fff;" class="table_first" >

                <tr height="50px;"><td>&nbsp;</td></tr>
                <tr align="center">
                    <td class="name" style="padding-top:50px;">Hi, <?php echo $data['message']['user']['name']; ?> !</td>
                </tr>
                <tr height="30px;"><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr align="center">
                    <td style="padding-top:5px;" class="confirm"><?php echo $data['msg']; ?> </td>
                </tr>
                <tr align="center">
                    <td style="padding-top:5px;" class="confirm">Subject: <?php echo $data['subject']['subject']; ?></td>
                </tr>
                <tr align="center">
                    <td style="padding-top:5px;" class="confirm">Title: <?php echo $data['title']; ?></td>
                </tr>
                <tr align="center">
                    <td style="padding-top:5px;" class="confirm">Message: <?php echo $data['message']['message']; ?></td>
                </tr>

                <tr height="65px;"><td>&nbsp;</td></tr>
	<tr>
    </tr>
    <tr height="65px;"><td>&nbsp;</td></tr>
</table>
        </td>
     </tr>
     
     <tr>   
        <td style="padding-left:14px; padding-right:12px; padding-bottom:15px;">
        	<table width="570px"  border="0" cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif; font-size:15px; text-align:left; border:1px solid#cfe2e2; background-color:#fff; margin-top:3px;" class="table_first" >
	
    <tr height="20px;"><td>&nbsp;</td></tr>
    <tr align="center">
        <td colspan="5" class="copyright" style="padding-top:20px; padding-bottom:20px;">Copyright @2017 Galaxyico. All rights reserved.</td>
    </tr>
    
    <tr height="20px;"><td>&nbsp;</td></tr>
    
  
    <tr height="20px;"><td>&nbsp;</td></tr>
</table>
        </td>
     </tr>
</table>
</body>
</html>
