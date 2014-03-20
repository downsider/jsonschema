<?php
/**
 * SchemaFactoryTest.php
 *
 * @author: Danny Smart <danny.smart@my-wardrobe.com>
 */

namespace MW\JsonSchema\Tests;


use MW\JsonSchema\SchemaFactory;

class SchemaFactoryTest extends \PHPUnit_Framework_TestCase {

    protected $idHelper;

    protected $constraintFactory;

    protected $constraint;

    public function setUp()
    {
        $this->idHelper = $this->getMock("\\MW\\Utility\\Helper\\IdentifierHelper");
        $this->constraintFactory = $this->getMockBuilder("\\Mw\\JsonSchema\\ConstraintFactory")
            ->disableOriginalConstructor()
            ->getMock();
        $this->constraint = $this->getMockBuilder("\\Mw\\JsonSchema\\Constraint\\Constraint")
                      ->disableOriginalConstructor()
                      ->getMockForAbstractClass();
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
        $this->constraintFactory->expects($this->any())->method("getConstraintName")->will($this->returnArgument(0));
        $this->constraint->expects($this->any())->method("getName")->will($this->returnValue("maximum"));

        $schema = (object) array(
            "Type" => "number"
        );

        $result = $factory->create($schema);
        $this->assertAttributeEquals(array(), "availableConstraints", $result);


    }



}
