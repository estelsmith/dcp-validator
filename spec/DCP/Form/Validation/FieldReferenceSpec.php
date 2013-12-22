<?php

namespace spec\DCP\Form\Validation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FieldReferenceSpec extends ObjectBehavior
{
    public function it_is_initializable_with_string_argument()
    {
        $this->beConstructedWith('test');
        $this->shouldHaveType('DCP\Form\Validation\FieldReference');
    }

    public function it_can_be_converted_to_a_string()
    {
        $this->beConstructedWith('test');
        $this->__toString()->shouldBe('test');
    }

// @TODO: Uncomment when phpspec supports this type of error checking against object constructors.
//    public function it_is_not_initializable_with_non_string_argument()
//    {
//        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->during('__construct', ['test']);
//    }
}
