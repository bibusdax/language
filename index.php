<?php
require_once 'vendor/autoload.php';

use LanguageLearning\Controller\LanguageController;
use Slim\Http\Request;
use Slim\Http\Response;

$config = ['settings' => [
    'displayErrorDetails' => true,
]];

$app = new Slim\App($config);

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('views', [
        'cache' => false
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$container['HomeController'] = function ($container) {
    return new \LanguageLearning\Controller\HomeController($container);
};

$container['AuthController'] = function ($container) {
    return new \LanguageLearning\Controller\AuthController($container);
};

$container['LanguageController'] = function ($container) {
    return new LanguageController($container);
};

$app->get('/', 'HomeController:index')->setName('home');
$app->get('/login', 'AuthController:getLogin')->setName('auth.login');
$app->post('/login', 'AuthController:postLogin');
$app->get('/logout', 'AuthController:getLogout')->setName('auth.logout');
$app->get('/dashboard', 'LanguageController:index')->add(new \LanguageLearning\Middleware\AuthMiddleware($container))->setName('language.dashboard');
$app->get('/train/{language}', 'LanguageController:getTrain')->add(new \LanguageLearning\Middleware\AuthMiddleware($container))->setName('language.train');
$app->post('/train', 'LanguageController:postTrain')->add(new \LanguageLearning\Middleware\AuthMiddleware($container))->setName('language.train.post');
$app->get('/edit/{language}', 'LanguageController:getEdit')->add(new \LanguageLearning\Middleware\AuthMiddleware($container))->setName('language.edit');
$app->post('/edit/{language}', 'LanguageController:postEdit')->add(new \LanguageLearning\Middleware\AuthMiddleware($container))->setName('language.edit.post');

$app->run();
