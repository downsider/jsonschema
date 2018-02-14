<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * MaxItems.php * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class MaxItems extends Maximum
{

    protected $name = "maxItems";

    protected $type = "array";

    protected $exceptionMessage = "The number of items was greater than the maximum";

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
