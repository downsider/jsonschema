<?php
/**
 * SchemaFactoryTest.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */

namespace Lexide\JsonSchema\Tests;


use Lexide\JsonSchema\SchemaFactory;

class SchemaFactoryTest extends \PHPUnit_Framework_TestCase {

    protected $idHelper;

    protected $constraintFactory;

    protected $constraint;

    public function setUp()
    {
        $this->idHelper = $this->getMock("\\Lexide\\JsonSchema\\IdentifierHelper");
        $this->constraintFactory = $this->getMockBuilder("\\Lexide\\JsonSchema\\ConstraintFactory")
            ->disableOriginalConstructor()
            ->getMock();
        $this->constraint = $this->getMockBuilder("\\Lexide\\JsonSchema\\Constraint\\Constraint")
                      ->disableOriginalConstructor()
                      ->getMock();
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testBadSchema()
    {
        $factory = new SchemaFactory($this->constraintFactory, $this->idHelper);

        $factory->create(false);
    }

    public function testType()
    {
        $factory = new SchemaFactory($this->constraintFactory, $this->idHelper);
        $this->constraint->expects($this->any())->method("getName")->will($this->returnValue("type"));
        $this->constraintFactory->expects($this->any())->method("getConstraintName")->will($this->returnValue("Type"));
        $this->constraintFactory->expects($this->any())->method("create")->will($this->returnValue($this->constraint));


        $schema = (object) array(
            "type" => "number"
        );

        $result = $factory->create($schema);
        $this->assertAttributeEquals(array($this->constraint), "constraints", $result);


    }



}
