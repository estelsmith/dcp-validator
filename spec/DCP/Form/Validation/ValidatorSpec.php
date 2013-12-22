<?php

namespace spec\DCP\Form\Validation;

use DCP\Form\Validation\Constraints;
use DCP\Form\Validation\FieldReference;
use DCP\Form\Validation\Filters;
use DCP\Form\Validation\ResultInterface;
use DCP\Form\Validation\Rule;
use DCP\Form\Validation\RuleSet;
use DCP\Form\Validation\RuleSetInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use spec\Stubs\Validator\TestForm;
use spec\Stubs\Validator\TestFormMissingGetter;
use spec\Stubs\Validator\TestFormMissingSetter;

require __DIR__ . '/../../../Stubs/Validator/TestForm.php';
require __DIR__ . '/../../../Stubs/Validator/TestFormMissingGetter.php';
require __DIR__ . '/../../../Stubs/Validator/TestFormMissingSetter.php';

class ValidatorSpec extends ObjectBehavior
{
    public function it_implements_the_proper_interface()
    {
        $this->shouldImplement('DCP\Form\Validation\ValidatorInterface');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('DCP\Form\Validation\Validator');
    }

    public function it_cannot_set_rule_set_if_rule_set_does_not_implement_rule_set_interface()
    {
        $this->shouldThrow()->duringSetRuleSet('not a rule set');
    }

    public function it_can_set_and_get_the_rule_set(RuleSetInterface $rule_set)
    {
        $this->setRuleSet($rule_set);
        $this->getRuleSet()->shouldBe($rule_set);
    }

