<?php

namespace Lexide\JsonSchema\Constraint;

use Lexide\JsonSchema\Schema;

/**
 * Items.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class Items extends SchemaConstraint
{

    protected $name = "items";

    protected $type = "array";

    protected $additionalItems = true;

    public function __construct($value)
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                if (!$item instanceof Schema) {
                    throw new \InvalidArgumentException("The elements of an Item constraint array must be one of the Schema classes");
                }
            }
        } elseif (!$value instanceof Schema) {
            throw new \InvalidArgumentException("Items constraints require the check value to be a Schema or an array of Schemas");
        }
        parent::__construct($value);
    }

    public function setAdditionalItems($additionalItems)
    {
        $this->additionalItems = (bool) $additionalItems;
    }

    public function validate($data)
    {
        parent::validate($data);

        if ($this->additionalItems === true || empty($this->value)) {
            return;
        }

        if (!is_array($data)) {
            $this->throwException("ValueException", "When checking Items, the data is required to be in array format");
        }

        // value is a schemata
        if ($this->value instanceof Schema) {
            // the draft 4 documentation is confused on this point
            // section 5.3.1.2 says this is an automatic success
            // section 8.2.3.1 says the data needs to validate against the value
            foreach ($data as $item) {
                $this->value->validate($item);
            }
            return;
        }

        // value is an array of schema
        foreach ($data as $key => $item) {
            if (!is_numeric($key)) {
                throw new \InvalidArgumentException("JSONSchema arrays must be numerically indexed");
            }
            if (isset($this->value[$key])) {
                /** @var Schema $value */
                $value = $this->value[$key];
                $value->validate($item);
            } elseif ($this->additionalItems instanceof Schema) {
                // the draft 4 documentation is confused on this point
                // section 5.3.1.2 says this is an automatic success
                // section 8.2.3.2 says the data needs to validate against the value of additionalItems
                $this->additionalItems->validate($item);
            } else {
                $this->throwException("ValueException", "The array contained more elements than are allowed");
            }
        }

    }

}
