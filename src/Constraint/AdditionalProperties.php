<?php

namespace Lexide\JsonSchema\Constraint;

use Lexide\JsonSchema\Schema;

/**
 * AdditionalProperties.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class AdditionalProperties extends SchemaConstraint
{

    protected $name = "additionalProperties";

    protected $type = "object";

    protected $checkedProperties = array();

    public function __construct($value)
    {
        if (!is_bool($value) && !$value instanceof Schema) {
            throw new \InvalidArgumentException("AdditionalProperties constraints require the value to be a boolean or a Schema");
        }
        parent::__construct($value);
    }

    public function setCheckedProperties($properties)
    {
        $this->checkedProperties = $properties;
    }

    public function validate($data)
    {
        parent::validate($data);

        if ($this->value === true) {
            return;
        }

        // only process properties if they have not been checked already;
        $dataVars = get_object_vars($data);
        foreach ($dataVars as $name => $value) {
            if (!isset($this->checkedProperties[$name])) {
                if ($this->value instanceof Schema) {
                    // draft 4 documentation is confused at this point
                    // 5.4.4.2 says that this is an automatic success
                    // 8.3.3.4 says that the data needs to validate against the value schemata
                    $this->value->validate($value);
                } else {
                    // value is boolean false
                    $this->throwException(
                        "RequirementException",
                        "A property was present in the data that was not allowed by the schema",
                        $name
                    );
                }
            }
        }
        //*/
    }

}
