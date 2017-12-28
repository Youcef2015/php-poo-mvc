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
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * Controller constructor.
     * @param Request $request
     * @param Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
        // On instancie le loader de twig en lui précisant dans quel dossier se trouvera nos vues
        $loader = new \Twig_Loader_Filesystem([__DIR__.'/../src/View']);
        // On instancie l'environnement de twig, qui nous permettra de générer nos vues
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => false,
        ));
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

    /**
     * @param string $filename
     * @param array $data
     */
    protected function render($filename, $data = [])
    {
        // On charge notre vue
        $view = $this->twig->load($filename);
        // On affiche notre vue en lui passant nos données pour que la vue puisse les exploiter
        echo $view->render($data);
    }

}