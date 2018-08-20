<?php

use Infrastructure\Models\SearchCriteria\EqualCriteria;
use Infrastructure\Models\SearchCriteria\InArrayCriteria;
use Infrastructure\Models\SearchCriteria\OrderBy;
use Infrastructure\Models\SearchCriteria\SearchCriteriaConstructor;

class SearchCriteriaConstructorTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testConditionCreations() : void
    {
        $conditions = [new EqualCriteria('name', 'John'), new InArrayCriteria('id', [1,2,3])];
        $constructor = new SearchCriteriaConstructor($conditions);

        $this->tester->assertEquals($constructor->conditions(), ['id' => [1,2,3], 'name' => 'John']);
    }

    public function testOrderByASC() : void
    {
        $this->tester->assertEquals((new SearchCriteriaConstructor([], 100, 0, new OrderBy('ASC', 'name')))->orderBy(), ['name' => 'asc']);
    }

    public function testLimit() : void
    {
        $this->tester->assertEquals((new SearchCriteriaConstructor([], 105))->limit(), 100);
    }

    public function testOffset() : void
    {
        $this->tester->assertEquals((new SearchCriteriaConstructor([], 100, 10))->offset(), 10);
    }
}