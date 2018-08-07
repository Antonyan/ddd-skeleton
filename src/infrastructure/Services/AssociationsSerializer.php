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
     * @param Collection $arrayCollection
     * @param $format
     * @param $associatedFieldName
     * @return Serializer
     * @throws RuntimeException
     */
    public function serialize(Collection $arrayCollection, $format, $associatedFieldName)
    {
        return (new Serializer([
            (new ObjectNormalizer())
                ->setCircularReferenceHandler(
                    function ($object) use ($associatedFieldName) {
                        return $object->$associatedFieldName();
                    })
        ], [new JsonEncoder()]))->serialize($arrayCollection, $format);
    }
}