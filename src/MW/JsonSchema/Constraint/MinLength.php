<?php

namespace MW\JsonSchema\Constraint;

/**
 * MinLength
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class MinLength extends Minimum
{

    protected $name = "minLength";

    protected $type = "string";

    protected $exclusive = null;

    protected $exceptionMessage = "The data was shorter than the minimum length";

    /**
     * This is where traits would be useful
     *
     * {@inheritDoc}
     */
    public function checkValue($data)
    {
        $length = strlen($data);

        return parent::checkValue($length);

    }

}
