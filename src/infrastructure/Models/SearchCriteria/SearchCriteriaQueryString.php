<?php

namespace Infrastructure\Models\SearchCriteria;

class SearchCriteriaQueryString extends SearchCriteria
{
    /**
     * @var array
     */
    private $criteria;

    /**
     * @var array
     */
    private $nameOfDateFields;

    /**
     * @var array
     */
    private $conditions = [];

    /**
     * @var int
     */
    private $limit = 100;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array
     */
    private $orderBy = [];

    /**
     * @var array
     */
    private $groupBy = [];

    /**
     * @var bool
     */
    private $criteriaParsed = false;

    /**
     * Constructor receive array with data from query string
     * Query string can look like (url?id=1,2,5&name=Ivanov&orderByACS=name)
     * And it would be parsed by Symfony as ['id' => '1,2,5', 'name' => 'Ivanov', 'orderByACS' => 'name']
     * SearchCriteria constructor.
     * @param array $criteria
     * @param array $nameOfDateFields
     */
    public function __construct(array $criteria, array $nameOfDateFields = [])
    {
        $this->criteria = $criteria;
        $this->nameOfDateFields = $nameOfDateFields;
    }

    /**
     * @return array
     */
    public function conditions() : array
    {
        return $this->parseCriteria()->conditions;
    }

    /**
     * @return int
     */
    public function limit() : int
    {
        return $this->parseCriteria()->limit;
    }

    /**
     * @return int
     */
    public function offset() : int
    {
        return $this->parseCriteria()->offset;
    }

    /**
     * @return array
     */
    public function orderBy() : array
    {
        return $this->parseCriteria()->orderBy;
    }

    /**
     * @return array
     */
    public function groupBy() : array
    {
        return $this->parseCriteria()->groupBy;
    }

    /**
     * @param $field
     * @return bool
     */
    public function isSetType($field) : bool
    {
        return isset($this->parseCriteria()->types[$field]);
    }

    /**
     * @param $field
     * @return string
     */
    public function getType($field) : string
    {
        return $this->parseCriteria()->types[$field];
    }

    /**
     * @return SearchCriteriaQueryString
     */
    private function parseCriteria() : SearchCriteria
    {
        if ($this->criteriaParsed){
            return $this;
        }

        foreach ($this->criteria as $field => $value) {
            if (\in_array($field, $this->nameOfDateFields, true)) {
                $this->addDateCondition($field, $value);
                continue;
            }

            if (array_key_exists($field, $this->conversionMap())){
                $this->conversionMap()[$field]($value);
                continue;
            }

            if (strpos($value, ',')) {
                $this->addInCondition($field, explode(',', $value));
                continue;
            }

            $this->addEqualCondition($field, $value);
        }

        $this->criteriaParsed = true;

        return $this;
    }

