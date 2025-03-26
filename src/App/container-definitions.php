<?php

declare(strict_types=1);

use Framework\{TemplateEngine, Container, Validator};
use App\Config\Paths;
use App\Services\{ThreadService, UserService, ValidatorService};
use Framework\Database;

return [
    TemplateEngine::class => fn () => new TemplateEngine(Paths::VIEW),
    Database::class => fn () => new Database(
        $_ENV['DB_DRIVER'],
        ['host' => $_ENV['DB_HOST'], 'port' => $_ENV['DB_PORT'], 'dbname' => $_ENV['DB_NAME']],
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD']
    ),
    Validator::class => fn () => new Validator,
    UserService::class => function (Container $container) {
        $database = $container->get(Database::class);
        return new UserService($database);
    },
    ValidatorService::class => function (Container $container) {
        $validator = $container->get(Validator::class);
        return new ValidatorService($validator);
    },
    ThreadService::class => function (Container $container) {
        $database = $container->get(Database::class);
        return new ThreadService($database);
    }
];