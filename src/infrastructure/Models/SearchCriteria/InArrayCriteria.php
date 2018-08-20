<?php

namespace Infrastructure\Models\SearchCriteria;

class InArrayCriteria implements Condition
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var array
     */
    private $values;

    /**
     * EqualCriteria constructor.
     * @param string $key
     * @param array $values
     */
    public function __construct(string $key, array $values)
    {
        $this->key = $key;
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function toCondition() : array
    {
        return [$this->key => $this->values];
    }
}
