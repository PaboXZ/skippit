<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UserService;
use Framework\TemplateEngine;

class AuthController {
    public function __construct(
        private UserService $userService,
        private TemplateEngine $view
    )
    {
        
    }

    public function viewLogin(){
        echo "Login page";
    }
}