<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}
	
	if(isset($_SESSION['error_thread_delete']))
	{
		$error_message = $_SESSION['error_thread_delete'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_thread_delete']);
	}
	if(isset($_SESSION['error_change_user_password']))
	{
		$error_message = $_SESSION['error_change_user_password'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_change_user_password']);
	}
	if(isset($_SESSION['error_ignore_user']))
	{
		$error_message = $_SESSION['error_ignore_user'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_ignore_user']);
	}
	if(isset($_SESSION['error_ignore_notifications']))
	{
		$error_message = $_SESSION['error_ignore_notifications'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_ignore_notifications']);
	}
	if(isset($_SESSION['error_unignore_user']))
	{
		$error_message = $_SESSION['error_unignore_user'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_unignore_user']);
	}
	if(isset($_SESSION['error_add_user']))
	{
		$error_message = $_SESSION['error_add_user'];
		$error_style = "#dialog-box-message{display: block;} #error-text{display: block;}";
		unset($_SESSION['error_add_user']);
	}
?>