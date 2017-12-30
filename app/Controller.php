<?php

namespace App;

use App\ORM\Database;
use App\Response\JsonResponse;
use App\Response\RedirectResponse;
use App\Response\Response;
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
     * @var Database
     */
    private $database;

    /**
     * Controller constructor.
     * @param Request $request
     * @param Router $router
     */
    public function __construct(Request $request, Router $router)
    {
        $this->request = $request;
        $this->router = $router;
        $this->database = Database::getInstance($request);
        // On instancie le loader de twig en lui précisant dans quel dossier se trouvera nos vues
        $loader = new \Twig_Loader_Filesystem([__DIR__.'/../src/View']);
        // On instancie l'environnement de twig, qui nous permettra de générer nos vues
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => false,
        ));
    }

    /**
     * @param string $routeName
     * @param array $args
     * @return RedirectResponse
     */
    protected function redirect($routeName, $args = [])
    {
        // On récupère la route par son nom
        $route = $this->router->getRoute($routeName);
        // On génère l'url, $args contient les valeurs de chaque paramètre de la route
        $url = $route->generateUrl($args);
        // On renvoie un objet RedirectResponse
        return new RedirectResponse($url);
    }

    /**
     * @param string $filename
     * @param array $data
     * @return Response
     */
    protected function render($filename, $data = [])
    {
        // On charge notre vue
        $view = $this->twig->load($filename);
        // On récupère le contenu de la vue en lui passant nos données pour que la vue puisse les exploiter
        $content = $view->render($data);
        // On renvoie un objet Response
        return new Response($content);
    }

    /**
     * @param mixed $data
     * @return JsonResponse
     */
    protected function json($data)
    {
        // On renvoie un objet JsonResponse
        return new JsonResponse($data);
    }

    /**
     * @return Database
     */
    protected function getDatabase()
    {
        return $this->database;
    }

}