<?php

namespace Downsider\JsonSchema\Constraint;

/**
 * Maximum.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
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
