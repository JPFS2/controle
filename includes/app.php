<?php

require __DIR__.'/../vendor/autoload.php';

use App\Utils\View;
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use App\Http\Middleware\Queue as MiddlewareQueue;

//CARREGA AS VARIAVEIS AMBIENTE
Environment::load(__DIR__.'/../');

//DEFINE AS CONFIGURAÇ~EOS DO BANCO
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

define('URL',getenv('URL'));

View::init([
    'URL' => URL
]);

MiddlewareQueue::setMap([
    'maintenance' => \App\Http\Middleware\Mainteance::class,
    'required-admin-logout' => \App\Http\Middleware\RequireAdminLogout::class,
    'required-admin-login' => \App\Http\Middleware\RequireAdminLogin::class,
    'required-pages-login' => \App\Http\Middleware\RequirePagesLogin::class,
    'required-pages-logout' => \App\Http\Middleware\RequirePagesLogout::class
]);

MiddlewareQueue::setDefault([
    'maintenance'
]);
