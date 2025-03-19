<?php
	session_start();

	require_once('php-script/rules.php');
	isLoggedIn();
	redirectTemporary();
	
	require_once("php-script/db_connect.php");
	$db_connection = db_connect();
	
	
	require_once('php-script/message_print.php');
	require_once('php-script/settings_error_print.php');
	
	require_once('php-script/print_data.php');
	require_once('php-script/check_thread.php');
	
	$thread_names = printThreadNamesId($db_connection, $_SESSION['user_id']);
	
	$threads_menu_html="";
	if($thread_names)
	{
		foreach($thread_names as $thread_name)
		{
			$owner_text = "";
			$guest_edit_button = "";
			if(checkThreadOwner($thread_name[0]) == $_SESSION['user_id'])
			{
				$temp_message = "Czy chcesz usunąć listę: $thread_name[1]? Wszystkie dane oraz użytkownicy tymczasowi zostaną usunięci.";
				$owner_text = '<sup style="font-size: 0.6em"> <i class="icon-user"></i>administrator<sup>';
			}
			else
			{
				$temp_message = "Czy chcesz opuścić listę: $thread_name[1]?";
				$guest_edit_button = ' style="background-color:gray;"';
			}
			$temp_target = "thread_delete.php";
			$temp_data = "?thread_id=$thread_name[0]";
			$threads_menu_html .= '
					<div class="row gx-1 gx-lg-3" gx-xl-5>
						<div class="col-9 offset-lg-1 col-lg-8">
							<div class="settings-thread-name">
								<a href="change_active_thread.php?id='.$thread_name[0].'">'.$thread_name[1].$owner_text.'</a>
							</div>
						</div>
						<div class="col-2 col-lg-1">
							<div class="settings-thread-button"'.$guest_edit_button.'>
								<a href="settings.php?thread_id='.$thread_name[0].'" class="icon-edit"></a>
							</div>
						</div>
						<div class="col-1" onclick="corfirmActionDisplay(\''.$temp_message.'\', \''.$temp_target.'\', \''.$temp_data.'\')">
							<div class="settings-thread-button" style="background-color: red;">
								<i class="icon-trash"></i>
							</div>
						</div>
					</div>
			';
		}
		
		
		
		
		
		
		$threads_menu_html .= '
				<hr>
				<div class="col-12 offset-lg-1 col-lg-11 settings-thread-header">
					Szybki dostęp:
				</div>
				<div class="col-12 offset-lg-1 col-lg-11">
					<form action="set_favorite_threads.php" method="POST">
					';		
				
		if(count($thread_names) > 5) $thread_count = 5;
		else $thread_count = count($thread_names);
		
		$favorite_threads = favoriteThreadsGet($db_connection, $_SESSION['user_id']);
				
		for($i = 1; $i < $thread_count + 1; $i++)
		{
			$threads_menu_html .= '
					<label for="favorite-'.$i.'">Lista '.$i.':</label>
					<select id="favorite-'.$i.'" name="favorite-'.$i.'">
					';
			
			foreach($thread_names as $thread_name)
			{
				
				if($favorite_threads[$i-1] == $thread_name[0]) $selected_option_text = 'selected="true"';
				
				else $selected_option_text = '';
				$threads_menu_html .= '<option '.$selected_option_text.'value="'.$thread_name[0].'">'.$thread_name[1].'</option>';
			}
			
			$threads_menu_html .= '</select>';
		}
		$threads_menu_html .= '
					<input type="submit" value="Ustaw"/>
				</form>
			</div>
		';
	}
	
	if(isset($_GET['thread_id']))
	{
		if(checkThreadByID($_SESSION['user_id'], $_GET['thread_id']))
		{
			$_SESSION['user_active_thread'] = $_GET['thread_id'];
			$change_active_tab = 1;
		}
	}
	
	if(isset($_GET['user']))
	{
		$change_active_tab = 2;
	}
	
	
	
	
	if(isset($_SESSION['user_active_thread']))
	{
		try
		{
			$active_thread_id = $_SESSION['user_active_thread'];
			if(!$db_query_result = $db_connection->query("SELECT thread_name FROM thread_data WHERE thread_id = '$active_thread_id'"))
			{
				throw new Exception("");
			}
			if($db_query_result->num_rows != 1)
			{
				throw new Exception("");
			}
			$db_result = $db_query_result->fetch_assoc();
			$active_thread_button_html = '
			<div class="settings-tab-inactive settings-tab col-4 col-lg-2" id="menu-tab-1" onclick="changeActiveTab(1)">
				'.$db_result['thread_name'].'
			</div>
			';
		}
		catch(Exception $error)
		{
			$active_thread_button_html = "";
		}
	}
	
	$user_id = $_SESSION['user_id'];
	$active_thread_id = $_SESSION['user_active_thread'];
	$settings_thread_users_html = "";
	$add_user_form = "";
	$add_temp_user_form = "";
	try
	{
		if(!$db_result = $db_connection->query("SELECT thread_id FROM thread_data WHERE thread_id = '$active_thread_id' AND thread_owner_id = '$user_id'"))
		{
			throw new Exception();
		}
		if($db_result->num_rows == 1)
		{
			$is_owner_flag = true;
		}
		else
		{
			$is_owner_flag = false;
		}
		
		if(!$db_result = $db_connection->query("SELECT user_name, user_id, connection_is_owner FROM user_data INNER JOIN connection_user_thread ON user_id = connection_user_id WHERE connection_thread_id = '$active_thread_id'"))
		{
			throw new Exception();
		}
		$settings_thread_users_html = '
		<div class="row">
			<div class="col-12 offset-lg-1 col-lg-11 settings-thread-header">
				Użytkownicy:
			</div>
		';
		for($i = $db_result->num_rows; $i > 0; $i--)
		{
			if(!$is_owner_flag)
			{
				$db_result_row = $db_result->fetch_assoc();
				$settings_thread_users_html .= '
				<div class="offset-1 col-10 offset-lg-2 col-lg-8">
					<div class="settings-user-name">'.$db_result_row['user_name'].'</div>
				</div>
				';
			}
			else
			{
				$db_result_row = $db_result->fetch_assoc();
				$settings_thread_users_html .= '
				<div class="col-8 offset-lg-1 col-lg-7">
					<div class="settings-user-name">'.$db_result_row['user_name'].'</div>
				</div>';
				
				if($db_result_row['connection_is_owner'] == 0)
				{
					$settings_thread_users_html .= '
					<div class="col-2 col-lg-1">
						<div class="icon-cog settings-thread-button" style="background-color: slategray;"></div>
					</div>
					<div class="col-2 col-lg-1">
						<a class="icon-trash settings-thread-button" style="background-color: red; display: block;" href="remove_user.php?user_id='.$db_result_row['user_id'].'"></a>
					</div>
					';
				}
				
				$add_user_form = '
				<hr>
				<div class="col-12 offset-lg-1 col-lg-11 settings-thread-header">
					Dodaj istniejącego użytkownika:
				</div>
				<div class="col-12 offset-lg-1 col-lg-11">
					<form action="add_user.php" method="POST">
						<input type="text" name="user_name" placeholder="Login/email"/>
						<input type="submit" value="Dodaj"/>
					</form>
				</div>
				';
				$add_temp_user_form = '
				<hr>
				<div class="col-12 offset-lg-1 col-lg-11 settings-thread-header">
					Dodaj tymczasowego użytkownika:
				</div>
				<div class="col-12 offset-lg-1 col-lg-11">
					<form action="add_user_temp.php" method="POST">
						<input name="user_name" type="text" placeholder="Login"/>
						<input name="user_password" type="password" placeholder="hasło"/>
						<input type="submit" value="Dodaj"/>
					</form>
				</div>
				';
			}
		}
		$settings_thread_users_html .= '</div>';
		
		if(!$db_result = $db_connection->query("SELECT user_ignored FROM user_data WHERE user_id = '{$_SESSION['user_id']}'"))
		{
			throw new Exception();
		}
		
		if($db_result->num_rows != 1)
		{
			throw new Exception();
		}
		
		$db_result = $db_result->fetch_column();
		
		
		if(strlen($db_result) > 0)
		{
			$ignored_users_html = '
									<div>
										<hr>
										<h4>
											Lista zablokowanych:
										</h4>
										<div class="row">';
			
			$db_result = explode(',', $db_result);
			
			foreach($db_result as $ignored_user)
			{
				$ignored_users_html .= '<div class="col-9">'.$ignored_user.'</div> <div class="col-2"><a href="unignore_user.php?unignored_user='.$ignored_user.'">USUŃ</a></div>';
			}
			
			$ignored_users_html .= '</div></div>';
		}
		
		
	}
	catch(Exception $error)
	{
	}
	
	db_close($db_connection);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/> 
	<link rel="stylesheet" href="bootstrap/css/bootstrap.css"/>
	<link rel="stylesheet" href="css/main.css"/>
	<link rel="stylesheet" href="css/settings.css"/>
	<link rel="stylesheet" href="css/fontello.css"/>
	
	<title>Skippit - ustawienia</title>
	<link rel="icon" type="image/x-icon" href="favicon/favicon-32x32.png"/>
	
	<script src="js/dialog.box.js"></script>
	<script>
		tabIdOld = 0;
		function changeActiveTab(tabIdNew)
		{
			document.getElementById('menu-content-' + tabIdOld).style.cssText = 'display: none';
			document.getElementById('menu-tab-' + tabIdOld).classList.add('settings-tab-inactive');
			
			tabIdOld = tabIdNew;
			
			document.getElementById('menu-content-' + tabIdNew).style.cssText = 'display: flex';
			document.getElementById('menu-tab-' + tabIdNew).classList.remove('settings-tab-inactive')
		}
	</script>
	
	<style><?=isset($error_style) ? $error_style : ""?><?=isset($message_style) ? $message_style : ""?></style>
