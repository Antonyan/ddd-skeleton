<?php

namespace Infrastructure\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Driver\Statement;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Infrastructure\Exceptions\InfrastructureException;

class MySQLClient
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var array
     */
    private $dbConfig;

    /**
     * MySQLClient constructor.
     * @param array $dbConfig
     */
    public function __construct(array $dbConfig)
    {
        $this->dbConfig = $dbConfig;
    }

    /**
     * @param string $query
     * @param array $params
     * @return array
     * @throws InfrastructureException
     */
    public function fetchAll(string $query, array $params) : array
    {
        return $this->executeSqlStatement($query, $params)->fetchAll();
    }

    /**
     * @param string $query
     * @param array $params
     * @return array
     * @throws InfrastructureException
     */
    public function fetch(string $query, array $params) : array
    {
        return $this->executeSqlStatement($query, $params)->fetch();
    }

    /**
     * @param $table
     * @param $parameters
     * @return int
     * @throws InfrastructureException
     */
    public function insert($table, $parameters) : int
    {
        try {
            $this->connection()->insert($table, $parameters);
            return $this->connection()->lastInsertId();
        } catch (DBALException $e) {
            throw new InfrastructureException($e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $parameters
     * @param $identifier
     * @throws InfrastructureException
     */
    public function update($table, $parameters, $identifier) : void
    {
        try {
            $this->connection()->update($table, $parameters, $identifier);
        } catch (DBALException $e) {
            throw new InfrastructureException($e->getMessage());
        }
    }

    /**
     * @param $table
     * @param $identifier
     * @throws InfrastructureException
     */
    public function delete($table, $identifier) : void
    {
        try {
            $this->connection()->delete($table, $identifier);
        } catch (DBALException $e) {
            throw new InfrastructureException('Can\'t delete from ' . $table . ' where ' . $identifier . '.');
        }
    }

    /**
     * @return Connection
     * @throws InfrastructureException
     */
    private function connection() : Connection
    {
        if ($this->connection){
            return $this->connection;
        }

        try {
            $this->connection = DriverManager::getConnection($this->dbConfig);
        } catch (DBALException $e) {
            throw new InfrastructureException('Can\'t connect to Database');
        }

        return $this->connection;
    }

    /**
     * @param string $query
     * @param array $params
     * @return Statement
     * @throws InfrastructureException
     */
    private function executeSqlStatement(string $query, array $params): Statement
    {
        try {
            $stmt = $this->connection()->prepare($query);
        } catch (DBALException $e) {
            throw new InfrastructureException('Can\'t prepare statement');
        }

        foreach ($params as $paramName => $paramValue) {
            $stmt->bindValue($paramName, $paramValue);
        }

        $stmt->execute();
        return $stmt;
    }

}