<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * Enum @author: Danny Smart <downsider84@hotmail.com>
 */ 
class Enum extends Constraint
{

    protected $name = "enum";

    public function __construct($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException("Enum requires the check value to be an array");
        }
        $this->value = $value;
    }

    public function validate($data)
    {
        if (!in_array($data, $this->value)) {
            $this->throwException("ValueException", "Invalid value for enumation object");
        }
    }

}
