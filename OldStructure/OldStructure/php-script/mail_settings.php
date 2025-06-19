<?php

if(count(get_included_files()) == 1)
{
	exit('Access Denied');
}

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;




function getMail()
{


	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';
	require 'php-script/mail_credentials.php';
	
	
	try
	{
		$mail = new PHPMailer();
		
		$mail->isSMTP();
		$mail->SMTPAuth = true;
		$mail->Port = 465;
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
		
		$mail->Host = $mail_host;
		$mail->Username = $mail_user;
		$mail->Password = $mail_password;
		
		$mail->CharSet = 'UTF-8';
		$mail->isHTML(true);
		$mail->setFrom($mail_name, 'Skippit.pl');
		
		return $mail;
	}
	catch(Exception $error)
	{
		return FALSE;
	}
}

?>