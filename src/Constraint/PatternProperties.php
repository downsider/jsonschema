<?php

namespace Lexide\JsonSchema\Constraint;

/**
 * PatternProperties.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */ 
class PatternProperties extends Properties
{

    protected $name = "patternProperties";

    protected $type = "object";

    protected $originalValue;

    public function __construct($value)
    {
        parent::__construct($value);

        $this->originalValue = $this->value;
    }

    public function validate($data)
    {
        // gather keys to check
        $checkArray = array();
        $dataVars = get_object_vars($data);
        foreach ($this->originalValue as $regex => $schemata) {
            $regex = "/". str_replace("/", "\\/", $regex) . "/";
            foreach ($dataVars as $name => $value) {
                if (preg_match($regex, $name)) {
                    if (!isset($checkArray[$name])) {
                        $checkArray[$name] = array();
                    }
                    $checkArray[$name][] = $schemata;
                }
            }
        }
        $this->value = $checkArray;

        parent::validate($data);
    }

}
