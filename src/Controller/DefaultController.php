<?php

namespace Controller;

use App\Controller;

/**
 * Class DefaultController
 * @package Controller
 */
class DefaultController extends Controller
{

    public function indexAction()
    {
        $this->render("index.html.twig");
    }

    public function fooAction($bar)
    {
        $this->render("foo.html.twig", [
            "bar" => $bar
        ]);
    }

    public function redirectionAction($bar)
    {
        $this->redirect("foo", ["bar" => $bar]);
    }
}