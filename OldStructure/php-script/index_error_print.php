<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}

	if(isset($_SESSION['error_login']))
	{
		
		$error_message = $_SESSION['error_login'];
		unset($_SESSION['error_login']);
		
		$error_style = "#dialog-box-login{display: block} #dialog-box-message{display: block;} #error-text{display: block;}";
		
		if(isset($_SESSION['error-login-return']['login']))
		{			
			$user_login_l = $_SESSION['error-login-return']['login'];
			$user_password_l = $_SESSION['error-login-return']['password'];
			
			unset($_SESSION['error-login-return']['password']);
			unset($_SESSION['error-login-return']['login']);
		}
	}
	else if(isset($_SESSION['error_register']))
	{
		$error_message = $_SESSION['error_register'];
		unset($_SESSION['error_register']);
		
		$error_style = "#dialog-box-register{display: block} #dialog-box-message{display: block;} #error-text{display: block;}";
		if(isset($_SESSION['error_register_return']['login']))
		{
			$user_login_r = $_SESSION['error_register_return']['login'];
			$user_password_r = $_SESSION['error_register_return']['password'];
			$user_email_r = $_SESSION['error_register_return']['email'];
			
			unset($_SESSION['error_register_return']['login']);
			unset($_SESSION['error_register_return']['password']);
			unset($_SESSION['error_register_return']['email']);
		}
	}	
	if(isset($_SESSION['error_send_activation_mail']))
	{
		
		$error_message = $_SESSION['error_send_activation_mail'];
		unset($_SESSION['error_send_activation_mail']);
		
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
	}
	if(isset($_SESSION['error_activate_user']))
	{
		
		$error_message = $_SESSION['error_activate_user'];
		unset($_SESSION['error_activate_user']);
		
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
	}
	
?>