</head>
<body onload="changeActiveTab(<?=isset($change_active_tab) ? $change_active_tab : "0"?>)">

	<!--Confirm action box-->
	<aside class="blur-background" id="confirm-action-box">
		<div class="container">
			<div class="row">
				<div class="dialog-box offset-1 col-10">
					<div class="row">
						<div class="offset-10 col-1 offset-lg-11">
							<div class="dialog-box-title dialog-box-close" onclick="closeDialogBox('confirm-action-box'); clearConfirmActionBox();">
								<i class="icon-cancel"></i>
							</div>
						</div>
						<div class="col-12 offset-md-1 col-md-10">
							<div class="message-container" id="confirm-action-text">Tekst</div>
						</div>
						<div class="offset-2 col-4 offset-lg-4 col-lg-2">
							<div class="confirm-action-button" id="action-confirm">
								Akceptuj
							</div>
						</div>						
						<div class="col-4 col-lg-2">
							<div class="confirm-action-button" id="action-decline" onclick="closeDialogBox('confirm-action-box'); clearConfirmActionBox();">
								Powrót
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</aside>
	<!--Message/error box-->
	<aside class="blur-background" id="dialog-box-message">
		<div class="container">
			<div class="row">
				<div class="dialog-box offset-1 col-10">
					<div class="row">
						<div class="offset-10 col-1 offset-lg-11">
							<div class="dialog-box-title dialog-box-close" onclick="closeDialogBox('dialog-box-message')">
								<i class="icon-cancel"></i>
							</div>
						</div>
						<div class="col-12 offset-md-1 col-md-10">
							<div class="message-container" id="error-text"><?=isset($error_message) ? $error_message : ""?></div>
							<div class="message-container" id="message-text"><?=isset($message) ? $message : ""?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</aside>
	
	
	<div id="topbar">
		<div class="logo col-1 d-lg-none topnav-button-mobile">
			<a href="panel.php">Skippit</a>
		</div>
		<div class="d-none col-1 d-lg-none topnav-button-mobile" id="mobile-thread-menu-close" onclick="hideSideMenu()">
			<i class="icon-left-open"></i>
		</div>
		<div class="offset-7 col-1 d-lg-none topnav-button-mobile topnav-button-mobile-right">
			<a href="settings.php"><i class="icon-cog"></i></a><br>
		</div>
		<div class="offset-1 col-1 d-lg-none topnav-button-mobile topnav-button-mobile-right" onclick="window.location.href='logout.php'">
			<i class="icon-off"></i><br>
		</div>
	
	
		<div class="container">
			<div class="row">
				<div class="logo d-none d-lg-block col-8"><a href="panel.php">Skippit</a></div>
				<div class="d-none d-lg-block col-2"><a href="settings.php" class="topnav-button"><?=$_SESSION['user_name']?> <i class="icon-cog"></i></a></div>
				<div class="d-none d-lg-block col-2"><a href="logout.php" class="topnav-button" id="logout-button">Log out <i class="icon-off"></i></a></div>
			</div>
		</div>
	</div>
	

	
	<main>
		<div class="container">
			<div class="row">
				<div class="settings-title">Ustawienia</div>
				<div class="settings-tab-inactive settings-tab col-4 offset-lg-1 col-lg-2" id="menu-tab-0" onclick="changeActiveTab(0)">
					<i class="icon-menu"></i><span class="d-none d-lg-inline">Listy<span>
				</div>
				<?=$active_thread_button_html?>
				<div class="settings-tab-inactive settings-tab col-4 col-lg-2" id="menu-tab-2" onclick="changeActiveTab(2)">
					<i class="icon-user"></i><span class="d-none d-lg-inline">Użytkownik</span>
				</div>
			</div>
			<div class="row">
				<div class="settings-content col-12 col-xl-11">
					<div class="row settings-content-tab" id="menu-content-0">
							<?=isset($threads_menu_html) ? $threads_menu_html : ""?>
					</div>
					<div class="row settings-content-tab" id="menu-content-1">
							<div>
								<?=isset($settings_thread_users_html) ? $settings_thread_users_html : ""?>
								<?=$add_user_form?>
								<?=$add_temp_user_form?>
							</div>
							<div>
							</div>
					</div>
					<div class="row settings-content-tab" id="menu-content-2">
								<div class="col-12 offset-lg-1 col-lg-11 settings-thread-header">Ignorowanie powiadomień</div>
								<div class="row">
									<div class="offset-1 col-10 col-lg-5">
									<p>Zignoruj powiadomienia od użytkownika:</p>
										<form method="POST" action="ignore_user.php">
											<input type="text" name="ignored_user" placeholder="Nazwa użytkownika"/>
											<input type="submit"/>
										</form>
										<hr>
										<p><?=$_SESSION['user_notifications_ignore'] ? "Odblokuj" : "Zablokuj wszystkie"?> powiadomienia:</p>
										<form method="POST" action="ignore_notifications.php">
											<input type="submit" value="<?=$_SESSION['user_notifications_ignore'] ? "Odblokuj" : "Zablokuj"?>"/>
										</form>
									</div>
									<div class="offset-1 col-10 col-lg-5">
										<?= isset($ignored_users_html) ? $ignored_users_html : ''?>
									</div>
								</div>
							<hr>
								<div class="col-12 offset-lg-1 col-lg-11 settings-thread-header" id="zmien-haslo">Zmień hasło</div>
								<div class="row">
									<div class="offset-1 col-5">
										<form method="POST" action="change_user_password.php">
											<input type="password" name="user_password" placeholder="hasło"/>
											<input type="password" name="user_new_password" placeholder="nowe hasło"/>
											<input type="password" name="user_confirm_password" placeholder="powtórz nowe hasło"/>
											<input type="submit" value="Potwierdź"/>
										</form>
									</div>
								</div>
							<hr>
								<div class="col-12 offset-lg-1 col-lg-11 settings-thread-header">Usuwanie konta</div>
								<div class="row">
									<div class="offset-1 col-5">
										<form method="POST" action="delete_user.php">
											<input type="password" name="user_password_delete" placeholder="hasło"/>
											<input type="password" name="user_password_delete_confirm" placeholder="powtórz hasło"/>
											<input type="submit" value="Usuń"/>
										</form>
									</div>
								</div>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
