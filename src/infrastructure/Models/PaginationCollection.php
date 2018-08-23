<?php

namespace Infrastructure\Models;

class PaginationCollection extends Collection
{
    const TOTAL_RESULTS = 'totalResults';
    const LIMIT = 'limit';
    const OFFSET = 'offset';
    
    /**
     * @var int
     */
    private $totalResult = 0;

    /**
     * @var int
     */
    private $limit = 0;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * PaginationCollection constructor.
     * @param int $totalResult
     * @param int $limit
     * @param int $offset
     */
    public function __construct($totalResult, $limit, $offset)
    {
        $this->setTotalResult($totalResult)->setLimit($limit)->setOffset($offset);
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return array_merge(
            [
            self::TOTAL_RESULTS => $this->getTotalResult(),
            self::LIMIT => $this->getLimit(),
            self::OFFSET => $this->getOffset()
            ],
            parent::toArray()
        );
    }

    /**
     * @return int
     */
    public function getTotalResult()
    {
        return $this->totalResult;
    }

    /**
     * @param int $totalResult
     * @return PaginationCollection
     */
    public function setTotalResult($totalResult)
    {
        $this->totalResult = $totalResult;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return PaginationCollection
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int $offset
     * @return PaginationCollection
     */
    public function setOffset($offset)
    {
        $this->offset = $offset;
        return $this;
    }
}