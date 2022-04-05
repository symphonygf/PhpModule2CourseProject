<?php

use App\Container\DIContainer;
use App\Drivers\Connection;
use App\Drivers\PdoConnectionDriver;
use App\Http\Auth\AuthenticationInterface;
use App\Http\Auth\IdentificationInterface;
use App\Http\Auth\JsonBodyUserIdentification;
use App\Http\Auth\PasswordAuthentication;
use App\Repositories\ArticleRepository;
use App\Repositories\ArticleRepositoryInterface;
use App\Repositories\CommentRepository;
use App\Repositories\CommentRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use Dotenv\Dotenv;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

require_once __DIR__ . '/vendor/autoload.php';
Dotenv::createImmutable(__DIR__)->safeLoad();

$container = DIContainer::getInstance();

$container->bind(
    UserRepositoryInterface::class,
    UserRepository::class
);

$container->bind(
    ArticleRepositoryInterface::class,
    ArticleRepository::class
);

$container->bind(
    CommentRepositoryInterface::class,
    CommentRepository::class
);

$container->bind(
    CommentRepositoryInterface::class,
    CommentRepository::class
);

$container->bind(
    Connection::class,
    PdoConnectionDriver::getInstance($_SERVER['DSN_DATABASE'])
);

$container->bind(
    IdentificationInterface::class,
    JsonBodyUserIdentification::class
);

$container->bind(
    AuthenticationInterface::class,
    PasswordAuthentication::class
);


$logger = new Logger('geekbrains');

$isNeedLogToFile = (bool)$_SERVER['LOG_TO_FILES'];
$isNeedLogToConsole = (bool)$_SERVER['LOG_TO_CONSOLE'];

if($isNeedLogToFile)
{
    $logger
        ->pushHandler(new StreamHandler(
            __DIR__ . '/.logs/geekbrains.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/.logs/geekbrains.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}

if($isNeedLogToConsole)
{
    $logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(
    LoggerInterface::class,
    (new Logger('geekbrains'))
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/.logs/geekbrains.log'
            )
        )
        ->pushHandler(
            new StreamHandler(
                __DIR__ . '/.logs/geekbrains.error.log',
                level: Logger::ERROR,
                bubble: false,
            )
        )
    ->pushHandler(new StreamHandler("php://stdout"))
);


return $container;
