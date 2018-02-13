<?php

namespace App\ORM;

use App\Request;

/**
 * Class Database
 * @package App\ORM
 */
class Database
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var array
     */
    private $managers = [];

    /**
     * @var Database
     */
    private static $databaseInstance;

    public static function getInstance(Request $request)
    {
        if(self::$databaseInstance === null) {
            self::$databaseInstance = new Database(
                $request->getEnv("DB_HOST"),
                $request->getEnv("DB_NAME"),
                $request->getEnv("DB_USER"),
                $request->getEnv("DB_PASSWORD")
            );
        }
        return self::$databaseInstance;
    }

    /**
     * Database constructor.
     * @param string $host
     * @param string $dbName
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $dbName, $user, $password)
    {
        $this->pdo = new \PDO("mysql:dbname=".$dbName.";host=".$host, $user, $password);
    }

    /**
     * @return \PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    public function getManager($model)
    {
        $managerClass = $model::getManager();
        $this->managers[$model] = $this->managers[$model] ?? new $managerClass($this->pdo, $model);
        return $this->managers[$model];
    }
}