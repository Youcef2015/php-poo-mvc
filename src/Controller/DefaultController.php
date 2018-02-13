<?php

namespace Controller;

use App\Controller;
use Model\Foo;

/**
 * Class DefaultController
 * @package Controller
 */
class DefaultController extends Controller
{

    /**
     * @return \App\Response\Response
     */
    public function indexAction($page = 1)
    {
        $foos = $this->getDatabase()->getManager(Foo::class)->getPaginatedFoos($page);
        return $this->render("index.html.twig", [
            "foos" => $foos
        ]);
    }

    /**
     * @param integer $id
     * @return \App\Response\Response
     */
    public function showAction($id)
    {
        $foo = $this->getDatabase()->getManager(Foo::class)->find($id);
//         $foo = $this->getDatabase()->getManager(Foo::class)->findOneBy(["id" => $bar]);
//         $foo = $this->getDatabase()->getManager(Foo::class)->findOneById($bar);
        return $this->render("foo.html.twig", [
            "foo" => $foo
        ]);
    }

    /**
     * @return \App\Response\RedirectResponse
     */
    public function addAction()
    {
        $manager = $this->getDatabase()->getManager(Foo::class);
        $foo = new Foo();
        $foo->setName("Bar");
        $foo->setAddedAt(new \DateTime());
        $manager->persist($foo);
        return $this->redirect("show", ["id" => $foo->getId()]);
    }

    /**
     * @param $id
     * @return \App\Response\RedirectResponse
     */
    public function updateAction($id)
    {
        $manager = $this->getDatabase()->getManager(Foo::class);
        $foo = $manager->find($id);
        $foo->setName("Edit bar");
        $manager->persist($foo);
        return $this->redirect("show", ["id" => $foo->getId()]);
    }

    /**
     * @param $id
     * @return \App\Response\RedirectResponse
     */
    public function deleteAction($id)
    {
        $manager = $this->getDatabase()->getManager(Foo::class);
        $foo = $manager->find($id);
        $manager->remove($foo);
        return $this->redirect("index");
    }
}