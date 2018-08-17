<?php

namespace Infrastructure\Models;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Exception;
use Infrastructure\Annotations\Validation;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Services\BaseService;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Time;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\InvalidOptionsException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class ValidationRulesReader
{
    /**
     * @var BaseService
     */
    private $controllerForValidation;

    /**
     * @var string
     */
    private $methodForValidation;

    /**
     * @var array
     */
    private $rules;

    /**
     * ValidationRulesReader constructor.
     * @param BaseService $controllerForValidation
     * @param $methodForValidation
     */
    public function __construct(BaseService $controllerForValidation, $methodForValidation)
    {
        $this->controllerForValidation = $controllerForValidation;
        $this->methodForValidation = $methodForValidation;
    }

    /**
     * @return ValidationRule[]
     * @throws AnnotationException
     * @throws ReflectionException
     * @throws ConstraintDefinitionException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     */
    public function rules() : array
    {
        if ($this->rules) {
            return $this->rules;
        }

        $reflectionClass = new ReflectionClass($this->controllerForValidation);
        $method = $reflectionClass->getMethod($this->methodForValidation);

        $this->rules = (new AnnotationReader())->getMethodAnnotations($method);
        return $this->rules;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function validationFields() : array
    {
        $validationFields = [];

        /** @var Validation $rule */
        foreach ($this->rules() as $rule) {
            $validationFields[] = $rule->name;
         }

         return $validationFields;
    }
}
