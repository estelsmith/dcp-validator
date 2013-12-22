<?php

namespace spec\DCP\Form\Validation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResultSpec extends ObjectBehavior
{
    public function it_implements_the_proper_interface()
    {
        $this->shouldImplement('Dcp\Form\Validation\ResultInterface');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Dcp\Form\Validation\Result');
    }

    public function it_cannot_add_an_error_when_error_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringAddError(52, 'test');
    }

    public function it_cannot_add_an_error_when_field_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringAddError('test', 52);
    }

    public function it_can_add_and_get_errors()
    {
        $errors = [
            'test_field' => 'test_error',
            'another_field' => 'another_error'
        ];

        $this->addError('test_error', 'test_field');
        $this->addError('another_error', 'another_field');

        $this->getErrors()->shouldBe($errors);
    }

    public function it_is_valid_when_no_errors_have_been_added()
    {
        $this->isValid()->shouldBe(true);
    }

    public function it_is_not_valid_when_an_error_has_been_added()
    {
        $this->addError('error', 'field');
        $this->isValid()->shouldBe(false);
    }

    public function it_cannot_get_error_when_field_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringGetError(52);
    }

    public function it_cannot_get_field_error_when_field_has_no_errors()
    {
        $this->getError('non_existent')->shouldBe(false);
    }

    public function it_can_get_field_error_when_field_has_errors()
    {
        $this->addError('test_error', 'test_field');
        $this->getError('test_field')->shouldBe('test_error');
    }

    public function it_cannot_check_field_for_errors_when_field_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringFieldHasError(52);
    }

    public function it_does_not_have_field_error_when_no_error_has_been_added()
    {
        $this->fieldHasError('non_existent')->shouldBe(false);
    }

    public function it_has_field_error_when_error_has_been_added()
    {
        $this->addError('test_error', 'test_field');
        $this->fieldHasError('test_field')->shouldBe(true);
    }
}
