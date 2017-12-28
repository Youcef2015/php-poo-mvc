<?php

namespace App\Router;

/**
 * Class Route
 * @package App
 */
class Route
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $args;

    /**
     * Route constructor.
     * @param string $name
     * @param string $path
     * @param array $parameters
     * @param string $controller
     * @param string $action
     */
    public function __construct($name, $path, array $parameters, $controller, $action)
    {
        $this->name = $name;
        $this->path = $path;
        $this->parameters = $parameters;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function call()
    {
        $controller = $this->controller;
        // On instancie dynamiquement le contrôleur
        $controller = new $controller();
        // call_user_func_array permet d'appeler une méthode (ou une fonction, cf la doc) d'une classe et de lui passer des arguments
        return call_user_func_array([$controller, $this->action], $this->args);
    }

    /**
     * @param string $requestUri
     * @return bool
     */
    public function match($requestUri)
    {
        // On génère un nouveau chemin en remplaçant les paramètres par des regexp
        $path = preg_replace_callback("/:(\w+)/", [$this, "parameterMatch"], $this->path);
        // On échappe chaque "/" pour que notre regexp puisse reconnaître le "/"
        $path = str_replace("/","\/", $path);
        // Si notre requpete actuelle ne correspond pas à la regexp alons on renvoie false
        if(!preg_match("/^$path$/i", $requestUri, $matches)){
            return false;
        }
        // Sinon on remplit notre tableau d'arguments avec les valeurs de chaque paramètre de notre route
        $this->args = array_slice($matches,1);
        return true;
    }

    /**
     * @param $match
     * @return string
     */
    private function parameterMatch($match)
    {
        // Si nous avons bien définie notre paramètre alors on renvoie la regexp associé
        if(isset($this->parameters[$match[1]])) {
            return sprintf("(%s)",$this->parameters[$match[1]]);
        }
        // Sinon on renvoie une regexp par défaut
        return '([^/]+)';
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}