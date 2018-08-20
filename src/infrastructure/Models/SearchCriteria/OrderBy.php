<?php

namespace Infrastructure\Models\SearchCriteria;

use Infrastructure\Exceptions\InfrastructureException;

class OrderBy
{
    /**
     * @var string
     */
    private $order;

    /**
     * @var string
     */
    private $key;

    /**
     * OrderBy constructor.
     * @param string $order
     * @param string $key
     * @throws InfrastructureException
     */
    public function __construct(string $order, string $key)
    {
        $this->setOrder($order);
        $this->key = $key;
    }

    /**
     * @param string $order
     * @throws InfrastructureException
     */
    private function setOrder(string $order): void
    {
        $filteredOrder = strtolower($order);

        if (!\in_array($filteredOrder, ['asc','desc'])){
            throw new InfrastructureException('Order can be either ASC or DESC');
        }
        $this->order = $filteredOrder;
    }

    /**
     * @return array
     */
    public function toOrderCondition() : array
    {
        return [$this->key => $this->order];
    }
}