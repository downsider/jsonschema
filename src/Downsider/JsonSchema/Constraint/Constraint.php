<?php

namespace Downsider\JsonSchema\Constraint;

use Downsider\JsonSchema\Exception\InvalidStateException;
use Downsider\JsonSchema\Exception\ValidationException;

/**
 * Constraint.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
abstract class Constraint
{

    const ANY_TYPE = "any";

    protected $value;

    protected $name;

    protected $type = self::ANY_TYPE;

    protected $typeFunctionMap = array(
        "integer" => "is_int",
        "number" => "is_numeric",
        "string" => "is_string",
        "boolean" => "is_bool",
        "array" => "is_array",
        "object" => "is_object",
        "null" => "is_null"
    );

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getType()
    {
        return $this->type;
    }

    public function validate($data)
    {
        if ($this->type != self::ANY_TYPE) {
            if (!isset($this->typeFunctionMap[$this->type])) {
                throw new InvalidStateException("The type '{$this->type}' was not found in the function map");
            }
            $function = $this->typeFunctionMap[$this->type];
            if (!call_user_func($function, $data)) {
                $this->throwException("TypeException", "The value passed to the {$this->name} constraint was not of the correct type ({$this->type})", $data . " (" . gettype($data) . ")");
            }
        }
    }

    protected function throwException($className, $message, $data = null)
    {
        // add namespace if there isn't one
        if (strpos($className, '\\') === false) {
            $className = "\\Downsider\\JsonSchema\\Exception\\$className";
        }
        $ref = new \ReflectionClass($className);
        $e = $ref->newInstanceArgs(array($message));
        if ($e instanceof ValidationException) {
            $e->setData($data);
            $e->setValue($this->value);
        }
        throw $e;
    }

    public function getName()
    {
        return $this->name;
    }

}
