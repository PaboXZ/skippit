<?php
/*Required data: none OK
Access: all	OK

Rules: redirect if logged in (beware admin/user panel)
*/
?>

<?php
	session_start();
	
	if(isset($_SESSION['user_id']))
	{
		header("Location: panel.php");
		exit();
	}
		
	require_once('php-script/message_print.php');	
	require_once('php-script/index_error_print.php');
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="css/main.css"/>
	<link rel="stylesheet" href="css/index.style.css"/>
	<link rel="stylesheet" href="css/fontello.css"/>
	
	<title>Skippit - Task manager</title>
	<link rel="icon" type="image/x-icon" href="favicon/favicon-32x32.png"/>
	
	<style><?=isset($error_style) ? $error_style : ""?><?=isset($message_style) ? $message_style : ""?></style>
	<script src="js/dialog.box.js"></script>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<script>
       function onSubmit(token) {
         document.getElementById("register-form").submit();
       }
     </script>
</head>
<body>
	
	<aside class="blur-background" id="dialog-box-message">
		<div class="container">
			<div class="row">
				<div class="dialog-box offset-1 col-10">
					<div class="row">
						<div class="offset-11 col-1">
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
	
	<main class="blur-background" id="dialog-box-login">
		<div class="container">
			<div class="row">
				<div class="offset-xl-4 col-xl-4 offset-xl-3 col-lg-6 offset-md-2 col-md-8 offset-1 col-10">
					<div class="dialog-box">
						<div class="row">
							<div class="offset-1 col-8 offset-xl-1 col-xl-4">
								<div class="dialog-box-title">Zaloguj</div>
							</div>
							<div class="offset-1 col-1 offset-xl-5 col-xl-1 dialog-box-close" onclick="closeDialogBox('dialog-box-login')">
								<div class="dialog-box-title"><i class="icon-cancel"></i></div>
							</div>
							<div class="offset-1 col-10">
								<div class="dialog-box-content">
									<form action="login.php" method="POST">
										<input value="<?=isset($user_login_l) ? $user_login_l : ""?>" type="text" placeholder="login" name="user_name" onfocus="this.placeholder=''" onblur="this.placeholder='login'"/><br>
										<input value="<?=isset($user_password_l) ? $user_password_l : ""?>"type="password" placeholder="hasło" name="user_password" onfocus="this.placeholder=''" onblur="this.placeholder='hasło'"/><br>
										<input type="submit" value="Log in"/>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>	
	
	<aside class="blur-background" id="dialog-box-register">
		<div class="container">
			<div class="row">
				<div class="offset-xl-4 col-xl-4 offset-xl-3 col-lg-6 offset-md-2 col-md-8 offset-1 col-10">
					<div class="dialog-box">
						<div class="row">
							<div class="offset-1 col-8 offset-xl-1 col-xl-4">
								<div class="dialog-box-title">Zarejestruj</div>
							</div>
							<div class="offset-1 col-1 offset-xl-5 col-xl-1 dialog-box-close" onclick="closeDialogBox('dialog-box-register')">
								<div class="dialog-box-title"><i class="icon-cancel"></i></div>
							</div>
							<div class="offset-1 col-10">
								<div class="dialog-box-content">
									<form id="register-form" action="register.php" method="POST">
										<input value="<?=isset($user_login_r) ? $user_login_r : ""?>" type="text" name="user_name" placeholder="login"/>
										<input value="<?=isset($user_email_r) ? $user_email_r : ""?>"type="text" name="user_email" placeholder="email"/>
										<input value="<?=isset($user_password_r) ? $user_password_r : ""?>" type="password" name="user_password" placeholder="hasło"/>
										<input type="password" name="user_password_confirm" placeholder="potwierdź hasło"/>
										<input type="checkbox" name="tos" id="tos"/>
										<label for="tos">Akceptuję <a href="ToS.pdf">regulamin</a></label>
										<input type="submit" class="g-recaptcha" data-callback="onSubmit" data-sitekey="6Lf60YIkAAAAAFcMt8GmhkvTf6YHPDU4da2arDUN" value="Zarejestruj"/>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</aside>
	
	<nav id="topbar">
		<div class="container">
			<div class="row">
				<div class="logo col-1 d-lg-block d-none">
					Skippit
				</div>
				<div class="d-lg-none" id="index-welcome-text">
					<h1>Skippit</h1>
					<p>Skip it, or complete it.</p>
				</div>
				<div class="offset-xl-7 col-xl-2 offset-lg-7 col-lg-2 offset-md-1 col-md-10 col-12">
					<div class="topnav-button" onclick="showDialogBox('dialog-box-login')">Zaloguj</div>
				</div>
				<div class="d-lg-none" style="height: 15px;">
					
				</div>
				<div class="col-xl-2 offset-lg-0 col-lg-2 offset-md-1 col-md-10 col-12">
					<div class="topnav-button" onclick="showDialogBox('dialog-box-register')">Zarejestruj</div>
				</div>
			</div>
		</div>
	</nav>
	
	<div class="d-none d-lg-block" id="index-welcome-text">
		<h1>Skippit</h1>
		<p>Skip it, or complete it.</p>
	</div>
		
	<footer>
		<div class="container">
			<div class="row">
				<div class="col-12" id="footer-top-text">
					&copy;Mose creations 
				</div>
			</div>
		</div>
	</footer>
	
	
	<script src="bootstrap/js/bootstrap.bundle.min.js"></script>


</body>
</html>