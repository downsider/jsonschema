<?php

namespace Downsider\JsonSchema\Constraint;

/**
 * MultipleOf.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class MultipleOf extends Constraint
{

    const EPSILON = 0.000001;

    protected $name = "multipleOf";

    protected $type = "number";

    public function __construct($value)
    {
        if (empty($value)) {
            $this->throwException("TypeException", "The value for a {$this->name} Constraint must not be zero");
        }
        parent::__construct($value);
    }

    public function validate($data)
    {
        parent::validate($data);
        $float = $data / $this->value;
        $int = (int) $float;
        /**
         * we have to the epsilon method of comparison because floating point numbers are imprecise
         * and comparing them will give unexpected results.
         * I won't implement a relative Epsilon or ULP method here as that would be overkill, but it's
         * something to consider if we start finding this type of bug
         *
         * @link http://www.php.net/manual/en/language.types.float.php
         * @link http://randomascii.wordpress.com/2012/02/25/comparing-floating-point-numbers-2012-edition/
         */
        $epsilon = abs($float - $int);
        if (!($epsilon <= self::EPSILON || 1 - $epsilon <= self::EPSILON)) {
            $this->throwException("ValueException", "The data is not a multiple of the value (epsilon = $epsilon)", $data);
        }
    }

}
