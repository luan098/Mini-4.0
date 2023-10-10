<?php

use Mini\core\Application;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

define('start_time_D46', microtime(true));
date_default_timezone_set('America/Sao_Paulo');

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
header('Content-type: text/html; charset=UTF-8');

// set a constant that holds the project's folder path, like "/var/www/".
// DIRECTORY_SEPARATOR adds a slash to the end of the path
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// set a constant that holds the project's "application" folder, like "/var/www/application".
define('APP', ROOT . 'src' . DIRECTORY_SEPARATOR);

// This is the auto-loader for Composer-dependencies (to load tools into your project).
require_once ROOT . 'vendor/autoload.php';

// load application config (error reporting etc.)
require_once ROOT . 'config.php';

$handler = new NativeFileSessionHandler(ROOT . 'var/sessions');
$storage = new NativeSessionStorage([], $handler);
$session = new Session($storage);
$session->start();

if (empty($_GET['pg'])) {
    $url = $_GET['url'] ?? '';
    if (strpos($url, '/') === 0) $url = substr($url, 1);
    $pgs = explode('/', $url);
    foreach ($pgs as $key => $pg) {
        if ($key > 0 && !$pg) continue;
        $pg = explode("?", $pg)[0];
        $_GET['pg' .  ($key > 0 ? $key + 1 : '')] = $pg ? $pg : 'home';
    }
}

new Application();
