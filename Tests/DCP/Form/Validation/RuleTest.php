<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\Rule;
use DCP\Form\Validation\Exception;

class RuleTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectImplementsProperInterface()
    {
        $instance = new Rule();

        $this->assertInstanceOf('DCP\Form\Validation\RuleInterface', $instance);
    }

    public function testAddFilterThrowsExceptionWhenFilterArgumentIsNotCallable()
    {
        $gotException = false;
        $expectedMessage = 'filter must be callable';
        $actualMessage = null;

        try {
            $instance = new Rule();
            $instance->addFilter('not_callable');
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testCanAddAndGetFilters()
    {
        $expectedFilters = [
            function ($item) { return true; },
            function ($item) { return false; }
        ];

        $instance = new Rule();

        foreach ($expectedFilters as $filter) {
            $instance->addFilter($filter);
        }

        $actualFilters = $instance->getFilters();

        foreach ($actualFilters as $index => $filter) {
            $this->assertSame($expectedFilters[$index], $filter);
        }
    }

    public function testSetFieldNameThrowsExceptionWhenFieldArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'fieldName must be a string';
        $actualMessage = null;

        try {
            $instance = new Rule();
            $instance->setFieldName(['not as string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testCanSetAndGetFieldName()
    {
        $expectedResult = 'test_field_name';
        $actualResult = null;

        $instance = new Rule();
        $instance->setFieldName($expectedResult);
        $actualResult = $instance->getFieldName();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testGetMessageReturnsDefaultMessageWhenNoMessageIsSet()
    {
        $expectedResult = 'This field failed validation';
        $actualResult = null;

        $instance = new Rule();
        $actualResult = $instance->getMessage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testSetMessageThrowsExceptionWhenMessageArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'message must be a string';
        $actualMessage = null;

        try {
            $instance = new Rule();
            $instance->setMessage(['not a string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testCanSetAndGetMessage()
    {
        $expectedResult = 'test_message';
        $actualResult = null;

        $instance = new Rule();
        $instance->setMessage($expectedResult);
        $actualResult = $instance->getMessage();

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testAddConstraintThrowsExceptionWhenConstraintArgumentIsNotCallable()
    {
        $gotException = false;
        $expectedMessage = 'constraint must be callable';
        $actualMessage = null;

        try {
            $instance = new Rule();
            $instance->addConstraint('not_callable');
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testCanAddAndGetConstraints()
    {
        $expectedConstraints = [
            function ($item) { return true; },
            function ($item) { return false; }
        ];

        $instance = new Rule();

        foreach ($expectedConstraints as $constraint) {
            $instance->addConstraint($constraint);
        }

        $actualConstraints = $instance->getConstraints();

        foreach ($actualConstraints as $index => $constraint) {
            $this->assertSame($expectedConstraints[$index], $constraint);
        }
    }

    public function testAddValidationGroupThrowsExceptionWhenGroupArgumentIsNotAString()
    {
        $gotException = false;
        $expectedMessage = 'validationGroup must be a string';
        $actualMessage = null;

        try {
            $instance = new Rule();
            $instance->addValidationGroup(['not a string']);
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testCanAddAndGetValidationGroups()
    {
        $expectedValidationGroups = [
            'group 1',
            'group 2'
        ];

        $instance = new Rule();

        foreach ($expectedValidationGroups as $validationGroup) {
            $instance->addValidationGroup($validationGroup);
        }

        $actualValidationGroups = $instance->getValidationGroups();

        foreach ($actualValidationGroups as $index => $validationGroup) {
            $this->assertSame($expectedValidationGroups[$index], $validationGroup);
        }
    }

    public function testAddPrerequisiteThrowsExceptionWhenPrerequisiteArgumentIsNotCallable()
    {
        $gotException = false;
        $expectedMessage = 'prerequisite must be callable';
        $actualMessage = null;

        try {
            $instance = new Rule();
            $instance->addPrerequisite('not_callable');
        } catch (Exception\InvalidArgumentException $e) {
            $gotException = true;
            $actualMessage = $e->getMessage();
        }

        $this->assertTrue($gotException);
        $this->assertEquals($expectedMessage, $actualMessage);
    }

    public function testCanAddAndGetPrerequisites()
    {
        $expectedPrerequisites = [
            function () { return true; },
            function () { return false; }
        ];

        $instance = new Rule();

        foreach ($expectedPrerequisites as $prerequisite) {
            $instance->addPrerequisite($prerequisite);
        }

        $actualPrerequisites = $instance->getPrerequisites();

        foreach ($actualPrerequisites as $index => $prerequisite) {
            $this->assertSame($expectedPrerequisites[$index], $prerequisite);
        }
    }
}