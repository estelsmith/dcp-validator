<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\FieldReference;
use DCP\Form\Validation\Exception;

class FieldReferenceTest extends \PHPUnit_Framework_TestCase
{
    public function testCannotConstructWithNonStringConstructorArgument()
    {
        $gotException = false;
        $expectedMessage = 'field must be a string';
        $actualMessage = '';

        try {
            new FieldReference([]);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testCanConstructWithStringConstructorArgument()
    {
        $instance = new FieldReference('test');
        $this->assertInstanceOf('DCP\Form\Validation\FieldReference', $instance);
    }

    public function testConvertToString()
    {
        $expectedValue = 'test';

        $instance = new FieldReference($expectedValue);

        $this->assertEquals($expectedValue, $instance->__toString());
    }
}