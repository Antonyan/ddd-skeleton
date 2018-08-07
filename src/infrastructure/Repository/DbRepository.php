<?php

namespace Infrastructure\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

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

}