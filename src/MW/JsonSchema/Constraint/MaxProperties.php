<?php

namespace MW\JsonSchema\Constraint;

/**
 * MaxProperties
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class MaxProperties extends Maximum
{

    protected $name = "maxProperties";

    protected $type = "object";

    protected $exceptionMessage = "The number of properties was greater than the maximum";

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
