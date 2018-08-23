<?php

namespace Infrastructure\Mappers;

use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Exceptions\QueryBuilderEmptyInQueryException;
use Infrastructure\Models\ArraySerializable;
use Infrastructure\Models\PaginationCollection;
use Infrastructure\Models\SearchCriteria\EqualCriteria;
use Infrastructure\Models\SearchCriteria\SearchCriteria;
use Infrastructure\Models\SearchCriteria\SearchCriteriaConstructor;
use Infrastructure\Models\SearchCriteria\SearchCriteriaQueryString;
use Infrastructure\Services\BaseFactory;
use Infrastructure\Services\DbConnection;
use Infrastructure\Services\QueryBuilder;

abstract class DbMapper extends BaseMapper
{
    /**
     * Signs for where conditions.
     */
    const EQUAL_SIGN = '=';
    const GREATER_SIGN = '>';
    const LESS_SIGN = '<';
    const GREATER_OR_EQUAL_SIGN = '>=';
    const LESS_OR_EQUAL_SIGN = '<=';
    const IN_SIGN = 'in';
    const LIKE_SIGN = 'like';

    const SELECT_LIMIT_ALL = 'selectLimitAll';

    const TABLE = 'table';
    const COLUMNS = 'columns';
    const CREATE_CONDITION = 'create';
    const UPDATE_CONDITION = 'update';

    /**
     * @var DbConnection
     */
    private $db;

    /**
     * @var BaseFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $config;

    /**
     * DbMapper constructor.
     * @param DbConnection $db
     * @param BaseFactory $factory
     * @param array $config
     */
    public function __construct(DbConnection $db, BaseFactory $factory, array $config)
    {
        $this->db = $db;
        $this->factory = $factory;
        $this->config = $config;
    }

    /**
     * @param SearchCriteria $filter
     * @return PaginationCollection
     * @throws InfrastructureException
     */
    public function load(SearchCriteria $filter) : PaginationCollection
    {
        /** @var SearchCriteriaQueryString $filter */
        $queryBuilder = new QueryBuilder($this->getFromConfig(self::COLUMNS));
        try {
            $whereQueryPart = $queryBuilder->generateWhere($filter);
        } catch (QueryBuilderEmptyInQueryException $exception) {
            return new PaginationCollection(0, $filter->getLimit(), $filter->getOffset());
        }
        $query =
            $this->getSelectQuery().' '.
            $whereQueryPart->getQuery().' '.
            $queryBuilder->generateOrderBy($filter).' '.
            $queryBuilder->generateGroupBy($filter).' '.
            $queryBuilder->generateLimit($filter);

        $collection = $this->buildPaginationCollection(
            $this->db->execute($query, $whereQueryPart->getBindingValues())->fetchAll(),
            $this->getLoadTotalCount(),
            $filter->limit(),
            $filter->offset()
        );

        return $collection;
    }

    /**
     * @param array $objectData
     * @return ArraySerializable|mixed
     * @throws InfrastructureException
     */
    public function create(array $objectData)
    {
        return $this->createObject($objectData);
    }

    /**
     * @param array $objectData
     * @return ArraySerializable|mixed
     * @throws InfrastructureException
     */
    public function update(array $objectData)
    {
        return $this->updateObject($objectData);
    }

    /**
     * @param array $columnsMap
     * @return array
     */
    protected function getAsColumns(array $columnsMap) : array
    {
        $asColumns = [];
        foreach ($columnsMap as $modelField => $dbColumn) {
            $asColumns[] = $modelField == $dbColumn ? $dbColumn : $dbColumn.' as `'.$modelField.'`';
        }

        return $asColumns;
    }

    /**
     * @param array $objectData
     * @return ArraySerializable
     */
    protected function buildObject(array $objectData) : ArraySerializable
    {
        return $this->getFactory()->create($objectData);
    }

    /**
     * @param array $data
     * @return ArraySerializable
     * @throws InfrastructureException
     */
    protected function createObject(array $data) : ArraySerializable
    {
        $identifier = $this->getFromConfig(self::CREATE_CONDITION);

        $qb = new QueryBuilder($this->getFromConfig(self::COLUMNS));
        $insertQuery = $qb->getInsertQuery($data, $this->getFromConfig(self::TABLE));
        $this->db->execute($insertQuery->getQuery(), $insertQuery->getBindingValues());
        return $this->buildObject(array_merge($data, [$identifier => $this->db->lastInsertId()]));
    }

    /**
     * @param array $data
     * @return ArraySerializable
     * @throws InfrastructureException
     */
    protected function updateObject(array $data) : ArraySerializable
    {
        $whereKeys = $this->getFromConfig(self::UPDATE_CONDITION);

        $updateValues = array_diff_key($data, array_flip($whereKeys));
        $whereValues = array_intersect_key($data, array_flip($whereKeys));

        $qb = new QueryBuilder($this->getFromConfig(self::COLUMNS));
        $updateQuery = $qb->getUpdateQuery($updateValues, $whereValues, $this->getFromConfig(self::TABLE));

        $this->db->execute($updateQuery->getQuery(), $updateQuery->getBindingValues());
        return $this->buildObject($data);
    }

    /**
     * @param array $identifiers
     * @return ArraySerializable
     * @throws InfrastructureException
     */
    public function get(array $identifiers) : ArraySerializable
    {
        $conditions = [];
        foreach ($identifiers as $indName => $indValue) {
            $conditions[] = new EqualCriteria($indName, $indValue);
        }

        return $this->load(new SearchCriteriaConstructor($conditions))->getFirst();
    }

    /**
     * @return string
     * @throws InfrastructureException
     */
    protected function getSelectQuery() : string
    {
        return 'SELECT SQL_CALC_FOUND_ROWS '.implode(', ', $this->getAsColumns($this->getFromConfig(self::COLUMNS))).' FROM '.
               $this->getFromConfig(self::TABLE) .' '. $this->getJoins().' ';
    }

    /**
     * @return string
     * @throws InfrastructureException
     */
    protected function getDeleteQuery() : string
    {
        return 'DELETE '.$this->getFromConfig(self::TABLE).' FROM '.$this->getFromConfig(self::TABLE).' '.$this->getJoins().' ';
    }

    /**
     * @return string
     */
    protected function getJoins() : string
    {
        return '';
    }

    /**
     * @return mixed
     * @throws InfrastructureException
     */
    protected function getLoadTotalCount() : int
    {
        return $this->db->execute('SELECT FOUND_ROWS() as count', [])->fetch()['count'];
    }

    /**
     * @param SearchCriteria $filter
     * @return bool
     * @throws InfrastructureException
     */
    public function delete(SearchCriteria $filter) : bool
    {
        $queryBuilder = new QueryBuilder($this->getFromConfig(self::COLUMNS));
        try {
            $whereQueryPart = $queryBuilder->generateWhere($filter);
        } catch (QueryBuilderEmptyInQueryException $exception) {
            return true;
        }
        $query = $this->getDeleteQuery().$whereQueryPart->getQuery();

        $this->db->execute($query, $whereQueryPart->getBindingValues());

        return true;
    }

    /**
     * @return string
     */
    protected function getClassName() : string
    {
        return \get_class($this);
    }

    /**
     * @return BaseFactory
     */
    protected function getFactory() : BaseFactory
    {
        return $this->factory;
    }

    /**
     * @param $name
     * @return mixed
     * @throws InfrastructureException
     */
    protected function getFromConfig($name)
    {
        if(!array_key_exists($name, $this->config)){
            throw new InfrastructureException('There are no ' . $name . ' field in config');
        }

        return $this->config[$name];
    }
}
