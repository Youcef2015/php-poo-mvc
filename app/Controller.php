<?php

namespace App;

use App\Router\Router;

/**
 * Class Controller
 * @package App
 */
class Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var Router
     */
    private $router;

    /**
     * Controller constructor.
     * @param Request $request
     * @param Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
    }

    /**
     * @param $routeName
     * @param array $args
     */
    protected function redirect($routeName, $args = [])
    {
        // On récupère la route par son nom
        $route = $this->router->getRoute($routeName);
        // On effectue une redirection en générant l'url, $args contient les valeurs de chaque paramètre de la route
        header(sprintf("location: %s", $route->generateUrl($args)));
    }

}