<?php

namespace Infrastructure\Models\SearchCriteria;

class EqualCriteria implements Condition
{
    /**
     * @var string
     */
    private $key;

    /**
     * @var string
     */
    private $value;

    /**
     * EqualCriteria constructor.
     * @param string $key
     * @param string $value
     */
    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function toCondition() : array
    {
        return [$this->key => $this->value];
    }
}
