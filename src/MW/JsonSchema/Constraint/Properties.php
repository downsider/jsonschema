<?php

namespace MW\JsonSchema\Constraint;

use MW\JsonSchema\Schema;

/**
 * Properties.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class Properties extends SchemaConstraint
{

    protected $name = "properties";

    protected $type = "object";

    protected $checkedProperties = array();

    public function __construct($value)
    {
        if (is_object($value)) {
            $value = (array) $value;
        }
        // validate an associative array
        if (!is_array($value)) {
            throw new \InvalidArgumentException(__CLASS__ . " constraints require their value to be an object or an associative array");
        }

        foreach ($value as $prop => $schemata) {
            if (is_numeric($prop)) {
                throw new \InvalidArgumentException(__CLASS__ . " constraints require their value to be an object or an associative array");
            }
            if (!$schemata instanceof Schema) {
                throw new \InvalidArgumentException(__CLASS__ . " constraints require each value element to be a Schema");
            }
        }
        parent::__construct($value);
    }

    public function getCheckedProperties()
    {
        return $this->checkedProperties;
    }

    public function validate($data)
    {
        parent::validate($data);

        $this->checkProperties($this->value, $data);

    }

    protected function checkProperties($kvp, $data)
    {
        foreach ($kvp as $name => $schema) {
            $this->checkedProperties[$name] = true;

            // we can validate more than one schemata per property, so wrap single schemata in an array
            if (!is_array($schema)) {
                $schema = array($schema);
            }

            // validate, if the property exists
            if (isset($data->{$name})) {
                foreach ($schema as $schemata) {
                    /** @var Schema $schemata */
                    $schemata->validate($data->{$name});
                }
            } else {}
        }
    }

}
