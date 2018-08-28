<?php

namespace Infrastructure\Services;

use Infrastructure\Exceptions\QueryBuilderEmptyInQueryException;
use Infrastructure\Mappers\DbMapper;
use Infrastructure\Models\Collection;
use Infrastructure\Models\DbQueryPart;
use Infrastructure\Models\SearchCriteria\SearchCriteria;
use Infrastructure\Models\SearchCriteria\SearchCriteriaQueryString;

class FilterToQueryTranslator
{
    /**
     * @var array
     */
    private $columns;

    /**
     * @var int
     */
    private $placeholder = 1;

    /**
     * QueryBuilder constructor.
     * @param array $columns
     */
    public function __construct(array $columns)
    {
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    private function getColumns() : array
    {
        return $this->columns;
    }

    /**
     * @return string
     */
    public function getUniquePlaceholder() : string
    {
        return 'placeholder'.$this->placeholder++;
    }

    /**
     * @param SearchCriteria $filter
     * @return DbQueryPart
     * @throws QueryBuilderEmptyInQueryException
     */
    public function generateWhere(SearchCriteria $filter) : DbQueryPart
    {
        $filterConditions = $filter->conditions();

        if (!count($filterConditions)) {
            return new DbQueryPart('', []);
        }

        $bindPlaceholders = [];
        $whereConditions = [];
        foreach ($filterConditions as $compareSign => $conditions) {
            if ($compareSign === DbMapper::IN_SIGN) {
                $whereInQueryPart = $this->generateWhereIn($filter);
                $whereConditions[] = $whereInQueryPart->getQuery();
                $bindPlaceholders = array_replace($bindPlaceholders, $whereInQueryPart->getBindingValues());
                continue;
            }

            foreach ($conditions as $field => $value) {
                if (!array_key_exists($field, $this->columns)) {
                    continue;
                }
                $placeholder = $this->getUniquePlaceholder();
                $whereFieldName = $this->getFieldNameByCompassFilterType($field, $filter);
                $whereConditions[] = $whereFieldName.' '.$compareSign.' :'.$placeholder;
                $bindPlaceholders[$placeholder] = $value;
            }
        }

        return new DbQueryPart($whereConditions ? ' WHERE ' . implode(' AND ', $whereConditions) : '', $bindPlaceholders);
    }

    /**
     * @param $field
     * @param SearchCriteria $filter
     * @return string
     */
    private function getFieldNameByCompassFilterType($field, SearchCriteria $filter) : string
    {
        if (!$filter->isSetType($field)) {
            return $this->getColumns()[$field];
        }

        if ($filter->getType($field) === SearchCriteria::TYPE_DATE) {
            return 'STR_TO_DATE('.$this->getColumns()[$field].', \'%Y-%m-%d %H:%i:%s\')';
        }

        return 'CAST('.$this->getColumns()[$field].' AS '.$filter->getType($field).')';
    }

    /**
     * @param SearchCriteria $filter
     * @return string
     */
    public function generateLimit(SearchCriteria $filter)
    {
        if ($filter->limit() === DbMapper::SELECT_LIMIT_ALL) {
            return '';
        }

        return ' LIMIT '. $filter->offset().', '. $filter->limit().' ';
    }

    /**
     * @param SearchCriteria $filter
     * @return string
     */
    public function generateOrderBy(SearchCriteria $filter) : string
    {
        if (!count($filter->orderBy())) {
            return '';
        }

        $orderByMap = [
            SearchCriteriaQueryString::ORDER_ASCENDING => 'ASC',
            SearchCriteriaQueryString::ORDER_DESCENDING => 'DESC',
        ];

        $orderFields = [];
        foreach ($filter->orderBy() as $orderByField => $order) {
            $orderFields[] = $this->getFieldNameByCompassFilterType($orderByField, $filter).' '.$orderByMap[$order];
        }

        return ' ORDER BY '.implode(', ', $orderFields);
    }

    /**
     * @param SearchCriteria $filter
     * @return string
     */
    public function generateGroupBy(SearchCriteria $filter) : string
    {
        if (!count($filter->groupBy())) {
            return '';
        }

        $groupByFields = [];
        foreach ($filter->groupBy() as $field) {
            $groupByFields[] = $this->getColumns()[$field];
        }

        return ' GROUP BY '.implode(', ', $groupByFields);
    }

    /**
     * @param SearchCriteria $filter
     * @return DbQueryPart
     * @throws QueryBuilderEmptyInQueryException
     */
    public function generateWhereIn(SearchCriteria $filter) : DbQueryPart
    {
        $filterConditions = $filter->getConditions();
        $bindPlaceholders = [];
        $whereInConditions = [];
        foreach ($filterConditions[DbMapper::IN_SIGN] as $field => $values) {
            if (!array_key_exists($field, $this->columns)) {
                continue;
            }
            if (!count($values)) {
                throw new QueryBuilderEmptyInQueryException();
            }
            $fieldVariants = [];
            foreach ($values as $value) {
                $placeholder = $this->getUniquePlaceholder();
                $fieldVariants[] = ':'.$placeholder;
                $bindPlaceholders[$placeholder] = $value;
            }
            $whereInConditions[] = $this->getFieldNameByCompassFilterType($field, $filter).' IN ('.implode(', ', $fieldVariants).')';
        }

        return new DbQueryPart(implode(' AND ', $whereInConditions), $bindPlaceholders);
    }

    /**
     * @param array $insertValues
     * @param $tableName
     * @return DbQueryPart
     */
    public function getInsertQuery(array $insertValues, $tableName) : DbQueryPart
    {
        $insertColumns = [];
        $placeholders = [];
        foreach ($insertValues as $field => $value) {
            if(!array_key_exists($field,$this->getColumns())){
                unset($insertValues[$field]);
                continue;
            }

            $insertColumns[] = $this->getColumns()[$field];
            $placeholders[] = ':'.$field;
        }

        $query = 'INSERT INTO '.$tableName.' ('.implode(', ', $insertColumns).') '.
            'VALUES ('.implode(', ', $placeholders).')';

        return new DbQueryPart($query, $insertValues);
    }

    /**
     * @param array $updateValues
     * @param array $whereValues
     * @param $tableName
     * @return DbQueryPart
     */
    public function getUpdateQuery(array $updateValues, array $whereValues, $tableName) : DbQueryPart
    {
        $updateParams = [];
        $whereParams = [];

        foreach ($updateValues as $field => $value) {
            if(!array_key_exists($field,$this->getColumns())){
                unset($updateValues[$field]);
                continue;
            }
            $updateParams[] = $this->getColumns()[$field].' = :'.$field;
        }

        foreach ($whereValues as $field => $value) {
            if(!array_key_exists($field,$this->getColumns())){
                unset($updateValues[$field]);
                continue;
            }
            $whereParams[] = $this->getColumns()[$field].' = :'.$field;
        }

        $query = 'UPDATE '.$tableName.' SET '.implode(', ', $updateParams).' WHERE '.implode(' AND ', $whereParams);

        return new DbQueryPart($query, array_merge($updateValues, $whereValues));
    }

    /**
     * @param Collection $collection
     * @param array $fieldGetterMap
     * @param $table
     * @return DbQueryPart
     */
    public function getBatchQuery(Collection $collection, array $fieldGetterMap, $table) : DbQueryPart
    {
        $insertColumns = [];
        $itemValues = [];
        $bindValues = [];

        foreach (array_keys($fieldGetterMap) as $field) {
            $insertColumns[] = $this->getColumns()[$field];
        }

        foreach ($collection as $object) {
            $oneItemValues = [];
            foreach ($fieldGetterMap as $field => $fieldValueGetter) {
                $placeholder = $this->getUniquePlaceholder();
                $oneItemValues[] = ':'.$placeholder;
                $bindValues[$placeholder] = $fieldValueGetter($object);
            }
            $itemValues[] = '('.implode(', ', $oneItemValues).')';
        }

        $query = 'INSERT INTO '.$table.' ('.implode(', ', $insertColumns).')  VALUES '.implode(', ', $itemValues).' ';

        return new DbQueryPart($query, $bindValues);
    }

    /**
     * @param array $updateValues
     * @param array $keyValues
     * @param $table
     * @return DbQueryPart
     */
    public function getSaveQuery(array $updateValues, array $keyValues, $table) : DbQueryPart
    {
        $insertQuery = $this->getInsertQuery(array_merge($updateValues, $keyValues), $table);

        return new DbQueryPart(
            $insertQuery->getQuery().$this->getOnDuplicateKeyUpdateEnding(array_keys($updateValues)),
            $insertQuery->getBindingValues()
        );
    }

    /**
     * @param array $valuesToUpdate
     * @return string
     */
    public function getOnDuplicateKeyUpdateEnding(array $valuesToUpdate) : string
    {
        $duplicateParams = [];
        foreach ($valuesToUpdate as $field) {
            $duplicateParams[] = $this->getColumns()[$field].' = VALUES('.$this->getColumns()[$field].')';
        }

        return ' ON DUPLICATE KEY UPDATE '.implode(', ', $duplicateParams).' ';
    }
}
