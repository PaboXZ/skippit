<?php include $this->resolve('/partials/_header.php'); ?>

<body>
	<?php include $this->resolve('/partials/_message_display.php');?>
    <main>
        <div>
            <p class="center text-huge">Skippit<p>
            <p class="center text-mid" >Skip it, or complete it.</p>
        </div>
        <div class="small-box-centered">
            <form class="form-standard" action="/login" method="POST">
                <input value="<?=isset($oldFormData['login']) ? e($oldFormData['login']) : ""?>" type="text" placeholder="login" name="login" onfocus="this.placeholder=''" onblur="this.placeholder='login'"/>
                
                <?php if(isset($errors['login'])) :?>
                <div class="form-error-message"><?=$errors['login'][0]?></div>
                <?php endif; ?>
                
                <input value="" type="password" placeholder="password" name="password" onfocus="this.placeholder=''" onblur="this.placeholder='password'"/>
                
                <?php if(isset($errors['password'])) :?>
                <div class="form-error-message"><?=$errors['password'][0]?></div>
                <?php endif; ?>
                
                <input type="submit" value="Log in"/>
                <?php #CSRF TOKEN
                include $this->resolve('partials/_csrf.php'); ?>
                <div class="reverse-button" onclick="showDialogBox('dialog-box-register')">Register</div>
            </form>
        </div>  
	</main>	
	
    <aside class="blur-background" id="dialog-box-register" style="<?=isset($oldFormData['login_r']) ? "display: block;" : ""?>">
        <div class="small-box-centered bg-tile valign-20">
            <div class="dialog-box-close">
                <i onclick="closeDialogBox('dialog-box-register')" class="icon-cancel dialog-box-close-ico"></i>
            </div>
            <form class="form-standard" id="register-form" action="/register" method="POST">
                <h4>Register</h4>
                <input value="<?= isset($oldFormData['login_r']) ? e($oldFormData['login_r']) : ""?>" type="text" name="login_r" placeholder="login"/>

                <?php if(isset($errors['login_r'])): ?>
                    <div class="form-error-message"><?=$errors['login_r'][0]?></div>
                <?php endif;?>

                <input value="<?= isset($oldFormData['email']) ? e($oldFormData['email']) : ""?>"type="text" name="email" placeholder="email"/>
                
                <?php if(isset($errors['email'])): ?>
                    <div class="form-error-message"><?=$errors['email'][0]?></div>
                <?php endif;?>

                <input value="" type="password" name="password_r" placeholder="password"/>

                <?php if(isset($errors['password_r'])): ?>
                    <div class="form-error-message"><?=$errors['password_r'][0]?></div>
                <?php endif;?>

                <input type="password" name="password_confirm" placeholder="confirm password"/>
                
                <?php if(isset($errors['password_confirm'])): ?>
                    <div class="form-error-message"><?=$errors['password_confirm'][0]?></div>
                <?php endif;?>
                
                <div>
                    <input type="checkbox" name="tos" id="tos" <?= isset($oldFormData['tos']) ? "checked" : ""?>/>
                    <label for="tos">I accept <a class="reverse-button-small"  href="ToS.pdf">Terms of Service</a></label>
                </div>
                
                <?php if(isset($errors['tos'])): ?>
                    <div class="form-error-message"><?=$errors['tos'][0]?></div>
                <?php endif;?>

                <input type="submit" value="Register"/>
                <?php #CSRF TOKEN
                    include $this->resolve('partials/_csrf.php'); ?>
            </form>
        </div>
	</aside>
		
	<footer>
        <p>&copy;Mose creations</p>
	</footer>
</body>
</html>