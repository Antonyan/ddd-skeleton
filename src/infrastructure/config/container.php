<?php

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Infrastructure\Services\AssociationsSerializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

$containerBuilder->register('entityManager')
    ->setFactory([EntityManager::class, 'create'])
    ->setArgument('$conn', $containerBuilder->get('config')->database)
    ->setArgument('$config', Setup::createAnnotationMetadataConfiguration(array(__DIR__."../../"), true))
;

$containerBuilder->register('serializer', Serializer::class)
    ->setArgument('$normalizers', [new ObjectNormalizer()])
    ->setArgument('$encoders', [new JsonEncoder()]);

$containerBuilder->register('associationsSerializer', AssociationsSerializer::class);

