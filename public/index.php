<?php
session_start();

use Bramus\Router\Router;

const __ROOT__ = __DIR__ . '/..';
require __ROOT__ . '/vendor/autoload.php';

$router = new Router();

$router->setBasePath('/');
$router->get('/', function () {
    echo 'home';
});

$router->post('/submit', function () {
    echo $_POST['url'];
});

$router->get('/about', function () {
    echo 'About this app';
});

$router->get('/new', function () {
    echo <<<HTML
<form method='post' action='/submit'>
<input type='text' name='url' />
<input type='submit' />
</form>
HTML;
});

$router->run();
