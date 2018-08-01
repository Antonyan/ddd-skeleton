<?php

namespace Infrastructure\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class DbRepository extends BaseRepository
{
    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * DbRepository constructor.
     * @param EntityManager $entityManager
     * @param $entity
     */
    public function __construct(EntityManager $entityManager, $entity)
    {
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

}