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
        echo "Hallo world !";
    }

    public function fooAction($bar)
    {
        echo $bar;
    }

    public function redirectionAction($bar)
    {
        $this->redirect("foo", ["bar" => $bar]);
    }
}