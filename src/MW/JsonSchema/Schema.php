<?php

namespace MW\JsonSchema;

use MW\JsonSchema\Exception\InvalidStateException;
use MW\JsonSchema\Constraint\Constraint;

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
        if (!isset($this->allowedConstraints[$constraint->getName()])){
            throw new InvalidStateException("Cannot add a {$constraint->getName()} Constraint to this Schema");
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