    public function it_cannot_set_form_if_form_is_not_an_array_or_object()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringSetForm('not valid');
    }

    public function it_can_set_and_get_the_form_when_form_is_an_array()
    {
        $form = [
            'test_field' => 'test_value',
            'another_field' => 'another_value'
        ];

        $this->setForm($form);
        $this->getForm()->shouldBe($form);
    }

    public function it_can_set_and_get_the_form_when_form_is_an_object()
    {
        $form = new \stdClass();
        $form->test_field = 'test_value';
        $form->another_field = 'another_value';

        $this->setForm($form);
        $this->getForm()->shouldBe($form);
    }

    public function it_will_return_a_result_object_after_validation()
    {
        $rule_set = new RuleSet();

        $this->setRuleSet($rule_set);

        $this->validate()->shouldReturnAnInstanceOf('DCP\Form\Validation\ResultInterface');
    }

    public function it_will_apply_filters_from_rule_set_during_validation_when_form_is_an_array()
    {
        $form_before = [
            'test_field' => 'test_value',
            'another_field' => 'ANOTHER_VALUE'
        ];

        $form_after = [
            'test_field' => 'TEST_VALUE',
            'another_field' => 'another_value'
        ];

        // @TODO: find a way to make it not reliant on the actual behavior of other classes.
        $rule_set = new RuleSet();
        $rule_set
            ->add(
                (new Rule())
                ->setFieldName('test_field')
                ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                ->setFieldName('another_field')
                ->addFilter(Filters::toLowerCase())
            )
        ;

        $this->setRuleSet($rule_set);
        $this->setForm($form_before);

        $this->validate();

        $this->getForm()->shouldBe($form_after);
    }

    public function it_cannot_apply_filters_from_rule_set_when_form_is_an_object_and_is_missing_field_setter()
    {
        $form = new TestFormMissingSetter();
        $form->setAnotherField('ANOTHER_VALUE');

        // @TODO: find a way to make it not reliant on the actual behavior of other classes.
        $rule_set = new RuleSet();
        $rule_set
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->addFilter(Filters::toLowerCase())
            )
        ;

        $this->setRuleSet($rule_set);
        $this->setForm($form);

        $this->shouldThrow('DCP\Form\Validation\Exception\DomainException')->duringValidate();
    }

    public function it_will_apply_filters_from_rule_set_during_validation_when_form_is_an_object()
    {
        $form = new TestForm();
        $form->setTestField('test_value');
        $form->setAnotherField('ANOTHER_VALUE');

        // @TODO: find a way to make it not reliant on the actual behavior of other classes.
        $rule_set = new RuleSet();
        $rule_set
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->addFilter(Filters::toLowerCase())
            )
        ;

        $this->setRuleSet($rule_set);
        $this->setForm($form);

        $this->validate();

        $this->getForm()->getTestField()->shouldBe('TEST_VALUE');
        $this->getForm()->getAnotherField()->shouldBe('another_value');
    }

    public function it_will_test_constraints_from_rule_set_during_validation_when_form_is_an_array()
    {
        $form = [
            'test_field' => 'test_value',
            'another_field' => 'ANOTHER_VALUE',
            'reference_field' => 'this is a referenced field',
            'good_reference' => 'this is a referenced field',
            'bad_reference' => 'this is wrong...'
        ];

        // @TODO: find a way to make it not reliant on the actual behavior of other classes.
        $rule_set = new RuleSet();
        $rule_set
            ->add(
                (new Rule())
                    ->setFieldName('test_field')
                    ->setMessage('test_error')
                    ->addConstraint(Constraints::formatDigits())
            )
            ->add(
                (new Rule())
                    ->setFieldName('another_field')
                    ->setMessage('another_error')
                    ->addConstraint(Constraints::isBlank())
            )
            ->add(
                (new Rule())
                    ->setFieldName('good_reference')
                    ->setMessage('good_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('reference_field')))
            )
            ->add(
                (new Rule())
                    ->setFieldName('bad_reference')
                    ->setMessage('bad_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('reference_field')))
            )
        ;

        $this->setRuleSet($rule_set);
        $this->setForm($form);

        /** @var ResultInterface $result */
        $result = $this->validate();

        $result->getError('test_field')->shouldBe('test_error');
        $result->getError('another_field')->shouldBe('another_error');
        $result->getError('good_reference')->shouldBe(false);
        $result->getError('bad_reference')->shouldBe('bad_reference_error');
    }

    public function it_cannot_test_constraints_during_validation_when_form_is_an_object_and_is_missing_field_getter()
    {
        $form = new TestFormMissingGetter();
        $form->setAnotherField('ANOTHER_VALUE');

        // @TODO: find a way to make it not reliant on the actual behavior of other classes.
        $rule_set = new RuleSet();
        $rule_set
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->addFilter(Filters::toUpperCase())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->addFilter(Filters::toLowerCase())
            )
        ;

        $this->setRuleSet($rule_set);
        $this->setForm($form);

        $this->shouldThrow('DCP\Form\Validation\Exception\DomainException')->duringValidate();
    }

    public function it_will_test_constraints_from_rule_set_during_validation_when_form_is_an_object()
    {
        $form = new TestForm();
        $form
            ->setTestField('test_value')
            ->setAnotherField('ANOTHER_VALUE')
            ->setReferenceField('this is a reference field')
            ->setGoodReference($form->getReferenceField())
            ->setBadReference('this is wrong...')
        ;

        // @TODO: find a way to make it not reliant on the actual behavior of other classes.
        $rule_set = new RuleSet();
        $rule_set
            ->add(
                (new Rule())
                    ->setFieldName('testField')
                    ->setMessage('test_error')
                    ->addConstraint(Constraints::formatDigits())
            )
            ->add(
                (new Rule())
                    ->setFieldName('anotherField')
                    ->setMessage('another_error')
                    ->addConstraint(Constraints::isBlank())
            )
            ->add(
                (new Rule())
                    ->setFieldName('goodReference')
                    ->setMessage('good_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('referenceField')))
            )
            ->add(
                (new Rule())
                    ->setFieldName('badReference')
                    ->setMessage('bad_reference_error')
                    ->addConstraint(Constraints::mustMatch(new FieldReference('referenceField')))
            )
        ;

        $this->setRuleSet($rule_set);
        $this->setForm($form);

        /** @var ResultInterface $result */
        $result = $this->validate();

        $result->getError('testField')->shouldBe('test_error');
        $result->getError('anotherField')->shouldBe('another_error');
        $result->getError('goodReference')->shouldBe(false);
        $result->getError('badReference')->shouldBe('bad_reference_error');
    }

    public function it_will_not_validate_when_validation_group_is_not_a_string()
    {
        $this->shouldThrow('DCP\Form\Validation\Exception\InvalidArgumentException')->duringValidate(['not a string.']);
    }

    public function it_will_validate_requested_validation_group()
    {
        $form = [
            'page_1_field_1' => 'page 1 test data',
            'page_1_field_2' => '',
            'page_2_field_1' => 'page 2 test data',
            'page_2_field_2' => ''
        ];

        $ruleSet = new RuleSet();
        $ruleSet
            ->add(
                (new Rule())
                    ->setFieldName('page_1_field_1')
                    ->setMessage('page_1_field_1_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_1')
            )
            ->add(
                (new Rule())
                    ->setFieldName('page_1_field_2')
                    ->setMessage('page_1_field_2_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_1')
            )
            ->add(
                (new Rule())
                    ->setFieldName('page_2_field_1')
                    ->setMessage('page_2_field_1_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_2')
            )
            ->add(
                (new Rule())
                    ->setFieldName('page_2_field_2')
                    ->setMessage('page_2_field_2_error')
                    ->addConstraint(Constraints::notBlank())
                    ->addValidationGroup('page_2')
            )
        ;

        $this->setRuleSet($ruleSet);
        $this->setForm($form);

        /** @var ResultInterface $result */
        $result = $this->validate('page_1');

        $result->getError('page_1_field_1')->shouldBe(false);
        $result->getError('page_1_field_2')->shouldBe('page_1_field_2_error');
        $result->getError('page_2_field_1')->shouldBe(false);
        $result->getError('page_2_field_2')->shouldBe(false);

        /** @var ResultInterface $result */
        $result = $this->validate('page_2');

        $result->getError('page_1_field_1')->shouldBe(false);
        $result->getError('page_1_field_2')->shouldBe(false);
        $result->getError('page_2_field_1')->shouldBe(false);
        $result->getError('page_2_field_2')->shouldBe('page_2_field_2_error');
    }
}
