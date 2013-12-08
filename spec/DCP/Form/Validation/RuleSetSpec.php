<?php

namespace spec\DCP\Form\Validation;

use DCP\Form\Validation\RuleInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleSetSpec extends ObjectBehavior
{
    public function it_implements_the_proper_interface()
    {
        $this->shouldImplement('DCP\Form\Validation\RuleSetInterface');
        $this->shouldImplement('\Iterator');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DCP\Form\Validation\RuleSet');
    }

    public function it_cannot_add_rule_if_rule_does_not_implement_rule_interface()
    {
        $this->shouldThrow()->duringAdd('not_a_rule');
    }

    public function it_can_add_and_iterate_rules(RuleInterface $rule_1, RuleInterface $rule_2)
    {
        $rules = [
            $rule_1,
            $rule_2
        ];

        $index = 0;

        $this->add($rule_1);
        $this->add($rule_2);

        // @TODO: Figure out why foreach isn't working inside phpspec
        $this->rewind();
        while ($this->valid() === true) {
            $this->current()->shouldBe($rules[$index]);
            $this->next();
            ++$index;
        }
    }
}
