<?php

namespace App\Router;

use App\Request;

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
}