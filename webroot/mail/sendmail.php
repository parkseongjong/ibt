<?php 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php'; 

	$email_from = "alok@gmail.com";
	$name = 'test';
	$str = "<h1>testsfvsa</h1>";
    $mail = new PHPMailer(true); 
     
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1;  // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true;  // authentication enabled
    //$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = 'smtp.mandrillapp.com';
    $mail->Port = 587; 
   // $mail->Username = "technoloaderjaipur@gmail.com";  
   // $mail->Password = "IAV9509472";    
    
    $mail->Username = "Massconnects";  
    $mail->Password = "SV7gYd0qBN940k_7NKROow";    
           
    $mail->SetFrom($email_from, "TechnoLoader");
  //  $mail->SetFrom("technoloader@gmail.com", "TechnoLoader");
    
    $mail->isHTML(true);                                 
    $mail->Subject = "Get A Quote : ".$name;
  
    $mail->msgHTML($str);
    
   // $mail->AddAddress('narendra@technoloader.com');
    $mail->AddAddress('mighty.ambrish@gmail.com');
 //   $mail->AddAddress('mohsinkureshi786@gmail.com');
    //$mail->AddAddress('vipin0206@gmail.com');
    //$mail->AddAddress('hada@live.in');
    
    if(!$mail->Send()) {
       $error = 'Mail error: '.$mail->ErrorInfo; 
        //return false;
    } else {
        echo $error = 'Thank you for contacting us. We will be in touch with you very soon.'; die;
        //return true;
    }
   
?>