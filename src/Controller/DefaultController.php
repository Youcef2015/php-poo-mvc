<?php

namespace Controller;

/**
 * Class DefaultController
 * @package Controller
 */
class DefaultController
{

    public function indexAction()
    {
        echo "Hallo world !";
    }

    public function fooAction($bar)
    {
        echo $bar;
    }
}