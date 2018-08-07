<?php

namespace Infrastructure\Models;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Infrastructure\Annotations\Validation;
use Infrastructure\Exceptions\InfrastructureException;
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
    private $controllerForValidation;

    private $methodForValidation;

    /**
     * ValidationRulesReader constructor.
     * @param $controllerForValidation
     * @param $methodForValidation
     */
    public function __construct($controllerForValidation, $methodForValidation)
    {
        $this->controllerForValidation = $controllerForValidation;
        $this->methodForValidation = $methodForValidation;
    }

    /**
     * @return ValidationRule[]
     * @throws AnnotationException
     * @throws InfrastructureException
     * @throws ReflectionException
     * @throws ConstraintDefinitionException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     */
    public function rules() : array
    {
        $reflectionClass = new ReflectionClass($this->controllerForValidation);
        $method = $reflectionClass->getMethod($this->methodForValidation);

        $rules = [];

        /** @var Validation $ruleDescription */
        foreach ((new AnnotationReader())->getMethodAnnotations($method) as $ruleDescription) {
            $rule = new ValidationRule($ruleDescription->name);

            if (!$ruleDescription->type) {
                $ruleDescription->type = 'string';
            }

            foreach ($this->constainsMap() as $field => $constrain) {

                if (!$ruleDescription->$field){
                    continue;
                }

                $rule->addConstraint($constrain($ruleDescription->$field));
            }

            $rules[] = $rule;
        }

        return $rules;
    }

    /**
     * @param $type
     * @return Type
     * @throws InfrastructureException
     * @throws ConstraintDefinitionException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     */
    public function addType($type) : Type
    {
        $supportedTypes = ['array', 'bool', 'callable', 'float', 'double', 'int', 'integer',
            'iterable', 'long', 'null', 'numeric', 'object', 'real', 'resource', 'scalar', 'string'];

        $additionalTypes = [
            'date' => function() {return new Date();},
            'dateTime' => function() {return new DateTime();},
            'time'=> function() {return new Time();}
        ];

        if (array_key_exists($type, $additionalTypes)){
            return $additionalTypes[$type]();
        }

        if (!\in_array($type, $supportedTypes, true)){
            throw new InfrastructureException('Unsupported type for validation');
        }

        return new Type(['type' => $type]);
    }

    /**
     * @return array
     * @throws InfrastructureException
     * @throws ConstraintDefinitionException
     * @throws InvalidOptionsException
     * @throws MissingOptionsException
     */
    private function constainsMap()
    {
        return[
            'type' => function($value) {return $this->addType($value);},
            'required' => function($value) {return new NotBlank();},
            'minLength' => function($value) {return new Length(['min' => $value]);},
            'maxLength' => function($value) {return new Length(['max' => $value]);},
        ];
    }

}