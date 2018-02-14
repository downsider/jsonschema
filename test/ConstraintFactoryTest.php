<?php
/**
 * ConstraintFactoryTest.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */

namespace Lexide\JsonSchema\Tests;


use Lexide\JsonSchema\ConstraintFactory;

class ConstraintFactoryTest extends \PHPUnit_Framework_TestCase {

    protected $idHelper;

    public function setUp()
    {
        $this->idHelper = $this->getMock("\\Lexide\\JsonSchema\\IdentifierHelper");
        $this->idHelper->expects($this->any())->method("toStudlyCaps")->will($this->returnArgument(0));
        $this->idHelper->expects($this->any())->method("toCamelCase")->will($this->returnValueMap(array(array("MinimumExclusive", "minimumExclusive"), array("MaximumExclusive", "maximumExclusive"))));
    }

    public function testGetConstraintName()
    {
        $constraint = "Constraint";
        $factory = new ConstraintFactory($this->idHelper);

        $this->assertEquals($constraint, $factory->getConstraintName($constraint));

        $this->assertFalse($factory->getConstraintName($constraint . "2"));

    }

    public function testIsSchemaConstraint()
    {
        $constraint = "AllOf";
        $factory = new ConstraintFactory($this->idHelper);

        $this->assertTrue($factory->isSchemaConstraint($constraint));

        $this->assertFalse($factory->isSchemaConstraint("SchemaConstraint"));
    }

    public function testCreate()
    {
        $constraint = "MaxLength";
        $value = 2;
        $schema = new \stdClass();
        $schema->maximumExclusive = true;

        $factory = new ConstraintFactory($this->idHelper);
        $factoryClass = get_class($factory);
        $baseNamespace = substr($factoryClass, 0, strrpos($factoryClass, "\\") + 1) . "Constraint\\";

        $this->assertInstanceOf($baseNamespace . $constraint, $factory->create($constraint, $value, $schema));

        $maximum = $factory->create("Maximum", $value, $schema);
        $this->assertAttributeEquals(true, "exclusive", $maximum);

        try {
            $factory->create("Constraint", "value", $schema);
            $this->fail("We should not be able to create a constraint from the abstract base class");
        } catch (\ReflectionException $e) {
            // passed the test
        }

    }

}
