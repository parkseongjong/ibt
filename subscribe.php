<?php

$email   = $_POST['subsemail'];

    	$toEmail = 'hedgeconnect@gmail.com';
            $emailSubject = '';
            $htmlContent = '<h2>Email Subscription</h2>
                <h4>Email</h4><p>'.$email.'</p>';
            
            // Set content-type header for sending HTML email
           $mailHeaders = "From: " . ' '. "<". $email .">\r\n";
	   $mailHeaders .= "Reply-To: ". strip_tags($_POST['req-email']) . "\r\n";
	   $mailHeaders .= "CC: susan@example.com\r\n";
	   $mailHeaders .= "MIME-Version: 1.0\r\n";
	   $mailHeaders .= "Content-Type: text/html; charset=UTF-8\r\n";

            $thanksHtmlMail = file_get_contents("thanks_mail.html");
	$thanksHtml = str_replace("[content]",$email,$thanksHtmlMail);


            // Send email
            if(mail($toEmail,$emailSubject,$thanksHtml,$mailHeaders)){
                echo 'Your request has been submitted successfully !';
                die;
            }else{
                echo  'Your request submission failed, please try again.';
                die;
            }



?>
