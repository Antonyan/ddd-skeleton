<?php

namespace Infrastructure\Models;

use Infrastructure\Exceptions\InfrastructureException;

class EntityToDataSourceTranslator
{
    private const COLUMNS = 'columns';
    private const TABLE = 'table';
    private const CREATE_CONDITION = 'create';
    private const UPDATE_CONDITION = 'update';

    /**
     * @var array
     */
    private $mapperConfig;

    /**
     * EntityToDataSourceTranslator constructor.
     * @param array $mapperConfig
     */
    public function __construct(array $mapperConfig)
    {
        $this->mapperConfig = $mapperConfig;
    }

    /**
     * @return string
     */
    public function table() : string
    {
        return $this->mapperConfig[self::TABLE];
    }

    /**
     * @return array
     */
    public function propertyToColumnMap() : array
    {
        return $this->mapperConfig[self::COLUMNS];
    }

    /**
     * @return string
     */
    public function insertIdentifier() : string
    {
        return $this->mapperConfig[self::CREATE_CONDITION];
    }

    /**
     * @param $property
     * @return mixed
     * @throws InfrastructureException
     */
    public function columnName($property)
    {
        if (!array_key_exists($property, $this->mapperConfig[self::COLUMNS])){
            throw new InfrastructureException('There isn\'t such property in column map');
        }
        return $this->mapperConfig[self::COLUMNS][$property];
    }

    /**
     * @param array $propertyWithValues
     * @return array
     * @throws InfrastructureException
     */
    public function translatePropertyInColumn(array $propertyWithValues) : array
    {
        $filteredFields = array_intersect_key($propertyWithValues, $this->mapperConfig[self::COLUMNS]);

        $columnWithValues = [];
        foreach ($filteredFields as $key => $value) {
            if (!array_key_exists($key, $this->mapperConfig[self::COLUMNS])){
                throw new InfrastructureException('There isn\'t such property in column map');
            }
            $columnWithValues[$this->mapperConfig[self::COLUMNS][$key]] = $value;
        }

        return $columnWithValues;
    }

    /**
     * @param array $propertyWithValues
     * @return array
     * @throws InfrastructureException
     */
    public function extractUpdateIdentifiers(array $propertyWithValues) : array
    {
        return $this->translatePropertyInColumn(
            array_intersect_key($propertyWithValues, array_flip($this->mapperConfig[self::UPDATE_CONDITION])));
    }

    /**
     * @param array $propertyWithValues
     * @return array
     * @throws InfrastructureException
     */
    public function extractUpdateParams(array $propertyWithValues) : array
    {
        return $this->translatePropertyInColumn(
            array_diff_key($propertyWithValues, array_flip($this->mapperConfig[self::UPDATE_CONDITION])));
    }
}