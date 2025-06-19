<?php

declare(strict_types=1);

namespace App\Middlewares;

use Framework\Contracts\MiddlewareInterface;

class UserOnlyMiddleware implements MiddlewareInterface{

    public function __construct()
    {
        
    }

    public function process(callable $next)
    {
        if(!isset($_SESSION['user']))
            redirectTo('/login');
        $next();
    }
}