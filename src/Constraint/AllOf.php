<?php

namespace Lexide\JsonSchema\Constraint;

use Lexide\JsonSchema\Schema;

/**
 * AllOf.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class AllOf extends SchemaConstraint
{

    protected $name = "allOf";

    public function __construct($value)
    {
        if (!is_array($value)) {
            throw new \InvalidArgumentException("The " . __CLASS__ . " constraint requires an array of values");
        }

        foreach ($value as $i => $schema) {
            if (!$schema instanceof Schema) {
                throw new \InvalidArgumentException("The element for key: $i was not a schema object");
            }
        }
        if (empty($value)) {
            throw new \InvalidArgumentException("The value array did not contain any schema objects");
        }
        parent::__construct($value);
    }

    public function validate($data)
    {
        foreach ($this->value as $schema) {
            /** @var Schema $schema */
            $schema->validate($data);
        }
    }

}
