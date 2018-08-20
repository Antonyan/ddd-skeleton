<?php

namespace Infrastructure\Models;

use Doctrine\Common\Annotations\AnnotationException;
use Infrastructure\Exceptions\InfrastructureException;
use Infrastructure\Exceptions\ValidationException;
use Infrastructure\Services\BaseService;
use ReflectionException;
use Symfony\Component\Validator\Exception\ValidatorException;
use Symfony\Component\Validator\Validation;

class Validator
{
    /**
     * @var array
     */
    private $rules;

    /**
     * Validator constructor.
     * @param array $rules
     */
    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    /**
     * @param array $dataForValidation
     * @throws ValidationException
     * @throws AnnotationException
     * @throws InfrastructureException
     * @throws ReflectionException
     */
    public function validate(array $dataForValidation) : void
    {
        if (!$this->rules){
            return;
        }

        $validator = Validation::createValidator();

        $errors = [];

        foreach ((new ValidationRulesTranslator())->translate($this->rules) as $item) {

            if (!array_key_exists($item->getName(), $dataForValidation)){
                $dataForValidation[$item->getName()] = null;
            }

            $error = $validator->validate($dataForValidation[$item->getName()], $item->getConstraints());

            if (\count($error)) {
                $errors[] = new ValidationError($item->getName(), $error);
            }
        }


        if (\count($errors)){
            throw new ValidationException($errors);
        }
    }
}
