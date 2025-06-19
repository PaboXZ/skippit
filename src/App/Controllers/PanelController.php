<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TaskService;
use App\Services\ThreadService;
use App\Services\ValidatorService;
use Framework\Exceptions\ValidatorException;
use Framework\TemplateEngine;

class PanelController{
    public function __construct(
        private TemplateEngine $view,
        private ThreadService $threads,
        private TaskService $tasks,
        private ValidatorService $validator
    )
    {
        
    }

    public function view()
    {
        //Sprawdzić aktywny wątek, rodzaj konta, zezwolenia w aktywnym wątku
        //Pobrać wpisy z aktywnego wątku z uwzględnieniem uprawnień			
        /*
			Pobranie ulubionych wątków z bazy danych
			Print wątków do panelu bocznego HTML
		*/
        $threads = $this->threads->getUsersThreads($_SESSION['user']);
        if(isset($_SESSION['active_thread'])) {
            $active_thread = $this->threads->getUsersThreadByID($_SESSION['active_thread']);
            $this->view->addGlobal('active_thread', $active_thread);

            $tasks = $this->tasks->getTasksByID($active_thread['id']);
            $this->view->addGlobal('tasks', $tasks);
        }

        $this->view->addGlobal('threads', $threads);
        $this->view->addGlobal('styles', ['/assets/css/task.css', '/assets/css/thread.css']);

        echo $this->view->render('/panel.php');
    }

    public function createThread(){

        if($this->threads->getCount() === 10)
        {
            throw new ValidatorException(['thread_name' => ['Exceeded thread limit, max: 10']]);
        }
        $this->validator->validateThread($_POST);
        $this->threads->create($_POST['thread_name']);
        redirectTo('/');
    }

    public function changeThread(array $params) {
        $params['id'] = (int) $params['id'];
        if($this->threads->getUsersThreadByID($params['id']))
            $_SESSION['active_thread'] = $params['id'];
        redirectTo('/');
    }

    public function createTask() {
        if(!isset($_SESSION['active_thread'])){
            redirectTo('/');
            exit;
        }

        $activeThread = $this->threads->getUsersThreadByID($_SESSION['active_thread']);
        $this->validator->validateTask($_POST, $activeThread['create_power']);

        $data['name'] = $_POST['task_title'];
        $data['content'] = $_POST['task_content'];
        $data['priority'] = (int) $_POST['task_power'];
        $data['userID'] = $_SESSION['user'];
        $data['threadID'] = $_SESSION['active_thread'];

        $this->tasks->create($data);
        
        redirectTo('/');
    }
}