<?php

declare(strict_types=1);

namespace App\Middlewares;

use Framework\Contracts\MiddlewareInterface;

class CSRFGuardMiddleware implements MiddlewareInterface{
    public function __construct()
    {
        
    }

    public function process(callable $next){

        $applicableMethods = ['DELETE', 'POST', 'PATCH'];

        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        if(in_array($method, $applicableMethods)){
            if($_POST['_CSRF'] !== $_SESSION['token'])
                redirectTo('/');
        }

        $next();
    }
}