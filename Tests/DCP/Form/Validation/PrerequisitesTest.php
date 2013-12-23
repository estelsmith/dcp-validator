<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\FieldReference;
use DCP\Form\Validation\Prerequisites;

class PrerequisitesTest extends \PHPUnit_Framework_TestCase
{
    public function testNotBlank()
    {
        $constraint = Prerequisites::notBlank(new FieldReference('test_field'));

        $this->assertInstanceOf('\Closure', $constraint);

        $form = [
            'test_field' => null
        ];

        $callback = function ($field) use (&$form) {
            return $form[$field];
        };

        $tests = [
            [null, false],
            ['', false],
            ['something', true],
            [0, true],
            ['0', true]
        ];

        foreach ($tests as $test) {
            $form['test_field'] = $test[0];
            $this->assertEquals($test[1], $constraint($test[0], $callback));
        }
    }

    public function testIsBlank()
    {
        $constraint = Prerequisites::isBlank(new FieldReference('test_field'));

        $this->assertInstanceOf('\Closure', $constraint);

        $form = [
            'test_field' => null
        ];

        $callback = function ($field) use (&$form) {
            return $form[$field];
        };

        $tests = [
            [0, false],
            ['0', false],
            ['test', false],
            [null, true],
            ['', true]
        ];

        foreach ($tests as $test) {
            $form['test_field'] = $test[0];
            $this->assertEquals($test[1], $constraint($test[0], $callback));
        }
    }

    public function testMustMatch()
    {
        $constraint = Prerequisites::mustMatch(new FieldReference('test_field'), 'test');

        $this->assertInstanceOf('\Closure', $constraint);

        $form = [
            'test_field' => null
        ];

        $callback = function ($field) use (&$form) {
            return $form[$field];
        };

        $tests = [
            [null, false],
            ['', false],
            [0, false],
            ['0', false],
            ['this is a test', false],
            ['test', true]
        ];

        foreach ($tests as $test) {
            $form['test_field'] = $test[0];
            $this->assertSame($test[1], $constraint($test[0], $callback));
        }

        // See if field references are being utilizes in the prerequisite.
        $constraint = Prerequisites::mustMatch(new FieldReference('test_field'), new FieldReference('another_field'));

        $form = [
            'test_field' => 'this is a reference test',
            'another_field' => 'this is a reference test'
        ];

        $this->assertTrue($constraint('', $callback));

        $form = [
            'test_field' => 'this is a reference test',
            'another_field' => 'this is a bad reference test'
        ];

        $this->assertFalse($constraint('', $callback));
    }
}