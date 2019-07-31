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
$router->get('/', function () use ($twig, $database) {
    $template = $twig->load('list.twig');
    $data = $database->listLinks();
    echo $template->render(['list' => $data]);
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
    exit();
});

$router->before('GET|POST', '/new', function() {
    if (!isset($_SESSION['id'])) {
        header('location: /login');
        exit();
    }
});

$router->get('/new', function () use ($twig) {
    $template = $twig->load('new.twig');
    echo $template->render();
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
    exit();
});

$router->get('/logout', function () use ($twig) {
    session_unset();
    header('location: /');
    exit();
});

$router->before('GET|POST', '/admin/.*', function() use ($twig) {
    if (!App::verifyRegisterKey()) {
        header('location: /');
        exit();
    }
});

$router->get('/admin/register', function () use ($twig) {
    $template = $twig->load('register.twig');
    echo $template->render();
});

$router->post('/admin/registerSubmit', function () use ($database) {
    if (!App::verifyRegisterKey()) {
        header('location: /');
        exit();
    }
    $user = new User($database);
    $accessGranted = $user->registerUser($_POST);
    if ($accessGranted) {
        App::log('Registration done using ' . $_COOKIE['key']);
        header('location: /');
    } else {
        header('location: /');
    }
    exit();
});

$router->get('/deleteSubmit/{linkId}', function ($id) use ($database) {
    $database->deleteLink($id);
});

$router->run();
