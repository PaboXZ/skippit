<?php

declare(strict_types=1);

namespace App\Config;

use App\Middlewares\{CSRFGenerateMiddleware, CSRFGuardMiddleware, FlashMiddleware, SessionMiddleware, ValidatorExceptionMiddleware};
use Framework\App;

function registerMiddleware(App $app){
    $app->addMiddleware(ValidatorExceptionMiddleware::class);
    $app->addMiddleware(FlashMiddleware::class);
    $app->addMiddleware(CSRFGenerateMiddleware::class);
    $app->addMiddleware(CSRFGuardMiddleware::class);
    $app->addMiddleware(SessionMiddleware::class);
}