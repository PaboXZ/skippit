<?php
	if(count(get_included_files()) == 1)
	{
		exit("Access denied.");
	}

	function isLoggedIn()
	{
		if(isset($_SESSION['user_id']))
		{
			return TRUE;
		}
		else
		{
			header("Location: index.php");
			exit();
		}
	}	
		
	function redirectTemporary()	
	{
		if($_SESSION['user_temporary_flag'])
		{
			header("Location: panel.php");
			exit();
		}
	}	
	
?>