<?php

namespace Infrastructure\Models;

class DbQueryPart
{
    private $query = '';

    private $bindingValues = [];

    public function __construct($query, array $bindingValues)
    {
        $this->setQuery($query)
            ->setBindingValues($bindingValues);
    }

    /**
     * @return string
     */
    public function getQuery() : string
    {
        return $this->query;
    }

    /**
     * @param string $query
     * @return DbQueryPart
     */
    private function setQuery($query) : DbQueryPart
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return array
     */
    public function getBindingValues() : array
    {
        return $this->bindingValues;
    }

    /**
     * @param array $bindingValues
     * @return DbQueryPart
     */
    private function setBindingValues($bindingValues) : DbQueryPart
    {
        $this->bindingValues = $bindingValues;

        return $this;
    }
}
