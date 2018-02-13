<?php
/**
 * Created by PhpStorm.
 * User: tboileau-desktop
 * Date: 13/02/18
 * Time: 13:17
 */

namespace Manager;


use App\ORM\Manager;
use Model\Foo;

class FooManager extends Manager
{
    public function getPaginatedFoos($page)
    {
        $start = ($page-1)*10;
        $statement = $this->pdo->prepare(sprintf("SELECT * FROM foo ORDER BY added_at DESC LIMIT %d,10", $start));
        $statement->execute();
        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);
        array_walk($results, function(&$foo) {
            $foo = (new Foo())->hydrate($foo);
        });
        return $results;
    }
}