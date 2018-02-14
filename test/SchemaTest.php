<?php
/**
 * SchemaTest.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */

namespace Lexide\JsonSchema\Tests;


use Lexide\JsonSchema\Exception\InvalidStateException;
use Lexide\JsonSchema\Schema;

class SchemaTest extends \PHPUnit_Framework_TestCase {

    public function testAddingConstraints()
    {
        $constraint = $this->getMockBuilder("\\Lexide\\JsonSchema\\Constraint\\Constraint")
            ->disableOriginalConstructor()
            ->getMock();
        $constraint->expects($this->any())->method("getName")->will($this->onConsecutiveCalls("maximum", "minimum"));

        $schema = new Schema(array("maximum" => true));

        $schema->addConstraint($constraint);
        $this->assertAttributeEquals(array($constraint), "constraints", $schema);

        try {
            $schema->addConstraint($constraint);
            $this->fail("Should not be able to add a constraint when it is not in the available constraints list");
        } catch (InvalidStateException $e) {
            // passed the test
        }

    }

    /**
     * @dataProvider validationTestData
     *
     * @param $data
     * @param $processedData
     */
    public function testValidation($data, $processedData)
    {
        $constraint = $this->getMockBuilder("\\Lexide\\JsonSchema\\Constraint\\Constraint")
            ->disableOriginalConstructor()
            ->getMock();
        $constraint->expects($this->any())->method("getName")->will($this->returnValue("maximum"));
        $constraint->expects($this->once())->method("validate")->with($processedData);

        $schema = new Schema(array("maximum" => true));

        $schema->addConstraint($constraint);

        $schema->validate($data);

    }

    public function validationTestData()
    {
        $assoc = array(
            "property1" => "one",
            "property2" => "two",
            "property3" => "three",
        );

        $object = (object) $assoc;

        $numeric = array(
            "one",
            "two",
            "three"
        );

        return array(
            array($object, $object), // objects are used by default
            array($assoc, $object), // assoc arrays are converted to objects
            array($numeric, $numeric) // numeric arrays are untouched
        );
    }

}
