<?php

namespace Downsider\JsonSchema;

use Downsider\JsonSchema\Exception\InvalidStateException;
use Downsider\JsonSchema\Constraint\Constraint;

/**
 * Schema.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class Schema
{

    protected $allowedConstraints = array();

    protected $constraints = array();

    public function __construct(array $allowedConstraints = array())
    {
        $this->allowedConstraints = $allowedConstraints;
    }

    public function addConstraint(Constraint $constraint)
    {
        $name = $constraint->getName();
        if (!isset($this->allowedConstraints[$name])){
            throw new InvalidStateException("Cannot add a {$name} Constraint to this Schema");
        }
        $this->constraints[] = $constraint;
    }

    public function validate($data)
    {
        //convert associative arrays into objects
        if (is_array($data) && !empty($data)) {
            $keys = array_keys($data);
            if (is_string($keys[0])) {
                $data = (object) $data;
            }
        }

        foreach ($this->constraints as $constraint) {
            /** @var Constraint $constraint */
            $constraint->validate($data);
        }
    }

}
