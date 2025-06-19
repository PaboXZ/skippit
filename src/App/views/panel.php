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
    
	<?php if(isset($_SESSION["active_thread"])): ?>
    <!--Sidebar-->     
    <nav class="sidemenu" id="sidemenu">
        <ul>
            <?php foreach($threads as $thread): ?>
                <li class="<?= $thread['id'] == $_SESSION['active_thread'] ? "active-thread" : "inactive-thread" ?>"><a href="/change-thread/<?= $thread['id']?>"><?= $thread['name'] ?></a><br></li>
            <?php endforeach; ?>
            <li onclick="showDialogBox('add-thread')"><a id="create-thread">+ New thread</a></li>
        </ul>
    </nav>
    <!--Main area-->            
    <main>
        <div class="big-box">
            <div id="thread-active-name"><?= $active_thread['name'] ?></div>
            <?php
            //DELETE PERMISSION NEEDED
            //LOOP FOR TASK COUNT
            //POWER NEEDED
            ?>
            <div class="two-column">
                <?php foreach($tasks as $task): ?>
                <div class="task-show task-power-<?=$task['power']?>">
                    <div class="task-header">
                        <div class="task-title"><?=$task['title']?></div>
                        <div class="task-title-menu" onclick="showTaskMenu('task-title')">
                            <i class="icon-menu"></i>
                            <ul class="task-menu-list" id="task-menu-list-task-title">
                                <li onclick="deleteTask('task-title', 'task-id')">Usuń wpis</li>
                            </ul>
                        </div>
                    </div>
                    <div class="task-content"><?=$task['content']?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
 

    <?php if($active_thread['create_power'] > 0): ?>
        
        <!--Add task floating button-->   
        <div id="add-task-button" onclick="showDialogBox('add-task')">+ Add task</div>

        <!--Add task form-->
        <aside>
            <div class="blur-background" id="add-task" style="<?= isset($oldFormData['task_title']) ? "display: block;" : "" ?>">
                <div class="small-box-centered valign-5 bg-tile">
                    <div class="dialog-box-close" onclick="closeDialogBox('add-task')"><i class="icon-cancel"></i></div>
                        <form class="form-standard" action="/create-task" method="POST">
                            <div class="text-mid">Create Task</div>
                            <div>Task title:</div>
                            <input type="text" name="task_title" placeholder="Task title" value=""/>
                            <?php if(isset($errors['task_title'])): ?>
                                <div class="form-error-message"><?=$errors['task_title'][0]?></div>
                            <?php endif;?>
                            <div>Content:</div>
                            <textarea name="task_content" rows="6"></textarea><br>
                            <?php if(isset($errors['task_content'])): ?>
                                <div class="form-error-message"><?=$errors['task_content'][0]?></div>
                            <?php endif;?>
                            <div>Priority:</div>
                            <?php switch($active_thread['create_power']): 
                                case 5: ?>
                            <div>
                                <input type="radio" name="task_power" value="5" id="power-high"/>
                                <label for="power-high">High</label>
                            </div>
                                <?php case 4: ?>
                            <div>
                                <input type="radio" name="task_power" value="4" id="power-mid-high"/>
                                <label for="power-mid-high">Mid-High</label>
                            </div>
                                <?php case 3: ?>
                            <div>
                                <input type="radio" name="task_power" value="3" id="power-mid"/>
                                <label for="power-mid">Medium</label>
                            </div>
                                <?php case 2: ?>
                            <div>
                                <input type="radio" name="task_power" value="2" id="power-mid-low"/>
                                <label for="power-mid-low">Mid-Low</label>
                            </div>
                                <?php case 1: ?>
                            <div>
                                <input type="radio" name="task_power" value="1" id="power-low" checked/>
                                <label for="power-low">Low</label>
                            </div>
                                <?php default: endswitch; ?>
                            <?php if(isset($errors['task_power'])): ?>
                                <div class="form-error-message"><?=$errors['task_power'][0]?></div>
                            <?php endif;?>
                            <br>
                            <input type="submit" value="Add task"/>
                            <?php include $this->resolve('/partials/_csrf.php'); ?>
                        </form>
                    </div>
                </div>
            </div>
        </aside>
    <?php endif; ?>

    <?php else: ?>
        <!--Empty content-->
        <main>
            <div class="big-box">
                <div id="thread-active-name">Add new thread to begin</div>
                <div class="center"><button class="button" onclick="showDialogBox('add-thread')">Add</button></div>
            </div>
        </main>
    <?php endif; ?>

    

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