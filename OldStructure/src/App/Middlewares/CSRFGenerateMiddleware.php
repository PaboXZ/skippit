<?php

declare(strict_types=1);

namespace App\Middlewares;

use Framework\Contracts\MiddlewareInterface;

class CSRFGenerateMiddleware implements MiddlewareInterface{
    public function __construct()
    {
        
    }
    
    public function process(callable $next){
        
        if(!isset($_SESSION['token'])){
            $token = random_bytes(20);
            $token = bin2hex($token);

            $_SESSION['token'] = $token;
        }

        $next();
    }
}