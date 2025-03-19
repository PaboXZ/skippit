<?php

declare(strict_types=1);

namespace App\Middlewares;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidatorException;

class ValidatorExceptionMiddleware implements MiddlewareInterface {

    public function __construct()
    {
        
    }

    public function process(callable $next){

        try{
            $next();
        }
        catch(ValidatorException $e){
            $_SESSION['errors'] = $e->errors;

            if(isset($_POST['password']))
                unset($_POST['password']);

            if(isset($_POST['passwordConfirm']))
                unset($_POST['passwordConfirm']);

            $_SESSION['oldFormData'] = $_POST;

            $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH);

            redirectTo($referer);
        }
    }
}