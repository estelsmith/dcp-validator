<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\Constraints;
use DCP\Form\Validation\FieldReference;

class ConstraintsTest extends \PHPUnit_Framework_TestCase
{
    public function testNotBlank()
    {
        $constraint = Constraints::notBlank();

        $this->assertInstanceOf('\Closure', $constraint);

        $tests = [
            [null, false],
            ['', false],
            ['something', true],
            [0, true],
            ['0', true]
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $constraint($test[0]));
        }
    }

    public function testFormatEmail()
    {
        $constraint = Constraints::formatEmail();

        $this->assertInstanceOf('\Closure', $constraint);

        $tests = [
            [null, null],
            ['', null],
            [0, false],
            ['0', false],
            ['test', false],
            ['test@', false],
            ['test@test', false],
            ['test@test.com', true],
            ['estel.smith@gmail.com', true],
            ['spammer.guy+really@russian.place.ru.biz', true]
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $constraint($test[0]));
        }
    }

    public function testFormatDigits()
    {
        $constraint = Constraints::formatDigits();

        $this->assertInstanceOf('\Closure', $constraint);

        $tests = [
            [null, null],
            ['', null],
            ['test', false],
            ['3.14', false],
            [0, true],
            ['0', true],
            [1337, true]
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $constraint($test[0]));
        }
    }

    public function testFormatNumeric()
    {
        $constraint = Constraints::formatNumeric();

        $this->assertInstanceOf('\Closure', $constraint);

        $tests = [
            [null, null],
            ['', null],
            ['test', false],
            [0, true],
            ['0', true],
            [1337, true],
            ['1337', true],
            [3.14, true],
            ['3.14', true],
            [1.01e+6, true],
            ['1.01e+6', true]
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $constraint($test[0]));
        }
    }

    public function testFormatRegex()
    {
        $constraint = Constraints::formatRegex('/test/');

        $this->assertInstanceOf('\Closure', $constraint);

        $tests = [
            [null, null],
            ['', null],
            [0, false],
            ['0', false],
            ['tset', false],
            ['test', true],
            ['this is a test', true]
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $constraint($test[0]));
        }
    }

    public function testIsBlank()
    {
        $constraint = Constraints::isBlank();

        $this->assertInstanceOf('\Closure', $constraint);

        $tests = [
            [0, false],
            ['0', false],
            ['test', false],
            [null, true],
            ['', true]
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $constraint($test[0]));
        }
    }

    public function testMustMatch()
    {
        $constraint = Constraints::mustMatch('test');

        $this->assertInstanceOf('\Closure', $constraint);

        $tests = [
            [null, null],
            ['', null],
            [0, false],
            ['0', false],
            ['this is a test', false],
            ['test', true]
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $constraint($test[0], ''));
        }

        // See if field references are being utilizes in the constraint.
        $constraint = Constraints::mustMatch(new FieldReference('test_reference'));

        $testForm = [
            'test_reference' => 'test',
            'wrong_reference' => 'nooo'
        ];

        $callback = function ($field) use ($testForm) {
            return $testForm[$field];
        };

        $this->assertTrue($constraint('test', $callback));
        $this->assertFalse($constraint('nooo', $callback));
    }
}