<?php

namespace Tests\DCP\Form\Validation;

use DCP\Form\Validation\RuleSet;

class RuleSetTest extends \PHPUnit_Framework_TestCase
{
    public function testObjectImplementsProperInterfaces()
    {
        $instance = new RuleSet();

        $this->assertInstanceOf('DCP\Form\Validation\RuleSetInterface', $instance);
        $this->assertInstanceOf('\Iterator', $instance);
    }

    public function testCanAddAndIterateRules()
    {
        $rules = [
            $this->getMock('DCP\Form\Validation\RuleInterface'),
            $this->getMock('DCP\Form\Validation\RuleInterface')
        ];

        $instance = new RuleSet();

        $instance->add($rules[0]);
        $instance->add($rules[1]);

        foreach ($instance as $index => $rule) {
            $this->assertSame($rules[$index], $rule);
        }
    }
}