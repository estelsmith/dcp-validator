<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\Result;
use DCP\Form\Validation\Exception;

class ResultTest extends \PHPUnit_Framework_TestCase
{
    public function testImplementsProperInterface()
    {
        $this->assertInstanceOf('Dcp\Form\Validation\ResultInterface', new Result());
    }

    public function testAddErrorThrowsExceptionWhenErrorArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'error must be a string';
        $actualMessage = null;

        try {
            $instance = new Result();
            $instance->addError(['not a string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testAddErrorThrowsExceptionWhenFieldArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'field must be a string';
        $actualMessage = null;

        try {
            $instance = new Result();
            $instance->addError('string', ['not a string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testAddAndGetErrors()
    {
        $expectedErrors = [
            'test_field' => 'test_error',
            'another_field' => 'another_error'
        ];

        $actualErrors = null;

        $instance = new Result();
        $instance->addError('test_error', 'test_field');
        $instance->addError('another_error', 'another_field');

        $actualErrors = $instance->getErrors();

        $this->assertEquals($expectedErrors, $actualErrors);
    }

    public function testIsValidReturnsTrueWhenNoErrorsHaveBeenAdded()
    {
        $instance = new Result();

        $expectedResult = true;
        $actualResult = $instance->isValid();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testIsValidReturnsFalseWhenErrorsHaveBeenAdded()
    {
        $instance = new Result();
        $instance->addError('stuff');

        $expectedResult = false;
        $actualResult = $instance->isValid();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetErrorThrowsExceptionWhenFieldArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'field must be a string';
        $actualMessage = null;

        try {
            $instance = new Result();
            $instance->getError(['not a string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testGetErrorReturnsFalseWhenFieldArgumentHasNoErrors()
    {
        $instance = new Result();

        $expectedResult = false;
        $actualResult = $instance->getError('does_not_exist');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetErrorReturnsErrorWhenFieldArgumentHasAnError()
    {
        $instance = new Result();
        $instance->addError('test_error', 'test_field');

        $expectedResult = 'test_error';
        $actualResult = $instance->getError('test_field');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testFieldHasErrorThrowsExceptionWhenFieldArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'field must be a string';
        $actualMessage = null;

        try {
            $instance = new Result();
            $instance->fieldHasError(['not a string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testFieldHasErrorReturnsFalseWhenFieldArgumentHasNoErrors()
    {
        $instance = new Result();

        $expectedResult = false;
        $actualResult = $instance->fieldHasError('does_not_exist');

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testFieldHasErrorReturnsTrueWhenFieldArgumentHasAnError()
    {
        $instance = new Result();
        $instance->addError('error_message', 'test_field');

        $expectedResult = true;
        $actualResult = $instance->fieldHasError('test_field');

        $this->assertEquals($expectedResult, $actualResult);
    }
}