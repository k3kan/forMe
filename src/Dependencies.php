<?php declare(strict_types = 1);

use Auryn\Injector;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Pdo\Database;

$injector = new Injector();

$injector->share('Symfony\Component\HttpFoundation\Request');
$injector->define('Symfony\Component\HttpFoundation\Request', [
    ':query' => $_GET,
    ':request' => $_POST,
    ':attributes' => [],
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER
]);

$injector->share('App\Page\FilePageReader');
$injector->define('App\Page\FilePageReader', [
    ':pageFolder' => __DIR__ . '/../pages',
]);
$injector->share('Symfony\Component\HttpFoundation\Response');
$injector->define('Symfony\Component\HttpFoundation\Response', [
    ':content' => 'Content',
    ':status' => 200,
    ':headers' => ['content-type' => 'text/html'],
]);

$injector->delegate('Twig\Environment', function () use ($injector)
{
    $loader = new FilesystemLoader(__DIR__ . '/Templates');
    $twig = new Environment($loader);
    return $twig;
});

$injector->alias('App\Render\Renderer', 'App\Render\FrontendTwigRenderer');

$injector->alias('App\Menu\MenuReader', 'App\Menu\ArrayMenuReader');
$injector->share('App\Menu\ArrayMenuReader');

$injector->define('App\Api\WeatherMap', [
    ':token' => $_ENV['WEATHER_TOKEN']
]);

$injector->define('App\Controllers\Mailing', [
    ':token' => $_ENV['TELEGRAM_TOKEN']
]);

$injector->define('App\Models\Model', [
    ':db' => Database::getInstance(),
]);

return $injector;