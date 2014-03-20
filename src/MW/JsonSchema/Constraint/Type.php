<?php

namespace MW\JsonSchema\Constraint;

/**
 * Type.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
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
