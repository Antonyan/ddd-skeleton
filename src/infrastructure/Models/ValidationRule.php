<?php

namespace Infrastructure\Models;

use Symfony\Component\Validator\Constraint;

class ValidationRule
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $constraints;

    /**
     * ValidationRule constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param Constraint $constraint
     */
    public function addConstraint(Constraint $constraint): void
    {
        $this->constraints[] = $constraint;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @param $constraints
     * @return array
     */
    public function setConstraints($constraints): array
    {
        $this->constraints = $constraints;
    }
}