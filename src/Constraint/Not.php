<?php

namespace Lexide\JsonSchema\Constraint;

use Lexide\JsonSchema\Exception\ValidationException;
use Lexide\JsonSchema\Schema;

/**
 * Not.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class Not extends SchemaConstraint
{

    protected $name = "not";

    public function __construct($value)
    {
        if (!$value instanceof Schema) {
            throw new \InvalidArgumentException("Not constraints require the value to be a Schema");
        }
        parent::__construct($value);
    }

    public function validate($data)
    {
        try {
            $this->value->validate($data);
        } catch (ValidationException $e) {
            return;
        }
        $this->throwException("RequirementException", "The data did not fail to validate against the value of a Not constraint");
    }

}
