<?php

namespace spec\DCP\Form\Validation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FiltersSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('DCP\Form\Validation\Filters');
    }

    public function it_filters_trim_properly()
    {
        $filter = $this->trim();

        $filter->shouldBeAnInstanceOf('\Closure');

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
            $filter($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_filters_to_lower_case_properly()
    {
        $filter = $this->toLowerCase();

        $filter->shouldBeAnInstanceOf('\Closure');

        $tests = [
            [null, ''],
            ['', ''],
            ['test', 'test'],
            ['TEST', 'test'],
            ['TeSt', 'test']
        ];

        foreach ($tests as $test) {
            $filter($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_filters_to_upper_case_properly()
    {
        $filter = $this->toUpperCase();

        $filter->shouldBeAnInstanceOf('\Closure');

        $tests = [
            [null, ''],
            ['', ''],
            ['TEST', 'TEST'],
            ['test', 'TEST'],
            ['TeSt', 'TEST']
        ];

        foreach ($tests as $test) {
            $filter($test[0])->shouldReturn($test[1]);
        }
    }
}
