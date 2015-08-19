<?php

namespace Downsider\JsonSchema\Constraint;

use Downsider\JsonSchema\Exception\ValidationException;
use Downsider\JsonSchema\Schema;

/**
 * AnyOf.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class AnyOf extends AllOf
{

    protected $name = "anyOf";

    public function validate($data)
    {

        foreach ($this->value as $schema) {
            /** @var Schema $schema */
            try {
                $schema->validate($data);
                return;
            } catch (ValidationException $e) {
                // move onto the next schemata
            }
        }
        // if we reach here then none of the schema validated
        $this->throwException("RequirementException", "None of the sub-schemata validated when one was required to");
    }

}
