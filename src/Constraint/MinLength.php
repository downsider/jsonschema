<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * MinLength
 *
 * @author: Danny Smart <downsider84@hotmail.com>
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
