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
     * @var ValidationRulesReader
     */
    private $validationRulesReader;

    /**
     * Validator constructor.
     * @param $controller
     * @param $method
     * @throws ValidatorException
     */
    public function __construct(BaseService $controller, $method)
    {
        $this->validationRulesReader = new ValidationRulesReader($controller, $method);
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
        if (!$this->validationRulesReader){
            return;
        }

        $validator = Validation::createValidator();

        $errors = [];

        foreach ($this->validationRulesReader->rules() as $item) {

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
