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
            <li id="add-thread-button"><a id="create-thread">+ New thread</a></li>
        </ul>
    </nav>
    <!--Main area-->            
    <main>
        <div class="big-box">
            <div id="thread-active-name"><?= $active_thread['name'] ?></div>
            <?php
            //POWER NEEDED
            ?>
            <div class="two-column">
                <?php foreach($tasks as $task): ?>
                <div class="task-show task-power-<?=$task['power']?>">
                    <div class="task-header">
                        <div class="task-title" id="task-title-<?=$task['id']?>"><?=$task['title']?></div>
                        <div class="task-title-menu" id="task-title-menu-<?=$task['id']?>">
                            <i class="icon-menu"></i>
                        </div>
                        <ul class="task-menu-list" id="task-menu-list-<?=$task['id']?>">
                            <li >Pin</li>
                            <?php if($active_thread['edit_permission']): ?>
                                <li >Edit</li>
                            <?php endif;?>
                            <?php if($active_thread['complete_permission']): ?>
                                <li >Complete</li>
                            <?php endif;?>
                            <?php if($active_thread['delete_permission']): ?>
                                <li class="delete-task" id="delete-task-<?=$task['id']?>">Delete</li>
                            <?php endif;?>
                        </ul>
                    </div>
                    <div class="task-content"><?=$task['content']?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
 

    <?php if($active_thread['create_power'] > 0): ?>
        
        <!--Add task floating button-->   
        <div id="add-task-button">+ Add task</div>

        <!--Add task form-->
        <aside>
            <div class="blur-background" id="add-task" style="<?= isset($oldFormData['task_title']) ? "display: block;" : "" ?>">
                <div class="small-box-centered valign-5 bg-tile">
                    <div class="dialog-box-close" id="close-add-task"><i class="icon-cancel dialog-box-close-ico"></i></div>
                        <form class="form-standard" action="/create-task" method="POST">
                            <div class="text-mid">Create Task</div>
                            <div>Task title:</div>
                            <input type="text" name="task_title" placeholder="Task title" value="<?= isset($oldFormData['task_title']) ? $oldFormData['task_title'] : ""?>"/>
                            <?php if(isset($errors['task_title'])): ?>
                                <div class="form-error-message"><?=$errors['task_title'][0]?></div>
                            <?php endif;?>
                            <div>Content:</div>
                            <textarea name="task_content" rows="6"><?= isset($oldFormData['task_content']) ? $oldFormData['task_content'] : ""?></textarea><br>
                            <?php if(isset($errors['task_content'])): ?>
                                <div class="form-error-message"><?=$errors['task_content'][0]?></div>
                            <?php endif;?>
                            <div>Priority:</div>
                            <?php switch($active_thread['create_power']): 
                                case 5: ?>
                            <div>
                                <input type="radio" name="task_power" value="5" id="power-high" <?= isset($oldFormData["task_power"]) && $oldFormData["task_power"] == "5" ? "checked" : ""?> />
                                <label for="power-high">High</label>
                            </div>
                                <?php case 4: ?>
                            <div>
                                <input type="radio" name="task_power" value="4" id="power-mid-high" <?= isset($oldFormData["task_power"]) && $oldFormData["task_power"] == "4" ? "checked" : ""?>/>
                                <label for="power-mid-high">Mid-High</label>
                            </div>
                                <?php case 3: ?>
                            <div>
                                <input type="radio" name="task_power" value="3" id="power-mid" <?= isset($oldFormData["task_power"]) && $oldFormData["task_power"] == "3" ? "checked" : ""?> />
                                <label for="power-mid">Medium</label>
                            </div>
                                <?php case 2: ?>
                            <div>
                                <input type="radio" name="task_power" value="2" id="power-mid-low" <?= isset($oldFormData["task_power"]) && $oldFormData["task_power"] == "2" ? "checked" : ""?> />
                                <label for="power-mid-low">Mid-Low</label>
                            </div>
                                <?php case 1: ?>
                            <div>
                                <input type="radio" name="task_power" value="1" id="power-low" <?= (isset($oldFormData["task_power"]) && $oldFormData["task_power"] == "1") || !isset($oldFormData["task_power"]) ? "checked" : ""?>/>
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
                <div class="center"><button class="button" id="create-thread">Add</button></div>
            </div>
        </main>
    <?php endif; ?>

    

    <!--Confirm action box-->
    <aside class="blur-background" id="confirm-action-box">
        <div class="mid-box-centered bg-tile">
            <div class="dialog-box-title dialog-box-close" id="close-confirm-action-button"><i class="icon-cancel dialog-box-close-ico"></i></div>
            <div class="message-box" id="confirm-action-text">Text</div>
            <div class="button" id="action-confirm">Accept</div>				
            <div class="reverse-button" id="action-decline">Back</div>
        </div>
	</aside>
	
	<!--Add thread box-->
	<aside>
		<div class="blur-background" id="add-thread" style="<?= isset($oldFormData['thread_name']) ? "display: block;" : "" ?>">
            <div class="small-box-centered valign-20 bg-tile">
                <div class="dialog-box-close" id="close-add-thread"><i class="icon-cancel dialog-box-close-ico"></i></div>
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
    <aside>
        <div class="hidden-barrier"></div>
    </aside>
    <script src="/assets/js/dialog.box.js"></script>
    <script src="/assets/js/panel.dialog.box.js"></script>
    <script src="/assets/js/task.js"></script>
</body>
</html>