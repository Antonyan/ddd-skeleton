<?php

namespace Infrastructure\Models\SearchCriteria;

class SearchCriteriaConstructor implements SearchCriteria
{
    private const MAX_LIMIT = 100;

    /**
     * @var Condition[]
     */
    private $conditions;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var OrderBy
     */
    private $orderBy;

    /**
     * SearchCriteriaConstructor constructor.
     * @param Condition[] $conditions
     * @param int $limit
     * @param int $offset
     * @param OrderBy $orderBy
     */
    public function __construct(array $conditions, int $limit = self::MAX_LIMIT, int $offset = 0, OrderBy $orderBy = null)
    {
        $this->conditions = $conditions;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->orderBy = $orderBy;
    }

    /**
     * @return int
     */
    public function limit() : int
    {
        return $this->limit <= self::MAX_LIMIT ? $this->limit : self::MAX_LIMIT;
    }

    /**
     * @return int
     */
    public function offset() : int
    {
        return $this->offset;
    }

    /**
     * @return array
     */
    public function orderBy() : array
    {
        return $this->orderBy->toOrderCondition();
    }

    /**
     * @return array
     */
    public function conditions() : array
    {
        $conditions = [];
        foreach ($this->conditions as $condition) {
            $conditions = array_merge($conditions, $condition->toCondition());
        }

        return $conditions;
    }
}