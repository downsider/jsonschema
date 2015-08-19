<?php

namespace Downsider\JsonSchema\Constraint;

/**
 * Pattern.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class Pattern extends Constraint
{

    protected $name = "pattern";

    protected $type = "string";

    protected $format = null;

    public function validate($data)
    {
        parent::validate($data);

        if (preg_match("/".str_replace("/", "\\/", $this->value)."/", $data) === 0) {
            $this->throwException(
                "ValueException",
                "The data did not match the pattern " . (empty($this->format)? "($this->value)": "for {$this->format}"),
                $data
            );
        }
    }

}