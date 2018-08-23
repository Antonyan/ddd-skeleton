<?php

namespace Infrastructure\Mappers;

use Infrastructure\Models\ArraySerializable;
use Infrastructure\Models\Collection;
use Infrastructure\Models\PaginationCollection;
use Infrastructure\Models\SearchCriteria\SearchCriteria;

abstract class BaseMapper
{
    /**
     * @param array $objectData
     * @return ArraySerializable
     */
    abstract protected function buildObject(array $objectData) : ArraySerializable;

    /**
     * @param array $objectData
     * @return ArraySerializable
     */
    protected function buildObjectOptionalFields(array $objectData)
    {
        return $this->buildObject($objectData);
    }

    /**
     * @param array $objectData
     * @return ArraySerializable
     */
    abstract public function create(array $objectData) : ArraySerializable;

    /**
     * @param array $objectData
     * @return ArraySerializable
     */
    abstract public function update(array $objectData) : ArraySerializable;

    /**
     * @param SearchCriteria $filter
     * @return PaginationCollection
     */
    abstract public function load(SearchCriteria $filter) : PaginationCollection;

    /**
     * @param SearchCriteria $filter
     * @return bool
     */
    abstract public function delete(SearchCriteria $filter) : bool;

    /**
     * @param ArraySerializable $objectData
     * @param $identifier
     * @return ArraySerializable
     */
    abstract protected function createObject(ArraySerializable $objectData, $identifier) : ArraySerializable;

    /**
     * @param ArraySerializable $objectData
     * @param array $whereKeys
     * @return ArraySerializable
     */
    abstract protected function updateObject(ArraySerializable $objectData, array $whereKeys) : ArraySerializable;

    /**
     * @param array $objectsParams
     * @param $totalCount
     * @param $limit
     * @param $offset
     * @return PaginationCollection
     */
    protected function buildPaginationCollection(array $objectsParams, $totalCount, $limit, $offset) : PaginationCollection
    {
        $collection = new PaginationCollection($totalCount, $limit, $offset);
        foreach ($objectsParams as $objectParams) {
            $collection->push($this->buildObjectOptionalFields($objectParams));
        }

        return $collection;
    }

    /**
     * @param array $objectsParams
     * @return Collection
     */
    protected function buildCollection(array $objectsParams) : Collection
    {
        $collection = new Collection();
        foreach ($objectsParams as $objectParams) {
            $collection->push($this->buildObjectOptionalFields($objectParams));
        }

        return $collection;
    }
}
