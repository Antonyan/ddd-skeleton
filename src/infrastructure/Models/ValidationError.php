<?php

namespace Infrastructure\Models;

use JsonSerializable;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationError implements JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var ConstraintViolationList
     */
    private $error;

    /**
     * ValidationError constructor.
     * @param string $name
     * @param ConstraintViolationList $error
     */
    public function __construct(string $name, ConstraintViolationList $error)
    {
        $this->name = $name;
        $this->error = $error;
    }

    /**
     * @return array
     */
    public function printMessage() : array
    {
        $error = [];

        /** @var ConstraintViolation $constraintViolation */
        foreach ($this->error as $constraintViolation) {
            $error[] = [
                'field' => $this->name,
                'error' => $constraintViolation->getMessage()
            ];
        }

        return $error;
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array
    {
        return $this->printMessage();
    }
}