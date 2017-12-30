<?php

namespace App\Router;

use App\Request;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Router
 * @package App
 */
class Router
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var Request
     */
    private $request;

    /**
     * Router constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $file
     */
    public function loadYaml($file)
    {
        // On charge le fichier routing.yml
        $routes = Yaml::parseFile($file);
        foreach($routes as $name => $route){
            $this->addRoute(new Route($name, $route["path"], $route["parameters"], $route["controller"], $route["action"]));
        }
    }

    /**
     * @param Route $route
     * @throws \Exception
     */
    public function addRoute(Route $route)
    {
        // Si la route existe déjà (teste sur le nom) alors on soulève une erreur
        if(isset($this->routes[$route->getName()])) {
            throw new RouterException("Cette route existe déjà !");
        }
        $this->routes[$route->getName()] = $route;
    }

    /**
     * @return mixed
     * @throws RouterException
     */
    public function getRouteByRequest()
    {
        // Pour chaque route, on teste si elle correspond à la requête, si oui alors on renvoie cette route
        foreach($this->routes as $route) {
            if($route->match($this->request->getUri())) {
                return $route;
            }
        }
        // Sinon on soulève une erreur
        throw new RouterException("Cette route n'existe pas !");
    }

    /**
     * @param string $routeName
     * @return Route
     * @throws RouterException
     */
    public function getRoute($routeName)
    {
        // Si la route existe (teste sur le nom) alors on renvoie la route en question
        if(isset($this->routes[$routeName])) {
            return $this->routes[$routeName];
        }
        // Sinon on soulève une erreur
        throw new RouterException("Cette route n'existe pas !");
    }
}