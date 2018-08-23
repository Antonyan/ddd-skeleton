<?php

namespace Infrastructure\Models;

use Infrastructure\Exceptions\InfrastructureException;

class Collection implements \IteratorAggregate, \Countable, ArraySerializable
{
    const COLLECTION = 'collection';
    const ITEMS = 'items';

    /**
     * @var ArraySerializable[]
     */
    private $collection = [];

    /**
     * @return ArraySerializable[]
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * @param ArraySerializable $object
     *
     * @return $this
     */
    public function push(ArraySerializable $object)
    {
        $this->collection[] = $object;

        return $this;
    }

    /**
     * @param int $position
     * @param ArraySerializable $object
     *
     * @return $this
     */
    public function set($position, ArraySerializable $object)
    {
        $this->collection[$position] = $object;

        return $this;
    }

    /**
     * @param int $position
     *
     * @return ArraySerializable
     */
    public function get($position)
    {
        return $this->issetElement($position) ? $this->collection[$position] : null;
    }

    /**
     * @param int $position
     *
     * @return bool
     */
    public function issetElement($position)
    {
        return array_key_exists($position, $this->collection);
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->collection);
    }

    /**
     * @param int $position
     *
     * @return Collection
     */
    public function remove($position)
    {
        unset($this->collection[$position]);

        return $this;
    }

    /**
     * @return ArraySerializable
     */
    public function getFirst()
    {
        reset($this->collection);

        return count($this->collection) > 0 ? current($this->collection) : null;
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        $objectArray = [];
        foreach ($this->collection as $objectKey => $object) {
            $objectArray[$objectKey] = $object->toArray();
        }

        return [
            self::ITEMS => $objectArray,
        ];
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    /**
     * @param $getterName
     *
     * @return $this
     */
    public function sortByEntityField($getterName)
    {
        usort($this->collection, function (ArraySerializable $a, ArraySerializable $b) use ($getterName) {
            if ($a->$getterName() === $b->$getterName()) {
                return 0;
            }

            return $a->$getterName() > $b->$getterName() ? 1 : -1;
        });

        return $this;
    }

    /**
     * @param $getterName
     * @return $this
     */
    public function sortByEntityFieldDesc($getterName)
    {
        usort($this->collection, function (ArraySerializable $a, ArraySerializable $b) use ($getterName) {
            if ($a->$getterName() === $b->$getterName()) {
                return 0;
            }

            return $a->$getterName() < $b->$getterName() ? 1 : -1;
        });

        return $this;
    }

    /**
     * @param $getterName
     *
     * @return array
     */
    public function getColumn($getterName)
    {
        $columnArray = [];
        foreach ($this->collection as $item) {
            $columnArray[] = $item->$getterName();
        }

        return $columnArray;
    }

    /**
     * @param $getterName
     * @return Collection
     */
    public function replaceKeys($getterName)
    {
        $collection = $this->collection;
        $this->collection = [];
        foreach ($collection as $item) {
            $this->set($item->$getterName(), $item);
        }

        return $this;
    }

    public function groupBy($getterName)
    {
        $items = $this->collection;
        $this->collection = [];

        foreach ($items as $item) {
            if (!array_key_exists($item->$getterName(), $this->collection)) {
                $this->collection[$item->$getterName()] = new Collection();
            }

            $groupedCollection = $this->collection[$item->$getterName()];

            $groupedCollection->push($item);
        }

        return $this;
    }

    /**
     * @param $limit
     * @return Collection
     */
    public function limit($limit)
    {
        $this->collection = array_slice($this->collection, 0, $limit, true);

        return $this;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function merge(Collection $collection) {
        $this->collection = array_merge($this->collection, $collection->getCollection());
        return $this;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function unite(Collection $collection) {
        $this->collection = $this->collection + $collection->getCollection();
        return $this;
    }

    /**
     * @param $callable
     * @return $this
     * @throws InfrastructureException
     */
    public function walk($callable)
    {
        if (!is_callable($callable)) {
            throw new InfrastructureException('You should pass callable function to walk it though collection');
        }

        foreach ($this->collection as $key => $model) {
            $this->set($key, $callable($model, $key));
        }

        return $this;
    }

    /**
     * @param $callable
     * @return $this
     * @throws InfrastructureException
     */
    public function filter($callable)
    {
        if (!is_callable($callable)) {
            throw new InfrastructureException('You should pass callable function to filter though collection');
        }

        $this->collection = array_values(array_filter($this->collection, $callable));

        return $this;
    }
}