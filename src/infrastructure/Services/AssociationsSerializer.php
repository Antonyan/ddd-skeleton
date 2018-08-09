<?php

namespace Infrastructure\Services;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AssociationsSerializer
{
    /**
     * @param $object
     * @param $format
     * @param $associatedFieldName
     * @return string
     * @throws RuntimeException
     */
    public function serialize($object, $format = 'json', $associatedFieldName = 'getId') : string
    {
        return (new Serializer([
            (new ObjectNormalizer())
                ->setCircularReferenceHandler(
                    function ($object) use ($associatedFieldName) {
                        if (\in_array($associatedFieldName, get_class_methods($object), true)){
                            return $object->$associatedFieldName();
                        }
                        return null;
                    })
        ], [new JsonEncoder()]))->serialize($object, $format);
    }
}