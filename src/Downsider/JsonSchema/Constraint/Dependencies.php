<?php

namespace Downsider\JsonSchema\Constraint;

use Downsider\JsonSchema\Schema;

/**
 * Dependencies.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class Dependencies extends SchemaConstraint
{

    protected $name = "dependencies";

    protected $type = "object";

    public function __construct($value)
    {
        if (is_object($value)) {
            $value = (array) $value;
        }

        foreach ($value as $key => $dependency) {
            if (is_numeric($key)) {
                throw new \InvalidArgumentException("Dependencies constraints require the keys for the value elements to be non-numeric");
            }
            if (
                !(is_array($dependency) && count($dependency) > 0) ||
                !$dependency instanceof Schema
            ) {
                throw new \InvalidArgumentException("Dependencies constraints require the value elements to be non-empty arrays or Schemas");
            }
        }
        parent::__construct($value);
    }

    public function validate($data)
    {
        parent::validate($data);

        $dataVars = get_object_vars($data);
        foreach ($this->value as $name => $dependency) {
            if (isset($dataVars[$name])) {
                if ($dependency instanceof Schema) {
                    $dependency->validate($data);
                } else {
                    foreach ($dependency as $dependantProperty) {
                        if (!isset($datVars[$dependantProperty])) {
                            $this->throwException(
                                "RequirementException",
                                "The data is dependant on a non existent property",
                                $dependantProperty
                            );
                        }
                    }
                }
            }
        }
    }

}
