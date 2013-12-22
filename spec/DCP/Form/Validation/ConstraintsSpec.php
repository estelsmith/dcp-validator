<?php

namespace spec\DCP\Form\Validation;

use DCP\Form\Validation\FieldReference;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ConstraintsSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('DCP\Form\Validation\Constraints');
    }

    public function it_validates_not_blank_properly()
    {
        $constraint = $this->notBlank();

        $constraint->shouldBeAnInstanceOf(\Closure::class);

        $tests = [
            [null, false],
            ['', false],
            ['something', true],
            [0, true],
            ['0', true]
        ];

        foreach ($tests as $test) {
            $constraint($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_validates_format_email_properly()
    {
        $constraint = $this->formatEmail();

        $constraint->shouldBeAnInstanceOf(\Closure::class);

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
            $constraint($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_validates_format_digits_properly()
    {
        $constraint = $this->formatDigits();

        $constraint->shouldBeAnInstanceOf(\Closure::class);

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
            $constraint($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_validates_format_numeric_properly()
    {
        $constraint = $this->formatNumeric();

        $constraint->shouldBeAnInstanceOf(\Closure::class);

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
            $constraint($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_validates_format_regex_properly()
    {
        $constraint = $this->formatRegex('/test/');

        $constraint->shouldBeAnInstanceOf(\Closure::class);

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
            $constraint($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_validates_is_blank_properly()
    {
        $constraint = $this->isBlank();

        $constraint->shouldBeAnInstanceOf(\Closure::class);

        $tests = [
            [0, false],
            ['0', false],
            ['test', false],
            [null, true],
            ['', true]
        ];

        foreach ($tests as $test) {
            $constraint($test[0])->shouldReturn($test[1]);
        }
    }

    public function it_validates_matches_value_properly()
    {
        $constraint = $this->matchesValue('test');

        $constraint->shouldBeAnInstanceOf(\Closure::class);

        $tests = [
            [null, null],
            ['', null],
            [0, false],
            ['0', false],
            ['this is a test', false],
            ['test', true]
        ];

        foreach ($tests as $test) {
            $constraint($test[0], '')->shouldReturn($test[1]);
        }

        // See if field references are being utilizes in the constraint.
        $constraint = $this->matchesValue(new FieldReference('test_reference'));

        $testForm = [
            'test_reference' => 'test',
            'wrong_reference' => 'nooo'
        ];

        $callback = function ($field) use ($testForm) {
            return $testForm[$field];
        };

        $constraint('test', $callback)->shouldReturn(true);
        $constraint('nooo', $callback)->shouldReturn(false);
    }
}
