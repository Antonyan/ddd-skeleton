<?php

namespace Infrastructure\Models\SearchCriteria;

class SearchCriteriaQueryString implements SearchCriteria
{
    private const CONDITIONS = 'conditions';
    private const LIMIT = 'limit';
    private const OFFSET = 'offset';
    private const ORDER_BY = 'orderBy';
    private const ORDER_BY_ASC = 'orderByASC';
    private const ORDER_BY_DESC = 'orderByDESC';
    private const MAX_LIMIT = 100;
    
    /**
     * @var array
     */
    private $criteria;

    /**
     * @var array
     */
    private $parsedCriteria;

    /**
     * Constructor receive array with data from query string
     * Query string can look like (url?id=1,2,5&name=Ivanov&orderByACS=name)
     * And it would be parsed by Symfony as ['id' => '1,2,5', 'name' => 'Ivanov', 'orderByACS' => 'name']
     * SearchCriteria constructor.
     * @param array $criteria
     */
    public function __construct(array $criteria)
    {
        $this->criteria = $criteria;
    }

    /**
     * @return int
     */
    public function limit() : int
    {
        return array_key_exists(self::LIMIT, $this->parsedCriteria())
        && $this->parsedCriteria()[self::LIMIT] < self::MAX_LIMIT ?
            $this->parsedCriteria()[self::LIMIT] : self::MAX_LIMIT;
    }

    /**
     * @return int
     */
    public function offset() : int
    {
        return array_key_exists(self::OFFSET, $this->parsedCriteria()) ?
            $this->parsedCriteria()[self::OFFSET] : 0;
    }

    /**
     * @return array
     */
    public function orderBy() : array
    {
        return array_key_exists(self::ORDER_BY, $this->parsedCriteria()) ?
            $this->parsedCriteria()[self::ORDER_BY] : [];
    }

    /**
     * @return array
     */
    public function conditions() : array
    {
        return array_key_exists(self::CONDITIONS, $this->parsedCriteria()) ?
            $this->parsedCriteria()[self::CONDITIONS] : [];
    }

    /**
     * @return array
     */
    private function parsedCriteria() : array
    {
        if ($this->parsedCriteria) {
            return $this->parsedCriteria;
        }

        $this->parsedCriteria[self::CONDITIONS] = [];

        foreach ($this->criteria as $key => $criterion) {

            if (array_key_exists($key, $this->conversionMap())){
                $this->conversionMap()[$key]($criterion);
                unset($this->criteria[$key]);
                continue;
            }

            if (strpos($criterion, ',')){
                $this->parsedCriteria[self::CONDITIONS] = [$key => explode(',', $criterion)];
                unset($this->criteria[$key]);
            }
        }

        $this->parsedCriteria[self::CONDITIONS] = array_merge($this->criteria, $this->parsedCriteria[self::CONDITIONS]);

        return $this->parsedCriteria;
    }

    /**
     * @return array
     */
    private function conversionMap() : array
    {
        return [
            self::LIMIT => function($value) { $this->parsedCriteria[self::LIMIT] = $value;},
            self::OFFSET => function($value) { $this->parsedCriteria[self::OFFSET] = $value;},
            self::ORDER_BY_ASC => function($value) { $this->parsedCriteria[self::ORDER_BY] = [$value => 'asc'];},
            self::ORDER_BY_DESC => function($value) { $this->parsedCriteria[self::ORDER_BY] = [$value => 'desc'];},
        ];
    }
}