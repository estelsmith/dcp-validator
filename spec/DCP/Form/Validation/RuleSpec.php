<?php

namespace spec\DCP\Form\Validation;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RuleSpec extends ObjectBehavior
{
    public function it_implements_the_proper_interface()
    {
        $this->shouldImplement('DCP\Form\Validation\RuleInterface');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DCP\Form\Validation\Rule');
    }

    public function it_cannot_add_filter_that_is_not_callable()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringAddFilter('Totally not callable');
    }

    public function it_can_add_and_get_filters()
    {
        $filters = [
            function () { return 'yep'; },
            function () { return 'indeed'; }
        ];

        $this->addFilter($filters[0]);
        $this->addFilter($filters[1]);

        $this->getFilters()->shouldBe($filters);
    }

    public function it_cannot_set_field_name_that_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringSetFieldName(55);
    }

    public function it_can_set_and_get_field_name()
    {
        $this->setFieldName('lol wot');
        $this->getFieldName()->shouldBe('lol wot');
    }

    public function it_has_default_message()
    {
        $this->getMessage()->shouldBe('This field failed validation');
    }

    public function it_cannot_set_message_that_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringSetMessage(55);
    }

    public function it_can_set_and_get_message()
    {
        $this->setMessage('lol wot');
        $this->getMessage()->shouldBe('lol wot');
    }

    public function it_cannot_add_constraint_that_is_not_callable()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringAddConstraint('Totally not callable');
    }

    public function it_can_add_and_get_constraints()
    {
        $filters = [
            function () { return 'yep'; },
            function () { return 'indeed'; }
        ];

        $this->addConstraint($filters[0]);
        $this->addConstraint($filters[1]);

        $this->getConstraints()->shouldBe($filters);
    }

    public function it_cannot_add_validation_group_that_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringAddValidationGroup(['not a string.']);
    }

    public function it_can_add_and_get_validation_groups()
    {
        $validationGroups = [
            'page_1',
            'page_2'
        ];

        $this->addValidationGroup($validationGroups[0]);
        $this->addValidationGroup($validationGroups[1]);

        $this->getValidationGroups()->shouldBe($validationGroups);
    }
}
