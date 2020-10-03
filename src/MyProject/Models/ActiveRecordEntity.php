<?php

namespace MyProject\Models;

use MyProject\Exceptions\DbException;
use MyProject\Models\Users\User;
use MyProject\Services\Db;

/**
 * Class ActiveRecordEntity
 * @package MyProject\Models
 *
 */
abstract class ActiveRecordEntity
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Modifying undeclared properties received from DB
     * to public properties in noticed class in SQL query
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $camelCaseName = $this->underScoreToCamelCase($name);
        $this->$camelCaseName = $value;
    }

    /**
     * @return string
     * @param $name
     */
    private function underScoreToCamelCase($name): string
    {
        return lcfirst(str_replace('_', '', ucwords($name, '_')));
    }

    /**
     * @return string
     * @param $string
     */
    private function camelCaseToUnderscore($string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    /**
     * @return array
     * @throws DbException
     */
    public static function getAll(): array
    {
        $db = Db::getDb();
        $sql = 'SELECT * FROM `' . static::getTableName() . '`';
        return $db->queryWithGettingDAta($sql, [], static::class);
    }

    public static function getByLimit(int $offset, int $limit): array
    {
        $db = Db::getDb();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` LIMIT ' . $offset.','.$limit;
        return $db->queryWithGettingDAta($sql, [], static::class);
    }

    /**
     * @param int $id
     * @return static|null
     * @throws DbException
     */
    public static function getById(int $id): ?self
    {
        $db = Db::getDb();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` WHERE id = :id';
        $entity = $db->queryWithGettingDAta($sql, [':id' => $id], static::class);
        return $entity ? $entity[0] : null;
    }

    /**
     * Returns two arrays(params and values) in one.
     * Prepared data for filling DB up.
     *
     * @return array
     */
    public function preparePropertiesToDbFormat(): array
    {
        /*
         * Object properties' names format preparing
         * for using in SQL queries
         * */

        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();
        $preparedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if ($this->$propertyName === null) {
                continue;  // removing null-values from array
            }
            $preparedProperties[$this->CamelCaseToUnderscore($propertyName)] = $this->$propertyName;
        }

        /*
         * Dividing common array $mappedProperties = [param => value]
         * into separate ones:
         * $params = [param = :param]
         * $values = [:param => value]
         * */
        $params = [];
        $values = [];
        foreach ($preparedProperties as $column => $value) {
            $params[] = $column.'=:'.$column;
            $values[':'.$column] = $value;
        }
        return $preparedProperties = ['params' => $params, 'values' => $values];
    }

    /**
     * @throws DbException
     */
    public function save()
    {
        $preparedProperties = $this->preparePropertiesToDbFormat();
        if ($this->id === null) {
            $this->insert($preparedProperties);
        } else {
            $this->update($preparedProperties);
        }
    }

    /**
     * @param array $preparedProperties
     * @throws DbException
     */
    private function update(array $preparedProperties)
    {
        $sql = 'UPDATE `' . static::getTableName() . '` SET ' . implode(', ', $preparedProperties['params']) . ' WHERE id = ' . $this->id . ';';
        $db = Db::getDb();
        $db->queryWithoutGettingData($sql, $preparedProperties['values']);
    }

    /**
     * @param array $preparedProperties
     * @throws DbException
     */
    private function insert(array $preparedProperties)
    {
        $sql = 'INSERT INTO `' . static::getTableName() . '` SET ' . implode(', ', $preparedProperties['params']) . ';';
        $db = Db::getDb();
        $db->queryWithoutGettingData($sql, $preparedProperties['values']);
        $this->id = $db->getLastInsertId();
        $this->refresh();
    }

    /**
     * @throws DbException
     */
    public function delete(): void
    {
        $sql = 'DELETE FROM `'.static::getTableName().'` WHERE id = :id';
        $db = Db::getDb();
        $db->queryWithoutGettingData($sql, [':id' => $this->id]);
        $this->id = null;
    }

    /**
     * @param string $columnName
     * @param $value
     * @throws DbException
     */
    public static function deleteAllByColumn(string $columnName, $value)
    {
        $db = Db::getDb();
        $sql = 'DELETE FROM `'.static::getTableName().'` WHERE '.$columnName. ' = :value';
        $db->queryWithoutGettingData($sql, [':value' => $value]);
    }

    /**
     * @throws DbException
     */
    protected function refresh(): void
    {
        $objFromDb = static::getById($this->id);

        $properties = get_object_vars($objFromDb);

        foreach ($properties as $key=>$value) {
            $this->$key = $value;
        }
    }

    /**
     * @param string $columnName
     * @param $value
     * @return null|array
     * @throws DbException
     */
    public static function getOneByColumn(string $columnName, $value): ?User
    {
        $db = Db::getDb();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` WHERE ' . $columnName . ' = :value; LIMIT 1';
        $result = $db->queryWithGettingDAta($sql, [':value' => $value], static::class);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return mixed|null
     * @throws DbException
     */
    public static function getAllByColumn(string $columnName, $value): ?array
    {
        $db = Db::getDb();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` WHERE ' . $columnName . ' = :value ORDER BY created_at DESC; ';
        $result = $db->queryWithGettingDAta($sql, [':value' => $value], static::class);
        return !empty($result) ? $result : null;
    }

    abstract protected static function getTableName(): string;
}
