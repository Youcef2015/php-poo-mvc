<?php

// On inclus le fichier autoload.php pour pouvoir bénéficier de l'autoloader généré par composer.
require __DIR__."/../vendor/autoload.php";

use App\Request;
use App\Router\Route;
use App\Router\Router;
use Controller\DefaultController;

$request = Request::createFromGlobals();

// Si nous sommes en environnement de développment, alors nous affichons les erreurs.
if($request->getEnv("ENV") == "dev") {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

$router = new Router($request);

$router->addRoute(new Route("index", "/", [], DefaultController::class, "indexAction"));

$router->addRoute(new Route("foo", "/foo/:bar", ["bar" => "[\w]+"], DefaultController::class, "fooAction"));

try{
    $route = $router->getRouteByRequest();
    $route->call();
}catch (\Exception $e) {
    echo $e->getMessage();
}