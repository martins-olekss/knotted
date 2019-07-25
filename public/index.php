<?php
session_start();

use Bramus\Router\Router;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

const __ROOT__ = __DIR__ . '/..';
require __ROOT__ . '/vendor/autoload.php';

$database = new Database();
$router = new Router();
$loader = new FilesystemLoader(__ROOT__ . '/template');
$twig = new Environment($loader, [
    'debug' => true,
    'cache' => __ROOT__ . '/template/cache'
]);
$twig->addExtension(new DebugExtension());

$router->setBasePath('/');
$router->get('/', function () use ($twig)  {
    $template = $twig->load('home.twig');
    echo $template->render();
});

$router->post('/submit', function () use ($database) {
    $database->save(
        $_POST['title'],
        $_POST['url'],
        $_POST['description']);

    header('location: /list');
});

$router->get('/new', function () use ($twig)  {
    $template = $twig->load('new.twig');
    echo $template->render();
});


$router->get('/list', function () use ($twig, $database)  {
    $template = $twig->load('list.twig');
    $data = $database->listLinks();
    echo $template->render(['list' => $data]);
});

$router->run();