    /**
     * @return array
     */
    private function conversionMap() : array
    {
        return [
            self::LIMIT => function($value) { $this->limit = $this->limit <= self::MAX_LIMIT ? $value : self::MAX_LIMIT;},
            self::OFFSET => function($value) { $this->offset = $value;},
            self::ORDER_ASCENDING => function($value) { $this->addOrderByAscending($value);},
            self::ORDER_DESCENDING => function($value) { $this->addOrderByDescending($value);},
            self::WHERE_LIKE => function($value) {
                foreach ($value as $innerField => $innerValue) {
                    $this->addLikeCondition($innerField, $innerValue);}
            },
            self::WHERE_EQUAL => function($value) { $this->addArrayEqualCondition($value);},
            self::WHERE_LESS => function($value) { $this->addArrayLessCondition($value);},
            self::WHERE_LESS_OR_EQUAL => function($value) { $this->addArrayLessOrEqualCondition($value);},
            self::WHERE_GREATER => function($value) { $this->addArrayGreaterCondition($value);},
            self::WHERE_GREATER_OR_EQUAL => function($value) { $this->addArrayGreaterOrEqualCondition($value);},
        ];
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addEqualCondition($field, $value) : SearchCriteria
    {
        $this->conditions[self::WHERE_EQUAL_SIGN][$field] = $value;
        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addInCondition($field, $value) : SearchCriteria
    {
        $this->conditions[self::WHERE_IN][$field] = $value;
        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addLikeCondition($field, $value) : SearchCriteria
    {
        $this->conditions[self::WHERE_LIKE][$field] = '%'.$value.'%';
        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addLessCondition($field, $value) : SearchCriteria
    {
        $this->conditions[self::WHERE_LESS_SIGN][$field] = $value;
        $this->addFieldType($field, self::TYPE_DECIMAL);

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addLessOrEqualCondition($field, $value) : SearchCriteria
    {
        $this->conditions[self::WHERE_LESS_OR_EQUAL_SIGN][$field] = $value;
        $this->addFieldType($field, self::TYPE_DECIMAL);

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addGreaterCondition($field, $value) : SearchCriteria
    {
        $this->conditions[self::WHERE_GREATER_SIGN][$field] = $value;
        $this->addFieldType($field, self::TYPE_DECIMAL);

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addGreaterOrEqualCondition($field, $value) : SearchCriteria
    {
        $this->conditions[self::WHERE_GREATER_OR_EQUAL_SIGN][$field] = $value;
        $this->addFieldType($field, self::TYPE_DECIMAL);

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addDateCondition($field, $value) : SearchCriteria
    {
        if (is_array($value)) {
            if (count($value) == 2) {
                $this->conditions[self::WHERE_GREATER_OR_EQUAL_SIGN][$field] = date('Y-m-d 00:00:00', strtotime($value[0]));
                $this->conditions[self::WHERE_LESS_OR_EQUAL_SIGN][$field] = date('Y-m-d 23:59:59', strtotime($value[1]));
            }
        } else {
            $this->conditions[self::WHERE_GREATER_OR_EQUAL_SIGN][$field] = date('Y-m-d 00:00:00', strtotime($value));
        }

        return $this;
    }

    /**
     * @param $field
     * @param $value
     * @return SearchCriteria
     */
    private function addDateTimeInTimestampCondition($field, $value) : SearchCriteria
    {
        if (is_array($value)) {
            if (count($value) == 2) {
                $this->conditions[self::WHERE_GREATER_OR_EQUAL_SIGN][$field] = date('Y-m-d H:i:s', $value[0]);
                $this->conditions[self::WHERE_LESS_SIGN][$field] = date('Y-m-d H:i:s', $value[1]);
            }
        } else {
            $this->conditions[self::WHERE_GREATER_OR_EQUAL_SIGN][$field] = date('Y-m-d H:i:s', $value);
        }

        return $this;
    }

    /**
     * @param array $values
     * @return SearchCriteria
     */
    private function addArrayEqualCondition(array $values) : SearchCriteria
    {
        foreach ($values as $field => $value) {
            $this->addEqualCondition($field, $value);
        }

        return $this;
    }

    /**
     * @param array $values
     * @return SearchCriteria
     */
    private function addArrayLessCondition(array $values) : SearchCriteria
    {
        foreach ($values as $field => $value) {
            $this->addLessCondition($field, $value);
        }

        return $this;
    }

    /**
     * @param array $values
     * @return SearchCriteria
     */
    private function addArrayLessOrEqualCondition(array $values) : SearchCriteria
    {
        foreach ($values as $field => $value) {
            $this->addLessOrEqualCondition($field, $value);
        }

        return $this;
    }

    /**
     * @param array $values
     * @return SearchCriteria
     */
    private function addArrayGreaterCondition(array $values) : SearchCriteria
    {
        foreach ($values as $field => $value) {
            $this->addGreaterCondition($field, $value);
        }

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    private function addArrayGreaterOrEqualCondition(array $values) : SearchCriteria
    {
        foreach ($values as $field => $value) {
            $this->addGreaterOrEqualCondition($field, $value);
        }

        return $this;
    }

    /**
     * @param $field
     * @return SearchCriteria
     */
    private function addOrderByAscending($field) : SearchCriteria
    {
        if (\is_array($field)) {
            foreach ($field as $singleField) {
                $this->orderBy[$singleField] = self::ORDER_ASCENDING;
            }
            return $this;
        }
        $this->orderBy[$field] = self::ORDER_ASCENDING;

        return $this;
    }

    /**
     * @param $field
     * @return SearchCriteria
     */
    private function addOrderByDescending($field) : SearchCriteria
    {
        if (\is_array($field)) {
            foreach ($field as $singleField) {
                $this->orderBy[$singleField] = self::ORDER_DESCENDING;
            }

            return $this;
        }
        $this->orderBy[$field] = self::ORDER_DESCENDING;

        return $this;
    }

    /**
     * @param $field
     * @return SearchCriteria
     */
    private function addGroupBy($field) : SearchCriteria
    {
        $this->groupBy[] = $field;

        return $this;
    }

    /**
     * @param $field
     * @param $type
     * @return SearchCriteria
     */
    private function addFieldType($field, $type) : SearchCriteria
    {
        $this->types[$field] = $type;
        return $this;
    }
}