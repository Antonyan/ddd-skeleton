<?php

namespace Infrastructure\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\ORMInvalidArgumentException;

abstract class DbRepository extends BaseRepository
{
    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * DbRepository constructor.
     * @param EntityManager $entityManager
     * @param $entity
     */
    public function __construct(EntityManager $entityManager, $entity)
    {
        $this->entityManager =  $entityManager;
        $this->entityRepository = $entityManager->getRepository($entity);
    }

    /**
     * @param array $conditions
     * @return ArrayCollection
     */
    public function load(array $conditions) : ArrayCollection
    {
        return new ArrayCollection($this->entityRepository->findBy($conditions));
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(array $data)
    {
        $object = $this->createObject($data);
        $this->entityManager->persist($object);
        $this->entityManager->flush();
        return $object;
    }

    /**
     * @param $id
     * @param $entity
     * @return bool
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function delete($id, $entity) : bool
    {
        $this->entityManager->remove($this->entityManager->getReference($entity, $id));
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param array $data
     * @return mixed
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function update(array $data)
    {
        $object = $this->createObject($data);

        $this->entityManager->merge($object);
        $this->entityManager->flush();
        return $object;
    }

    /**
     * @param array $data
     * @return Collection
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws ORMInvalidArgumentException
     */
    public function batchUpdate(array $data) : Collection
    {
        $collection = new ArrayCollection();

        foreach ($data as $entityData) {
            $entity = $this->createObject($entityData);
            $collection->add($entity);
            $this->entityManager->merge($entity);
        }

        $this->entityManager->flush();

        return $collection;
    }

    /**
     * @param $id
     * @return null|object
     */
    public function get($id)
    {
        return $this->entityRepository->find($id);
    }
}
