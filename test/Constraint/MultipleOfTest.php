<?php
/**
 * MultipleOfTest.php
 *
 * @author: Danny Smart <downsider84@hotmail.com>
 */

namespace Lexide\JsonSchema\Tests\Constraint;


use Lexide\JsonSchema\Constraint\MultipleOf;
use Lexide\JsonSchema\Exception\ValueException;

class MultipleOfTest extends \PHPUnit_Framework_TestCase {


    /**
     * @dataProvider multipleValues
     *
     * @param $value
     * @param $multiple
     * @param $result
     */
    public function testMultiples($value, $multiple, $result)
    {
        $multipleOf = new MultipleOf($multiple);

        try {
            $multipleOf->validate($value);
            $this->assertTrue($result, "This test should pass");
        } catch (ValueException $e) {
            $this->assertFalse($result, "This test should fail");
        }
    }

    public function multipleValues()
    {
        return array(
            array(1, 1, true),
            array(2, 1, true),
            array(1, 2, false),
            array(1, 0.5, true),
            array(2.5, 0.01, true),
            array("2.5", 0.01, true),
            array(5/2, 0.01, true), // floating point number test (pass)
            array(5/2, 1.2, false) // floating point number test (fail)
        );
    }

}
