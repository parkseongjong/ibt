<?php

$name    = $_POST['name'];
$email   = $_POST['email'];
$mobile  = $_POST['mobile'];
$subject = $_POST['subject'];
$message = $_POST['message'];;


    $toEmail = 'hedgeconnect@gmail.com';
            $emailSubject = $subject;
            $htmlContent = '<h2>Contact Request</h2>
                <h4>Name</h4><p>'.$name.'</p>
                <h4>Email</h4><p>'.$email.'</p>
                <h4>Mobile</h4><p>'.$mobile.'</p>
                <h4>Message</h4><p>'.$message.'</p>';
            
$htmlContent = '
<tr>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">Name</td>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">'.$name.'</td>
</tr>
<tr>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">Email</td>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">'.$email.'</td>
</tr>
<tr>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">Mobile</td>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">'.$mobile.'</td>
</tr>
<tr>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">Message</td>
<td width="100%" style="line-height:20px;color:#707070;text-align:left;padding:5px 10px 5px 0px;font-size:14px; font-weight:normal;">'.$message.'</td>
</tr>
';
            // Set content-type header for sending HTML email
           $mailHeaders = "From: " . ' '. "<". $email .">\r\n";
	   $mailHeaders .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
	   $mailHeaders .= "CC: susan@example.com\r\n";
	   $mailHeaders .= "MIME-Version: 1.0\r\n";
	   $mailHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";

            $thanksHtmlMail = file_get_contents("thanks_mail.html");
	$thanksHtml = str_replace("[content]",$htmlContent,$thanksHtmlMail);

            // Send email
            if(mail($toEmail,$emailSubject,$thanksHtml,$mailHeaders)){
		
                echo 'Your contact request has been submitted successfully !';
                die;
            }else{
                echo  'Your contact request submission failed, please try again.';
                die;
            }



?>

          
