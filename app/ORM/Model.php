<?php

namespace App\ORM;

/**
 * Class Model
 * @package App\ORM
 */
abstract class Model
{
    /**
     * @var array
     */
    public $orignalData = [];

    /**
     * @return array
     */
    public abstract static function metadata();

    /**
     * @param array $result
     * @return Model
     * @throws ORMException
     */
    public function hydrate($result)
    {
        if(empty($result)) {
            throw new ORMException("Aucun résultat n'a été trouvé !");
        }
        $this->originalData = $result;
        foreach($result as $column => $value) {
            $this->hydrateProperty($column, $value);
        }
        return $this;
    }

    /**
     * @param string $column
     * @param mixed $value
     */
    private function hydrateProperty($column, $value)
    {
        switch($this::metadata()["columns"][$column]["type"]) {
            case "integer":
                $this->{sprintf("set%s", ucfirst($this::metadata()["columns"][$column]["property"]))}((int) $value);
                break;
            case "string":
                $this->{sprintf("set%s", ucfirst($this::metadata()["columns"][$column]["property"]))}($value);
                break;
            case "datetime":
                $datetime = \DateTime::createFromFormat("Y-m-d H:i:s", $value);
                $this->{sprintf("set%s", ucfirst($this::metadata()["columns"][$column]["property"]))}($datetime);
                break;
        }
    }

    /**
     * @param string $column
     * @return mixed
     */
    public function getSQLValueByColumn($column)
    {
        $value = $this->{sprintf("get%s", ucfirst($this::metadata()["columns"][$column]["property"]))}();
        if($value instanceof \DateTime){
            return $value->format("Y-m-d H:i:s");
        }
        return $value;
    }

    /**
     * @param mixed $value
     */
    public function setPrimaryKey($value)
    {
        $this->hydrateProperty($this::metadata()["primaryKey"], $value);
    }

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        $primaryKeyColumn = $this::metadata()["primaryKey"];
        $property = $this::metadata()["columns"][$primaryKeyColumn]["property"];
        return $this->{sprintf("get%s", ucfirst($property))}();
    }
}