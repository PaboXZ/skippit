<?php
/*
Access: logged in
Redirect if not logged in
*/
?>

<?php
	
	session_start();
	require_once("php-script/rules.php");
	isLoggedIn();
	
	require_once('php-script/message_print.php');
	require_once('php-script/panel_error_print.php');
			
	require_once('php-script/panel_thread_print.php');
	require_once('php-script/panel_task_print.php');
	
	require_once('php-script/notification_print.php');
	
	$notifications = getNewNotifications(3);
	
	if($notifications)
	{
		$new_notifications = "-alt";
		$notifications_html = '<div>';
		
		foreach($notifications as $notification)
		{
			$notifications_html .= '<div class="notification">'.notificationTranslate($notification).'</div>';
		}
		
		
		$notifications_html .= '</div>';
	}
	else
		$notifications_html = '<div class="notification">Brak powiadomień</div>';

?>



<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/main.css"/>
	<link rel="stylesheet" href="css/task.css"/>
	<link rel="stylesheet" href="css/thread.css"/>
	<link rel="stylesheet" href="css/fontello.css"/>
	
	<title>Skippit - Panel</title>
	<link rel="icon" type="image/x-icon" href="favicon/favicon-32x32.png"/>
	
	<script src="js/dialog.box.js"></script>
	<script src="js/thread.js"></script>
	<script src="js/task.js"></script>
	<style><?=isset($error_style) ? $error_style : ""//panel_error_print.php?><?=isset($message_style) ? $message_style : ""//message_print.php?></style>
</head>
<body>
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
							<div class="message-container" id="error-text"><?=isset($error_message) ? $error_message : ""//panel_error_print.php?></div>
							<div class="message-container" id="message-text"><?=isset($message) ? $message : ""//message_print.php?></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</aside>
	
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
	
	<!--Add task box-->
	<?=isset($add_task_html) ? $add_task_html : ""//panel_task_print.php?>
	
	<!--Add thread box-->
	<aside>
		<div class="blur-background" id="add-thread" style="<?=isset($create_thread_return_style) ? $create_thread_return_style : ""?>">
			<div class="container">
				<div class ="row">
					<div class="col-lg-6 offset-lg-3">
						<div class="dialog-box">
							<div class="row">
								<div class="dialog-box-title col-9">
									Tworzenie listy
								</div>
								<div class="dialog-box-title offset-1 col-1 dialog-box-close" onclick="closeDialogBox('add-thread')">
									<i class="icon-cancel"></i>
								</div>
								<div class="offset-lg-1 col-lg-10">
									<form action="create_thread.php" method="POST">
										<label for="thread_name">Nazwa listy:</label>
										<input type="text" name="thread_name" value="<?=isset($create_thread_name_r) ? $create_thread_name_r : ""?>"/>
										<label for="thread_version">Wersja:</label>
										<select name="thread_version">
											<optgroup label="Version">
												<option value="simple">Simple</option>
												<option value="pro">Pro</option>
											</optgroup>
										</select>
										<input type="submit" value="Utwórz"/>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</aside>
	
	<!--Add task button-->
	<?=isset($task_button_html) ? $task_button_html : ""//panel_task_print.php?>
	
	
	<div id="topbar">
		<div class="col-1 d-lg-none topnav-button-mobile" id="mobile-thread-menu-open" onclick="showSideMenu()">
			<i class="icon-menu"></i>
		</div>
		<div class="d-none col-1 d-lg-none topnav-button-mobile" id="mobile-thread-menu-close" onclick="hideSideMenu()">
			<i class="icon-left-open"></i>
		</div>
		
		<div class="offset-5 col-1 d-lg-none topnav-button-mobile topnav-button-mobile-right" onclick="document.getElementById('notification-window-mobile').classList.toggle('display-none')">
			<i class="icon-bell<?=isset($new_notifications) ? $new_notifications : ''?>"></i><br>
		</div>
		<div id="notification-window-mobile" class="display-none">
			<?= isset($notifications_html) ? $notifications_html : ''?>
		</div>
		<div class="offset-1 col-1 d-lg-none topnav-button-mobile topnav-button-mobile-right">
			<?=$_SESSION['user_temporary_flag'] ? "" : '<a href="settings.php"><i class="icon-cog"></i></a>'?><br>
		</div>
		<div class="offset-1 col-1 d-lg-none topnav-button-mobile topnav-button-mobile-right" onclick="window.location.href='logout.php'">
			<i class="icon-off"></i><br>
		</div>
	
	
		<div class="container">
			<div class="row">
				<div class="logo d-none d-lg-block col-6"><a href="panel.php">Skippit</a></div>
				<div class="d-none d-lg-block col-2">
					<div id="notification-window" class="display-none">
						<?= isset($notifications_html) ? $notifications_html : ''?>
					</div>
					<div class="topnav-button" onclick="document.getElementById('notification-window').classList.toggle('display-none')"><i class="icon-bell<?=isset($new_notifications) ? $new_notifications : ''?>"></i></div>
				</div>
				<div class="d-none d-lg-block col-2"><?= $_SESSION['user_temporary_flag'] ? '<span class="topnav-button">'.$_SESSION['user_name'].'</span>' : '<a href="settings.php" class="topnav-button">'.$_SESSION['user_name'].' <i class="icon-cog"></i></a>'?></div>
				<div class="d-none d-lg-block col-2"><a href="logout.php" class="topnav-button" id="logout-button">Log out <i class="icon-off"></i></a></div>
			</div>
		</div>
	</div>
	
	
	<?=$thread_html//panel_thread_print.php?>
	<main>	
		<div class="container">
			<div class="row">
				
				<main class="col-12 offset-lg-2 col-lg-10">
					<div class="row">
						<div id="thread-active-name"><?=$thread_active_name//panel_thread_print.php?></div>
						
						<?=isset($task_html) ? $task_html : "" //panel_task_print.php?>
					</div>
				</main>

			</div>
		</div>
	</main>




	<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>