<?php include $this->resolve('/partials/_header.php'); ?>

<body>
	
	<div class="topbar">
        <div class="logo"><a href="/">Skippit</a></div>
        <div class="right">
            <div class="button">Notifications <i class="icon-bell"></i></div>
            <a href="/"><div class="button">Settings <i class="icon-cog"></i></div></a>
            <a href="/logout" id="logout-button"><div class="button">Log out <i class="icon-off"></i></div></a>
        </div>
    </div>
	<?php include $this->resolve('/partials/_message_display.php');?>
    <nav class="sidemenu" id="sidemenu">
        <ul>
            <!--Wątek aktywny-->
            <li class="active-thread"><a href="change_active_thread.php?id=THREAD_ID">Thread Name</a><br></li>
            <!--Wątek nieaktywny-->
            <li class="inactive-thread"><a href="change_active_thread.php?id=THREAD_ID">Thread Name</a><br></li>
            <li onclick="showDialogBox('add-thread')"><a id="create-thread">+ Utwórz</a></li>
        </ul>
    </nav>
				
    <main>
        <div class="big-box">
            <div id="thread-active-name">Thread active name</div>
            <?php
            //DELETE PERMISSION NEEDED
            //LOOP FOR TASK COUNT
            //POWER NEEDED
            ?>
            <div class="two-column">
                <div class="task-show task-power-5">
                    <div class="task-title">Task Title</div>
                    <div class="task-title-menu" onclick="showTaskMenu('task-title')">
                        <i class="icon-menu"></i>
                        <ul class="task-menu-list" id="task-menu-list-task-title">
                            <li onclick="deleteTask('task-title', 'task-id')">Usuń wpis</li>
                        </ul>
                    </div>
                    <div class="task-show task-power-5">
                        <div class="task-content">Content</div>
                </div>
            </div>
        </div>
    </main>


    <?php
    // Przycisk dodawania wpisu, wyświetlać tylko jeśli są uprawnienia (create_power > 0);
    ?>

    <div id="add-task-button" onclick="showDialogBox('add-task')">+ Dodaj wpis</div>

    <?php 
    #Formularz nowego wpisu
    #Potrzebny creation_power do określenia czy wyświetlać okno i jeśli tak to jakie poziomy można nadawać wpisom
    // $priority_levels[0] = array("power-mid-low", "power-mid", "power-mid-high", "power-high");				
	// $priority_levels[1] = array("Średnio-niski", "Średni", "Średnio-wysoki", "Wysoki");
    // Jeśli mamy dane błędu wysyłki formularza to okno powinno być wyświetlone ?>
    
    <aside>
        <div class="blur-background" id="add-task">
            <div class="dialog-box">
                <div>Tworzenie wpisu</div>
                <div class="dialog-box-close" onclick="closeDialogBox('add-task')"><i class="icon-cancel"></i></div>
                    <form action="create_task.php" method="POST">
                        Nazwa wpisu:<br>
                        <input type="text" name="task_title" placeholder="Nazwa wpisu" value=""/>
                        Treść wpisu:<br>
                        <textarea name="task_content" rows="6"></textarea><br>
                        Priorytet:<br>
                        <input type="radio" name="task_power" value="1" id="power-low" checked/>
                        <label for="power-low">Niski</label><br>
                        <input type="radio" name="task_power" value="'.$i.'" id="iterować wartości"/>
                        <label for="Iterować wartości">Iterować wartości</label><br>
                        <br>
                        <input type="submit" value="Dodaj wpis"/>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <!--Confirm action box-->
    <aside class="blur-background" id="confirm-action-box">
        <div class="dialog-box-title dialog-box-close" onclick="closeDialogBox('confirm-action-box'); clearConfirmActionBox();"><i class="icon-cancel"></i></div>
        <div class="message-container" id="confirm-action-text">Tekst</div>
        <div class="confirm-action-button" id="action-confirm">Akceptuj</div>				
        <div class="confirm-action-button" id="action-decline" onclick="closeDialogBox('confirm-action-box'); clearConfirmActionBox();">Powrót</div>
	</aside>
	
	<!--Add thread box-->
	<aside>
		<div class="blur-background" id="add-thread" style="<?= isset($oldFormData['thread_name']) ? "display: block;" : "" ?>">
            <div class="small-box-centered valign-20 bg-tile">
                <div class="dialog-box-close" onclick="closeDialogBox('add-thread')"><i class="icon-cancel dialog-box-close-ico"></i></div>
                <form class="form-standard" action="/create-thread" method="POST">
                    <div class="text-mid">Create Thread</div><br>    
                    <label for="thread_name">Thread's Name:</label>
                    <input type="text" name="thread_name" value="<?=isset($oldFormData['thread_name']) ? e($oldFormData['thread_name']) : ""?>"/>
                    <?php if(isset($errors['thread_name'])) :?>
                    <div class="form-error-message"><?=e($errors['thread_name'][0])?></div>
                    <?php endif; ?>
                    <?php include $this->resolve('/partials/_csrf.php'); ?>
                    <input type="submit" value="Utwórz"/>
                </form>
            </div>
		</div>
	</aside>

</body>
</html>