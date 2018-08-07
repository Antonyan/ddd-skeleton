<?php

namespace Infrastructure\Annotations;

/**
 * @Annotation
 */
final class Validation
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var integer
     */
    public $minLength = 0;

    /**
     * @var integer
     */
    public $maxLength = 0;

    /**
     * @var boolean
     */
    public $required;
}