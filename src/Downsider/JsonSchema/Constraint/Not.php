<?php

namespace Downsider\JsonSchema\Constraint;

use Downsider\JsonSchema\Exception\ValidationException;
use Downsider\JsonSchema\Schema;

/**
 * Not.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
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
