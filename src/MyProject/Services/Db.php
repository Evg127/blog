<?php

namespace MyProject\Services;

use MyProject\Exceptions\DbException;
use PDO;
use PDOException;

/**
 * Class Db
 * @package MyProject\Services
 *
 */
class Db
{
    /**
     * @var PDO
     */
    private $pdo;

    /**
     * @var Db
     */
    private static $db;

    /**
     * @return static
     */
    public static function getDb(): self
    {
        if (self::$db === null) {
            self::$db = new self();
        }
        return self::$db;
    }

    /**
     * Db constructor.
     * @throws DbException
     */
    private function __construct()
    {
        $dbOptions = (require __DIR__ . '/../../settings.php')['db'];
        try {
            $this->pdo = new PDO(
                'mysql:host=' . $dbOptions['host'] . '; dbname=' . $dbOptions['dbname'] . '; charset=' . $dbOptions['charset'],
                $dbOptions['user'],
                $dbOptions['password']
            );
            // Set attribute for auto throwing PDO exceptions;
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            throw new DbException('DB connecting error<br>'.$exception->getMessage());
        }
    }

    /**
     * @param string $sql
     * @param array $param
     * @param string $className
     * @return array
     * @throws DbException
     */
    public function queryWithGettingDAta(string $sql, array $param = [], string $className = 'stdClass'): array
    {
        try{
            $sth = $this->pdo->prepare($sql);
            $sth->execute($param);
        } catch (PDOException $exception) {
            throw new DbException('Query error:<br>'.$exception->getMessage(). '<br><br>Query string:<br>'. $sql);
        }
        return $sth->fetchAll(PDO::FETCH_CLASS, $className);
    }

    /**
     * @param string $sql
     * @param array $param
     * @throws DbException
     */
    public function queryWithoutGettingData(string $sql, array $param = []): void
    {
        try{
            $sth = $this->pdo->prepare($sql);
            $sth->execute($param);
        } catch (PDOException $exception) {
            throw new DbException('Query error:<br>'.$exception->getMessage(). '<br><br>Query string:<br>'. $sql);
        }
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}