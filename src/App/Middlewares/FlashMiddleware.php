<?php

declare(strict_types=1);

namespace App\Middlewares;

use Framework\TemplateEngine;
use Framework\Contracts\MiddlewareInterface;

class FlashMiddleware implements MiddlewareInterface{

    public function __construct(private TemplateEngine $view){

    }

    public function process(callable $next){
        if(isset($_SESSION['errors'])){
            $this->view->addGlobal('errors', $_SESSION['errors']);
            unset($_SESSION['errors']);
        }
        if(isset($_SESSION['oldFormData'])){
            $this->view->addGlobal('oldFormData', $_SESSION['oldFormData']);
            unset($_SESSION['oldFormData']);
        }
        $next();
    }
}