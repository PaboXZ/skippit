<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{AuthController, PanelController, PasswordsController};

use App\Middlewares\{GuestOnlyMiddleware, UserOnlyMiddleware};

function registerRoutes(App $app){
    $app
        ->get('/login', [AuthController::class, 'viewLogin'])->addRouteMiddleware(GuestOnlyMiddleware::class)
        ->get('/', [PanelController::class, 'view'], [UserOnlyMiddleware::class]);
}