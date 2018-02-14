<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * Type.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class Type extends Constraint
{

    protected $name = "type";

    public function __construct($value)
    {
        if (!isset($this->typeFunctionMap[$value])) {
            throw new \InvalidArgumentException("Unrecognised check value for a Type constraint");
        }
        $this->type = $value;
    }

}
