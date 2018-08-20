<?php

use Infrastructure\Models\SearchCriteria;

class SearchCriteriaTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testConditionCreations()
    {
        $this->tester->assertEquals((new SearchCriteria(['id' => 1]))->conditions(), ['id' => 1]);
    }

    public function testConditionInArrayCreation()
    {
        $this->tester->assertEquals((new SearchCriteria(['id' => '1,2']))->conditions(), ['id' => [1,2]]);
    }

    public function testSimpleAndInArrayConditionCombine()
    {
        $this->tester->assertEquals((new SearchCriteria(['id' => '1,2', 'name' => 'John']))->conditions(), ['id' => [1,2], 'name' => 'John']);
    }

    public function testOrderByASC()
    {
        $this->tester->assertEquals((new SearchCriteria(['orderByASC' => 'name']))->orderBy(), ['name' => 'asc']);
    }

    public function testOrderByDESC()
    {
        $this->tester->assertEquals((new SearchCriteria(['orderByDESC' => 'name']))->orderBy(), ['name' => 'desc']);
    }

    public function testLimit()
    {
        $this->tester->assertEquals((new SearchCriteria(['limit' => 1]))->limit(), 1);
    }

    public function testOffset()
    {
        $this->tester->assertEquals((new SearchCriteria(['offset' => 1]))->offset(), 1);
    }
}