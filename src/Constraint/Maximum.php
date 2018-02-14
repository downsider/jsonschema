<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * Maximum.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class Maximum extends Minimum
{

    protected $name = "maximum";

    protected $type = "number";

    protected $exceptionMessage = "Value was not less than the maximum";

    protected function checkValue($data)
    {
        return !($data > $this->value || ($this->exclusive && $data == $this->value));
    }

}
