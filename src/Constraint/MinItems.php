<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * MinItems.php * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class MinItems extends Minimum
{

    protected $name = "minItems";

    protected $type = "array";

    protected $exceptionMessage = "The number of items was less than the minimum";

    protected $exclusive = null;

    /**
     * This is where traits would be useful
     *
     * {@inheritDoc}
     */
    public function checkValue($data)
    {
        $count = count($data);

        return parent::checkValue($count);

    }

}
