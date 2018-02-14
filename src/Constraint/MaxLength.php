<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * MaxLength.php * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class MaxLength extends Maximum
{

    protected $name = "maxLength";

    protected $type = "string";

    protected $exceptionMessage = "The data was longer than the maximum length";

    protected $exclusive = null;

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
