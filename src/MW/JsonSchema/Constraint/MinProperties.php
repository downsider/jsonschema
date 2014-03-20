<?php

namespace MW\JsonSchema\Constraint;

/**
 * MinProperties
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class MinProperties extends Minimum
{

    protected $name = "minProperties";

    protected $type = "object";

    protected $exceptionMessage = "The number of properties was less than the minimum";

    protected $exclusive = null;

    /**
     * This is where traits would be useful
     *
     * {@inheritDoc}
     */
    public function checkValue($data)
    {
        $count = count(get_object_vars($data));

        return parent::checkValue($count);

    }

}
