<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\Filters;

class FiltersTest extends \PHPUnit_Framework_TestCase
{
    public function testTrim()
    {
        $filter = Filters::trim();

        $this->assertInstanceOf('\Closure', $filter);

        $tests = [
            [null, ''],
            ['', ''],
            [' ', ''],
            ["\t", ''],
            ["\t\r\n\n\n\n", ''],
            ['test', 'test'],
            [' test', 'test'],
            ["\t test", 'test'],
            ["\t\r\n\n\n test", 'test']
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $filter($test[0]));
        }
    }

    public function testToLowerCase()
    {
        $filter = Filters::toLowerCase();

        $this->assertInstanceOf('\Closure', $filter);

        $tests = [
            [null, ''],
            ['', ''],
            ['test', 'test'],
            ['TEST', 'test'],
            ['TeSt', 'test']
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $filter($test[0]));
        }
    }

    public function testToUpperCase()
    {
        $filter = Filters::toUpperCase();

        $this->assertInstanceOf('\Closure', $filter);

        $tests = [
            [null, ''],
            ['', ''],
            ['TEST', 'TEST'],
            ['test', 'TEST'],
            ['TeSt', 'TEST']
        ];

        foreach ($tests as $test) {
            $this->assertEquals($test[1], $filter($test[0]));
        }
    }
}