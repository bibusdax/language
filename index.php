<?php
require_once __DIR__ . '/vendor/autoload.php';

session_start();

$container = new \Slim\Container();
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/views', [
        'cache' => false,
    ]);

    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$app = new \Slim\App($container);

$authMiddleware = function ($request, $response, $next) {
    if (!isset($_SESSION['user'])) {
        return $response->withRedirect('/login');
    }

    return $next($request, $response);
};

$loginMiddleware = function ($request, $response, $next) {
    if (isset($_SESSION['user'])) {
        return $response->withRedirect('/dashboard');
    }

    return $next($request, $response);
};

$app->get('/', function ($request, $response, $args) {
    return $this->view->render($response, 'index.twig');
})->setName('index');

$app->group('', function () use ($app) {
    $app->get('/login', function ($request, $response, $args) {
        return $this->view->render($response, 'login.twig');
    })->add($loginMiddleware)->setName('login');

    $app->post('/login', 'AuthController:login')->add($loginMiddleware);

    $app->get('/logout', 'AuthController:logout');
});

$app->group('', function () use ($app, $authMiddleware) {
    $app->get('/dashboard', 'LanguageController:dashboard')->add($authMiddleware)->setName('dashboard');

    $app->get('/train', 'LanguageController:train')->add($authMiddleware)->setName('train');

    $app->get('/edit/{id}', 'LanguageController:edit')->add($authMiddleware)->setName('edit');
    $app->post('/edit/{id}', 'LanguageController:update')->add($authMiddleware);

    $app->get('/add', 'LanguageController:add')->add($authMiddleware)->setName('add');
    $app->post('/add', 'LanguageController:store')->add($authMiddleware);

    $app->get('/remove/{id}', 'LanguageController:remove')->add($authMiddleware)->setName('remove');

})->add($authMiddleware);

$app->run();
