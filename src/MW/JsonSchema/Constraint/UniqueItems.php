<?php

namespace MW\JsonSchema\Constraint;

/**
 * UniqueItems.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class UniqueItems extends Constraint
{

    protected $name = "uniqueItems";

    protected $type = "array";

    public function __construct($value)
    {
        if (!is_bool($value)) {
            throw new \InvalidArgumentException("The value of a UniqueItems constraint must be a boolean");
        }

        parent::__construct($value);
    }

    public function validate($data)
    {
        parent::validate($data);

        $copy = $data;
        foreach ($data as $key => $value) {
            foreach ($copy as $key2 => $check) {
                if ($value === $check || (is_object($value) && $value == $check)) {
                    $this->throwException("ValueException", "The data contained values that aren't unique");
                }
            }
            // no need to check this key against any others
            // remove it from the check array to improve performance
            unset($copy[$key]);
        }
    }

}
