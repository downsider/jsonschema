<?php

namespace Downsider\JsonSchema;

use Downsider\JsonSchema\Constraint\Constraint;
use Downsider\JsonSchema\Constraint\Type;

/**
 * SchemaFactory.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */ 
class SchemaFactory
{

    protected $constraintMap = array(
        "string" => array(
            "maxLength",
            "minLength",
            "pattern"
        ),
        "number" => array(
            "maximum",
            "minimum",
            "multipleOf"
        ),
        "integer" => array(
            "maximum",
            "minimum",
            "multipleOf"
        ),
        "array" => array(
            "items",
            "maxItems",
            "minItems",
            "uniqueItems"
        ),
        "object" => array(
            "properties",
            "patternProperties",
            "additionalProperties",
            "maxProperties",
            "minProperties",
            "dependencies",
            "required"
        )
    );

    /**
     * This is the list of Constraints available to all Schema types
     * It is 1 indexed, rather then zero indexed
     *
     * @var array
     */
    protected $defaultConstraints = array(
        1 => "allOf",
        "anyOf",
        "oneOf",
        "not",
        "enum",
        "type"
    );

    protected $constraintFactory;

    /**
     * @var IdentifierHelper
     */
    protected $idHelper;

    public function __construct(ConstraintFactory $factory, IdentifierHelper $idHelper)
    {
        $this->constraintFactory = $factory;
        $this->idHelper = $idHelper;
    }

    /**
     * Todo: Add schema caching
     *
     * @param object $schema
     *
     * @return object
     * @throws \InvalidArgumentException
     */
    public function create($schema)
    {
        if (!is_object($schema)) {
            throw new \InvalidArgumentException("Cannot create a new Type. The value passed was not an object.");
        }

        $schemaType = $this->getTypeFromSchema($schema);

        // flip array for use with isset()
        $allowedConstraints = array_flip($this->defaultConstraints);
        if (isset($this->constraintMap[$schemaType])) {
            foreach ($this->constraintMap[$schemaType] as $constraint) {
                $allowedConstraints[$constraint] = true;
            }
        }

        // create the type instance
        $ref = new \ReflectionClass(__NAMESPACE__ . "\\Schema");
        $type = $ref->newInstance($allowedConstraints);

        //echo "type:<pre>" . print_r($type, true) . "</pre>\n";

        $schemaVars = get_object_vars($schema);

        foreach ($schemaVars as $property => $value) {

            $constraintName = $this->constraintFactory->getConstraintName($property);
            //echo "property: $property, constraint: $constraintName<br>\n";
            // skip if this is not a constraint
            if ($constraintName !== false) {
                // we use $newValue to prevent referencing issues if $value points to an object
                if ((is_array($value) || is_object($value)) && $this->constraintFactory->isSchemaConstraint($constraintName)) {
                    // this line prevents any object that $value points to from being altered here
                    $newValue = is_object($value)? new \stdClass(): array();
                    foreach ($value as $i => $element) {
                        if (!is_object($element)) {
                            throw new \InvalidArgumentException("SchemaConstraints require the elements of an array value to be objects");
                        }
                        is_array($value)
                            ? $newValue[$i] = $this->create($element)
                            : $newValue->{$i} = $this->create($element);
                    }
                } elseif (is_object($value)) {
                    $newValue = $this->create($value);
                } else {
                    // primitive value or (array and not a schema constraint)
                    $newValue = $value;
                }
                $constraint = $this->constraintFactory->create($constraintName, $newValue, $schema);

                $type->addConstraint($constraint);
            }
        }

        return $type;

        //return $ref->newInstance($this, $this->constraintFactory, $value, $allowedConstraints);

    }

    protected function getTypeFromSchema($schema)
    {
        // check for explicit type
        if (isset($schema->type)) {
            // this will validate the type, but we don't need the object
            $type = new Type($schema->type);
            unset($type);
            return $schema->type;
        }
        //echo "inferring type:<br>\n";
        // infer the type by the constraints
        foreach ($this->constraintMap as $type => $constraintList) {
            //echo " - $type?";
            $constraintList = array_flip($constraintList);
            foreach ($schema as $constraint => $value) {
                if (isset($constraintList[$constraint])) {
                    //echo " yes!<br><br>\n";
                    return $type;
                }
            }
            //echo " nope<br>";
        }
        //echo "<br>\n";
        return Constraint::ANY_TYPE;
    }

}
