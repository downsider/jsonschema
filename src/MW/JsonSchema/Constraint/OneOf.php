<?php

namespace MW\JsonSchema\Constraint;

use MW\JsonSchema\Exception\ValidationException;
use MW\JsonSchema\Schema;

/**
 * OneOf.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class OneOf extends AllOf
{

    protected $name = "oneOf";

    public function validate($data)
    {
        $validCount = 0;
        foreach ($this->value as $schemata) {
            /** @var Schema $schemata */
            try {
                $schemata->validate($data);
                ++$validCount;
            } catch (ValidationException $e) {
                // move onto the next schemata
            }
        }
        if ($validCount !== 1) {
            $this->throwException("RequirementException", "The data matched $validCount schemata when exactly one was required to match");
        }
    }

}
