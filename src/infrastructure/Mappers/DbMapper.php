<?php

namespace Infrastructure\Mappers;

use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Exceptions\QueryBuilderEmptyInQueryException;
use Infrastructure\Models\ArraySerializable;
use Infrastructure\Models\EntityToDataSourceTranslator;
use Infrastructure\Models\PaginationCollection;
use Infrastructure\Models\SearchCriteria\EqualCriteria;
use Infrastructure\Models\SearchCriteria\SearchCriteria;
use Infrastructure\Models\SearchCriteria\SearchCriteriaConstructor;
use Infrastructure\Models\SearchCriteria\SearchCriteriaQueryString;
use Infrastructure\Services\BaseFactory;
use Infrastructure\Services\FilterToQueryTranslator;
use Infrastructure\Services\MySQLClient;

abstract class DbMapper extends BaseMapper
{
    /**
     * Signs for where conditions.
     */
    public const EQUAL_SIGN = '=';
    public const GREATER_SIGN = '>';
    public const LESS_SIGN = '<';
    public const GREATER_OR_EQUAL_SIGN = '>=';
    public const LESS_OR_EQUAL_SIGN = '<=';
    public const IN_SIGN = 'in';
    public const LIKE_SIGN = 'like';

    public const SELECT_LIMIT_ALL = 'selectLimitAll';

    public const TABLE = 'table';
    public const COLUMNS = 'columns';
    public const CREATE_CONDITION = 'create';
    public const UPDATE_CONDITION = 'update';

    /**
     * @var BaseFactory
     */
    private $factory;

    /**
     * @var array
     */
    private $config;

    /**
     * @var MySQLClient
     */
    private $mySqlClient;

    /**
     * @var EntityToDataSourceTranslator
     */
    private $entityToDataSourceTranslator;

    /**
     * DbMapper constructor.
     * @param BaseFactory $factory
     * @param array $config
     * @param MySQLClient $mySqlClient
     */
    public function __construct(
        BaseFactory $factory,
        array $config,
        MySQLClient $mySqlClient
    ) {
        $this->factory = $factory;
        $this->config = $config;
        $this->mySqlClient = $mySqlClient;
        $this->entityToDataSourceTranslator = new EntityToDataSourceTranslator($config);
    }

    /**
     * @param SearchCriteria $filter
     * @return PaginationCollection
     * @throws InfrastructureException
     */
    public function load(SearchCriteria $filter) : PaginationCollection
    {
        /** @var SearchCriteriaQueryString $filter */
        $queryBuilder = new FilterToQueryTranslator($this->getFromConfig(self::COLUMNS));
        try {
            $whereQueryPart = $queryBuilder->generateWhere($filter);
        } catch (QueryBuilderEmptyInQueryException $exception) {
            return new PaginationCollection(0, $filter->limit(), $filter->offset());
        }

        $query =
            $this->getSelectQuery().' '.
            $whereQueryPart->getQuery().' '.
            $queryBuilder->generateOrderBy($filter).' '.
            $queryBuilder->generateGroupBy($filter).' '.
            $queryBuilder->generateLimit($filter);

        $collection = $this->buildPaginationCollection(
            $this->mySqlClient->fetchAll($query, $whereQueryPart->getBindingValues()),
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
        return $this->buildObject(array_merge($data,
            [$this->entityToDataSourceTranslator->insertIdentifier() => $this->mySqlClient->insert(
                $this->entityToDataSourceTranslator->table(),
                $this->entityToDataSourceTranslator->translatePropertyInColumn($data))]));
    }

    /**
     * @param array $data
     * @return ArraySerializable
     * @throws InfrastructureException
     */
    protected function updateObject(array $data) : ArraySerializable
    {
        $this->mySqlClient->update(
            $this->entityToDataSourceTranslator->table(),
            $this->entityToDataSourceTranslator->extractUpdateParams($data),
            $this->entityToDataSourceTranslator->extractUpdateIdentifiers($data)
        );

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
     */
    protected function getJoins() : string
    {
        return '';
    }

    /**
     * @return int
     * @throws InfrastructureException
     */
    protected function getLoadTotalCount() : int
    {
        return $this->mySqlClient->fetch('SELECT FOUND_ROWS() as count', [])['count'];
    }

    /**
     * @param string $byPropertyName
     * @param $propertyValue
     * @return bool
     * @throws InfrastructureException
     */
    public function delete(string $byPropertyName, $propertyValue) : bool
    {
        $this->mySqlClient->delete($this->getFromConfig(self::TABLE), [$byPropertyName => $propertyValue]);
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

    /**
     * @param array $data
     * @return array
     * @throws InfrastructureException
     */
    protected function getColumnValue(array $data): array
    {
        $filteredFields = array_intersect_key($data, $this->getFromConfig(self::COLUMNS));

        $columnValue = [];
        foreach ($filteredFields as $key => $value) {
            $columnValue[$this->getFromConfig(self::COLUMNS)[$key]] = $value;
        }

        return $columnValue;
    }
}
