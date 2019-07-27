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
$twig->addGlobal("session", $_SESSION);

$router->setBasePath('/');
$router->get('/', function () use ($twig) {
    $template = $twig->load('home.twig');
    echo $template->render();
});

$router->post('/submit', function () use ($database) {
    $database->save(
        $_POST['title'],
        $_POST['url'],
        $_POST['description']);

    if (isset($_POST['another'])) {
        header('location: /new');
    } else {
        header('location: /list');
    }
});

$router->get('/new', function () use ($twig) {
    $template = $twig->load('new.twig');
    echo $template->render();
});

$router->get('/list', function () use ($twig, $database) {
    $template = $twig->load('list.twig');
    $data = $database->listLinks();
    echo $template->render(['list' => $data]);
});

$router->get('/login', function () use ($twig) {
    $template = $twig->load('login.twig');
    echo $template->render();
});

$router->post('/loginSubmit', function () use ($database) {
    $user = new User($database);
    $accessGranted = $user->loginUser($_POST);
    if ($accessGranted) {
        header('location: /new');
    } else {
        header('location: /list');
    }
});

$router->get('/logout', function () use ($twig) {
    session_unset();
    header('location: /');
});

$router->get('/register', function () use ($twig) {
    $template = $twig->load('register.twig');
    echo $template->render();
});

$router->post('/registerSubmit', function () use ($database) {
    App::log('submit register');
    $user = new User($database);
    $accessGranted = $user->registerUser($_POST);

    if ($accessGranted) {
        header('location: /');
    } else {
        header('location: /');
    }
});

$router->run();
