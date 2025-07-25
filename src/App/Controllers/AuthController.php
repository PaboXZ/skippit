<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ThreadService;
use App\Services\UserService;
use App\Services\ValidatorService;
use Framework\TemplateEngine;

class AuthController {
    public function __construct(
        private UserService $userService,
        private TemplateEngine $view,
        private ValidatorService $validator,
        private ThreadService $thread
    )
    {
        
    }

    public function viewLogin(){
        echo $this->view->render('/login.php');
    }

    public function actionLogin(){
        $this->validator->validateLogin($_POST);
        $this->userService->login($_POST);

        $threads = $this->thread->getUsersThreads($_SESSION['user']);

        if(!empty($threads))
            $_SESSION['active_thread'] = $threads[0]['thread_id'];

        redirectTo('/');
    }

    public function actionRegister(){
        $this->validator->validateRegister($_POST);
        $this->userService->register($_POST);
        redirectTo('/login');
    }

    public function actionLogout(){
        $this->userService->logout();
        redirectTo('/login');
    }
}