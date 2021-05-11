<?php
require_once ('PHPMailer.php');
 require("SMTP.php");
    require("Exception.php");
class email {
    function smtpmailer($emailTo, $subject, $body) { 
    global $error;
  /*  $smtpSecurity       = "tls";//ssl//tls
    $smtpHost           = 'smtp.gmail.com';
    $smtpPort           = 587;//465 //587
    $smtpAuth           = true;
    $smtpDebug          = true;
   // $user               = "";//"waseem.mehmood01@gmail.com";
  //  $password           = '';//'+923215551086';
*/
    $mail = new PHPMailer\PHPMailer\PHPMailer();  			//	create a new object
   // $mail->IsSMTP(); 				//	enable SMTP
    $mail->CharSet	= "utf-8";		//	$mail->CharSet="windows-1251";
   // $mail->SMTPDebug 	= $smtpDebug;		//      debugging: 1 = errors and messages, 2 = messages only
   // $mail->SMTPAuth 	= $smtpAuth;		//      authentication enabled
  //  $mail->SMTPSecure 	= $smtpSecurity; 	// 	secure transfer enabled REQUIRED for Gmail
  //  $mail->Host 	= $smtpHost;		//	$smtpHost;
  //  $mail->Port 	= $smtpPort;		//	$smtpPort;
  //  $mail->Username 	= $user;		//	$smtpUser;
 //   $mail->Password 	= $password;		//	$smtpPassword;
//$mail->SMTPDebug = 1;
    $mail->AddReplyTo('email',"name");
    $mail->SetFrom('email',"NAME");
    $mail->Subject = $subject;  
    $mail->IsHTML(true);
    $mail->Body = $body;  
    $mail->AddAddress($emailTo);


	
    /*$mail->AddBCC('EMAIL');*/

  
try{
    if($mail->Send()){
//	echo "Email Sent";
	return true;
    }else{
     //   echo $mail->ErrorInfo;
	return false;
    }
    
} catch (Exception $e) {
   // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
  
    
   }
}

?>