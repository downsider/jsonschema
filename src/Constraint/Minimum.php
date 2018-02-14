<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * Minimum.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class Minimum extends Constraint
{

    protected $name = "minimum";

    protected $type = "number";

    protected $exceptionMessage = "Value was not greater than the minimum";

    protected $exclusive = false;

    public function setExclusive($exclusive)
    {
        $this->exclusive = (bool) $exclusive;
    }

    public function validate($data)
    {
        parent::validate($data);

        if (!$this->checkValue($data)) {
            $exclusiveStr = "";
            if (!is_null($this->exclusive)) {
                $exclusiveStr = ($this->exclusive === true)
                    ? " (exclusive)"
                    : " (inclusive)";
            }
            $this->throwException(
                "ValueException",
                $this->exceptionMessage . $exclusiveStr,
                $data
            );
        }
    }

    protected function checkValue($data)
    {
        return !($data < $this->value || ($this->exclusive && $data == $this->value));
    }

}
