<?php

namespace Downsider\JsonSchema\Constraint;

/**
 * Required.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class Required extends Constraint
{

    protected $name = "required";

    protected $type = "object";

    public function __construct($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException("The value of a Required constraint must be an array");
        }

        // parse the array to make sure only string values are used
        foreach ($value as $key => $field) {
            if (!is_string($field)) {
                throw new \InvalidArgumentException("The element for key: $key was not a string");
            }
        }

        if (empty($value)) {
            throw new \InvalidArgumentException("The required field array did not contain any string values");
        }

        parent::__construct($value);
    }

    public function validate($data)
    {
        parent::validate($data);

        foreach ($this->value as $field) {
            if (!isset($data->{$field})) {
                $this->throwException("RequirementException", "A required field was missing", $field);
            }
        }
    }

}
