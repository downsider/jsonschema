<?php

namespace MW\JsonSchema;

use MW\Utility\Helper\IdentifierHelper;

/**
 * ConstraintFactory.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class ConstraintFactory 
{

    /**
     * @var \Mw\Helper\IdentifierHelper
     */
    protected $idHelper;

    protected $baseNamespace = "";

    public function __construct(IdentifierHelper $idHelper)
    {
        $this->idHelper = $idHelper;
        $this->baseNamespace = __NAMESPACE__ . "\\Constraint\\";
    }

    public function create($name, $value, $schema) {
        $name = $this->idHelper->toStudlyCaps($name);
        $ref = new \ReflectionClass($this->baseNamespace . $name);
        $constraint = $ref->newInstance($value);
        // process dependant schema attributes
        //echo "name: $name<br>\n";
        switch ($name) {
            case "Minimum":
            case "Maximum":
                $exclusiveField = $this->idHelper->toCamelCase("{$name}Exclusive");
                if (isset ($schema->{$exclusiveField})) {
                    $constraint->setExclusive($schema->{$exclusiveField});
                }
                break;
        }
        return $constraint;
    }

    public function getConstraintName($name) {
        $name = $this->idHelper->toStudlyCaps($name);
        if (class_exists($this->baseNamespace . $name)) {
            return $name;
        }
        return false;
    }

    public function isSchemaConstraint($name)
    {
        return is_subclass_of(
            $this->baseNamespace . $name,
            $this->baseNamespace . "SchemaConstraint"
        );
    }

}
