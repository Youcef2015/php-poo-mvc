<?php

namespace Controller;

use App\Controller;

/**
 * Class DefaultController
 * @package Controller
 */
class DefaultController extends Controller
{

    /**
     * @return \App\Response\Response
     */
    public function indexAction()
    {
        return $this->render("index.html.twig");
    }

    /**
     * @param string $bar
     * @return \App\Response\Response
     */
    public function fooAction($bar)
    {
        return $this->render("foo.html.twig", [
            "bar" => $bar
        ]);
    }

    /**
     * @param string $bar
     * @return \App\Response\RedirectResponse
     */
    public function redirectionAction($bar)
    {
        return $this->redirect("foo", ["bar" => $bar]);
    }
}