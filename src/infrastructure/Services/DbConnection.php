<?php

namespace Infrastructure\Services;

use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Exceptions\InternalException;
use PDO;
use PDOException;
use PDOStatement;

class DbConnection
{
    /**
     * @var PDO
     */
    private $db;

    /**
     * @var array
     */
    private $config;

    /**
     * DbConnection constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param $query
     * @param array $bindingParams
     * @return PDOStatement
     * @throws InfrastructureException
     */
    public function execute($query, array $bindingParams) : PDOStatement
    {
        if ($this->db === null) {

            $dsn = 'mysql:host=' . $this->config['host'] . ';dbname=' . $this->config['dbname'];

            $options = [
                \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            ];
            try {
                $this->db =  new PDO($dsn, $this->config['user'], $this->config['password'], $options);
            } catch (PDOException $exception) {
                throw new InfrastructureException($exception->getMessage() . ': ' . $dsn);
            }
        }

        try {
            $statement = $this->db->prepare($query);
            $statement->execute($bindingParams);

            return $statement;
        } catch (\PDOException $exception) {
            throw new InfrastructureException('DbException : '.$exception->getMessage());
        }
    }

    /**
     * @param null $seqName
     * @return string
     */
    public function lastInsertId($seqName = null) : string 
    {
        return $this->db->lastInsertId($seqName);
    }

    /**
     * @return bool
     * @throws \PDOException
     */
    public function beginTransaction() : bool
    {
        return $this->db->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit() : bool
    {
        return $this->db->commit();
    }

    /**
     * @return bool
     */
    public function rollBack() : bool
    {
        return $this->db->rollBack();
    }
}
